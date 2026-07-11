<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\EmissaoLayout;
use App\Models\Grau;
use App\Models\InstituicaoEnsino;
use App\Models\Matricula;
use App\Models\PeriodoLetivo;
use App\Models\TurmaMontada;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * 79 Emissão de Alunos Matriculados — construtor de relatório dinâmico (padrão EDUQ):
 * abas Filtros | Colunas | Layout de Página, dropdown de Layouts salvos, e export PDF/CSV/XLSX.
 */
class EmissaoAlunosController extends Controller
{
    private const FUNCAO = 79;

    /** Catálogo de colunas disponíveis: chave => [rótulo, resolvedor]. */
    private function catalogo(): array
    {
        return [
            'matricula' => ['Matrícula', fn (Matricula $m) => $m->numero_matricula ?? $m->id],
            'aluno' => ['Aluno', fn (Matricula $m) => $m->aluno?->pessoa?->nome ?? '—'],
            'cpf' => ['CPF', fn (Matricula $m) => $m->aluno?->pessoa?->cpf ?? '—'],
            'email' => ['E-mail', fn (Matricula $m) => $m->aluno?->pessoa?->email ?? '—'],
            'celular' => ['Celular', fn (Matricula $m) => $m->aluno?->pessoa?->celular ?? '—'],
            'curso' => ['Curso', fn (Matricula $m) => $m->turma?->curso?->nome ?? '—'],
            'turma' => ['Turma', fn (Matricula $m) => $m->turmaMontada?->nome ?? $m->turma?->nome ?? '—'],
            'situacao' => ['Situação', fn (Matricula $m) => ucfirst($m->situacao)],
            'forma_ingresso' => ['Forma de Ingresso', fn (Matricula $m) => $m->formaIngresso?->nome ?? '—'],
            'data_matricula' => ['Data da Matrícula', fn (Matricula $m) => $m->data_matricula?->format('d/m/Y') ?? '—'],
            'data_inicio' => ['Início das Aulas', fn (Matricula $m) => $m->data_inicio_aulas?->format('d/m/Y') ?? '—'],
            'previsao_conclusao' => ['Previsão de Conclusão', fn (Matricula $m) => $m->previsao_conclusao?->format('d/m/Y') ?? '—'],
            'operador' => ['Operador', fn (Matricula $m) => $m->consultor?->nome ?? '—'],
            'como_conheceu' => ['Como conheceu', fn (Matricula $m) => $m->como_conheceu ?? '—'],
        ];
    }

    /** Colunas padrão quando não há layout escolhido. */
    private function colunasPadrao(): array
    {
        return ['matricula', 'aluno', 'curso', 'turma', 'situacao'];
    }

    public function index(Request $request)
    {
        $catalogo = collect($this->catalogo())->map(fn ($v) => $v[0]); // chave => rótulo
        $layouts = EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', self::FUNCAO)->orderBy('nome')->get();

        // layout selecionado (ou padrão do usuário, ou colunas padrão)
        $layoutAtual = $request->filled('layout_id')
            ? $layouts->firstWhere('id', (int) $request->layout_id)
            : $layouts->firstWhere('padrao', true);
        $colunasSel = $layoutAtual?->colunas ?? $this->colunasPadrao();

        return view('academico.emissoes.alunos-matriculados', [
            'catalogo' => $catalogo,
            'colunasSel' => $colunasSel,
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'cursos' => Curso::where('ativo', true)->orderBy('nome')->get(),
            'graus' => Grau::orderBy('nome')->get(),
            'turmasMontadas' => TurmaMontada::orderBy('nome')->get(),
            'periodos' => PeriodoLetivo::orderByDesc('id')->get(),
            'instituicoes' => InstituicaoEnsino::orderBy('nome')->get(),
            'operadores' => User::where('ativo', true)->orderBy('nome')->get(),
            'situacoes' => ['ativa', 'nao_confirmada', 'confirmada', 'trancada', 'cancelada', 'concluida', 'transferida', 'evadida'],
        ]);
    }

    /** Aplica os filtros do EDUQ à query de matrículas. */
    private function filtrar(Request $request)
    {
        $q = Matricula::with(['aluno.pessoa', 'turma.curso', 'turmaMontada', 'formaIngresso', 'consultor']);

        // faixas de data
        $faixa = function ($campo, $ini, $fim) use ($q) {
            if ($ini) {
                $q->whereDate($campo, '>=', $ini);
            }
            if ($fim) {
                $q->whereDate($campo, '<=', $fim);
            }
        };
        $faixa('data_matricula', $request->matricula_inicio, $request->matricula_fim);
        $faixa('previsao_conclusao', $request->previsao_inicio, $request->previsao_fim);
        $faixa('created_at', $request->criacao_inicio, $request->criacao_fim);

        // multi-selects
        if ($request->filled('cursos')) {
            $q->whereHas('turma', fn ($t) => $t->whereIn('curso_id', (array) $request->cursos));
        }
        if ($request->filled('turmas_montadas')) {
            $q->whereIn('turma_montada_id', (array) $request->turmas_montadas);
        }
        if ($request->filled('situacoes')) {
            $q->whereIn('situacao', (array) $request->situacoes);
        }
        if ($request->filled('operadores')) {
            $q->whereIn('consultor_id', (array) $request->operadores);
        }
        // toggles
        if ($request->boolean('ocultar_blacklist')) {
            $q->whereHas('aluno.pessoa', fn ($p) => $p->where('blacklist', false));
        }

        return $q->orderByDesc('id')->get();
    }

    public function emitir(Request $request)
    {
        $catalogo = $this->catalogo();
        $colunas = array_values(array_filter((array) $request->input('colunas', $this->colunasPadrao()), fn ($c) => isset($catalogo[$c])));
        if (empty($colunas)) {
            $colunas = $this->colunasPadrao();
        }

        $matriculas = $this->filtrar($request);
        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $matriculas->map(fn ($m) => array_map(fn ($c) => $catalogo[$c][1]($m), $colunas))->all();

        $formato = strtolower((string) $request->input('formato', 'pdf'));
        $arquivo = 'alunos_matriculados';
        if ($formato === 'csv') {
            return $this->csv($cabecalho, $linhas, $arquivo);
        }
        if ($formato === 'xlsx') {
            return $this->xlsx($cabecalho, $linhas, $arquivo);
        }

        $orientacao = $request->input('orientacao', 'landscape');
        $pdf = Pdf::loadView('emissoes.academico.relatorio', [
            'titulo' => 'Emissão de Alunos Matriculados',
            'subtitulo' => 'Total: ' . count($linhas) . ' matrícula(s)',
            'colunas' => $cabecalho,
            'linhas' => $linhas,
        ])->setPaper($request->input('papel', 'a4'), $orientacao);

        return $pdf->stream($arquivo . '.pdf');
    }

    public function salvarLayout(Request $request)
    {
        $v = $request->validate([
            'nome' => 'required|string|max:100',
            'colunas' => 'required|array|min:1',
            'colunas.*' => 'string',
            'padrao' => 'nullable|boolean',
        ]);
        if ($request->boolean('padrao')) {
            EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', self::FUNCAO)->update(['padrao' => false]);
        }
        EmissaoLayout::create([
            'user_id' => auth()->id(),
            'funcao_codigo' => self::FUNCAO,
            'nome' => $v['nome'],
            'colunas' => array_values($v['colunas']),
            'filtros' => $request->input('filtros', []),
            'padrao' => $request->boolean('padrao'),
        ]);

        return back()->with('success', 'Layout salvo.');
    }

    public function excluirLayout(EmissaoLayout $layout)
    {
        abort_unless($layout->user_id === auth()->id(), 403);
        $layout->delete();

        return back()->with('success', 'Layout removido.');
    }

    private function csv(array $colunas, array $linhas, string $arquivo)
    {
        return response()->streamDownload(function () use ($colunas, $linhas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $colunas, ';');
            foreach ($linhas as $l) {
                fputcsv($out, array_values((array) $l), ';');
            }
            fclose($out);
        }, $arquivo . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function xlsx(array $colunas, array $linhas, string $arquivo)
    {
        $esc = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $colLetra = fn ($n) => chr(65 + ($n % 26));
        $rowXml = fn ($cells, $rowNum) => '<row r="' . $rowNum . '">' . implode('', array_map(
            fn ($c, $i) => '<c r="' . $colLetra($i) . $rowNum . '" t="inlineStr"><is><t xml:space="preserve">' . $esc($c) . '</t></is></c>',
            $cells, array_keys($cells)
        )) . '</row>';
        $xml = $rowXml(array_values($colunas), 1);
        $r = 2;
        foreach ($linhas as $l) {
            $xml .= $rowXml(array_values((array) $l), $r++);
        }
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $xml . '</sheetData></worksheet>';
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Relatório" sheetId="1" r:id="rId1"/></sheets></workbook>';
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        return response()->download($tmp, $arquivo . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
