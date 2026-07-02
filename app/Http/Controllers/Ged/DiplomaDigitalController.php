<?php

namespace App\Http\Controllers\Ged;

use App\Http\Controllers\Controller;
use App\Models\DiplomaDigital;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;

class DiplomaDigitalController extends Controller
{
    public function index(Request $request)
    {
        $query = DiplomaDigital::with(['aluno.pessoa', 'matricula.aluno.pessoa', 'curso']);

        // Filtros (fiel ao EDUQ): Situação, Matrícula, Data de solicitação, Data de registro
        if ($s = $request->get('situacao')) {
            $query->where('situacao', $s);
        }
        if ($mid = $request->get('matricula_id')) {
            $query->where('matricula_id', $mid);
        }
        if ($di = $request->get('solicitacao_inicio')) {
            $query->whereDate('data_solicitacao', '>=', $di);
        }
        if ($df = $request->get('solicitacao_fim')) {
            $query->whereDate('data_solicitacao', '<=', $df);
        }
        if ($ri = $request->get('registro_inicio')) {
            $query->whereDate('data_registro', '>=', $ri);
        }
        if ($rf = $request->get('registro_fim')) {
            $query->whereDate('data_registro', '<=', $rf);
        }

        $diplomas = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $matriculas = Matricula::with('aluno.pessoa')->orderByDesc('id')->get();
        $situacoes = DiplomaDigital::situacoes();

        return view('ged.diplomas.index', compact('diplomas', 'matriculas', 'situacoes', 'request'));
    }

    public function create()
    {
        return view('ged.diplomas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        DiplomaDigital::create($this->validateData($request));
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital criado com sucesso.');
    }

    public function edit(DiplomaDigital $diploma)
    {
        return view('ged.diplomas.form', array_merge($this->dados($diploma), ['diploma' => $diploma]));
    }

    private function dados(?DiplomaDigital $diploma): array
    {
        return [
            'diploma' => $diploma,
            'matriculas' => Matricula::with('aluno.pessoa', 'turma')->orderByDesc('id')->get(),
            'alunos' => Aluno::with('pessoa')->get(),
            'cursos' => Curso::orderBy('nome')->get(),
        ];
    }

    public function update(Request $request, DiplomaDigital $diploma)
    {
        $diploma->update($this->validateData($request));
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital atualizado com sucesso.');
    }

    public function destroy(DiplomaDigital $diploma)
    {
        $diploma->delete();
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        $v = $request->validate([
            'matricula_id' => 'nullable|exists:matriculas,id',
            'aluno_id' => 'nullable|exists:alunos,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'numero_registro' => 'nullable|string|max:255',
            'situacao' => 'required|in:pendente,emitido,assinado,registrado',
            'data_solicitacao' => 'nullable|date',
            'data_registro' => 'nullable|date',
            'data_emissao' => 'nullable|date',
            'data_colacao' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        // deriva aluno/curso da matrícula quando informada (matrícula-cêntrico, fiel ao EDUQ)
        if (!empty($v['matricula_id'])) {
            $m = Matricula::with('turma')->find($v['matricula_id']);
            $v['aluno_id'] = $v['aluno_id'] ?: $m?->aluno_id;
            $v['curso_id'] = $v['curso_id'] ?: $m?->turma?->curso_id;
        }

        return $v;
    }
}
