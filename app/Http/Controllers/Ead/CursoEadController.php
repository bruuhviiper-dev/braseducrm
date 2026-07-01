<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\AgrupadorCurso;
use App\Models\CursoEad;
use App\Models\Profissional;
use App\Models\SubAgrupadorCurso;
use App\Models\TagCursoEad;
use App\Models\VideoEad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoEadController extends Controller
{
    public function index()
    {
        $cursos = CursoEad::with(['tutor.pessoa'])->withCount('modulos')->orderBy('nome')->paginate(20);

        return view('ead.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('ead.cursos.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $curso = CursoEad::create($data['curso']);
        $curso->tags()->sync($data['tags']);
        $this->salvarModulos($curso, $data['modulos']);

        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD criado com sucesso.');
    }

    public function edit(CursoEad $curso)
    {
        $curso->load(['modulos.aulas', 'tags']);

        return view('ead.cursos.form', $this->dados($curso));
    }

    public function update(Request $request, CursoEad $curso)
    {
        $data = $this->validar($request);
        $curso->update($data['curso']);
        $curso->tags()->sync($data['tags']);
        $this->salvarModulos($curso, $data['modulos']);

        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD atualizado com sucesso.');
    }

    public function destroy(CursoEad $curso)
    {
        $curso->delete();

        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD removido com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'nullable|integer|min:0',
            'valor' => 'nullable|numeric|min:0',
            'tutor_id' => 'nullable|exists:profissionais,id',
            'agrupador_curso_id' => 'nullable|exists:agrupadores_curso,id',
            'sub_agrupador_curso_id' => 'nullable|exists:sub_agrupadores_curso,id',
            'ativo' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags_curso_ead,id',
            'modulos' => 'nullable|array',
            'modulos.*.nome' => 'nullable|string|max:255',
            'modulos.*.aulas' => 'nullable|array',
            'modulos.*.aulas.*.titulo' => 'nullable|string|max:255',
            'modulos.*.aulas.*.tipo' => 'nullable|in:video,texto,questionario',
            'modulos.*.aulas.*.video_ead_id' => 'nullable|exists:videos_ead,id',
            'modulos.*.aulas.*.conteudo' => 'nullable|string',
        ]);

        return [
            'curso' => [
                'nome' => $v['nome'],
                'descricao' => $v['descricao'] ?? null,
                'carga_horaria' => $v['carga_horaria'] ?? null,
                'valor' => $v['valor'] ?? null,
                'tutor_id' => $v['tutor_id'] ?? null,
                'agrupador_curso_id' => $v['agrupador_curso_id'] ?? null,
                'sub_agrupador_curso_id' => $v['sub_agrupador_curso_id'] ?? null,
                'ativo' => $request->has('ativo'),
            ],
            'tags' => $v['tags'] ?? [],
            'modulos' => collect($v['modulos'] ?? [])
                ->filter(fn ($m) => !empty($m['nome']))
                ->values()->all(),
        ];
    }

    private function salvarModulos(CursoEad $curso, array $modulos): void
    {
        DB::transaction(function () use ($curso, $modulos) {
            $curso->modulos()->delete(); // cascade remove aulas
            foreach ($modulos as $mi => $m) {
                $modulo = $curso->modulos()->create([
                    'nome' => $m['nome'],
                    'ordem' => $mi,
                ]);
                foreach (collect($m['aulas'] ?? [])->filter(fn ($a) => !empty($a['titulo']))->values() as $ai => $a) {
                    $modulo->aulas()->create([
                        'titulo' => $a['titulo'],
                        'tipo' => $a['tipo'] ?? 'video',
                        'video_ead_id' => ($a['tipo'] ?? 'video') === 'video' ? ($a['video_ead_id'] ?? null) : null,
                        'conteudo' => ($a['tipo'] ?? 'video') === 'texto' ? ($a['conteudo'] ?? null) : null,
                        'ordem' => $ai,
                    ]);
                }
            }
        });
    }

    private function dados(?CursoEad $curso): array
    {
        return [
            'curso' => $curso,
            'profissionais' => Profissional::with('pessoa')->get(),
            'agrupadores' => AgrupadorCurso::orderBy('nome')->get(),
            'subAgrupadores' => SubAgrupadorCurso::orderBy('nome')->get(),
            'tagsDisponiveis' => TagCursoEad::orderBy('nome')->get(),
            'videos' => VideoEad::orderBy('titulo')->get(),
            'tagsSelecionadas' => $curso ? $curso->tags->pluck('id')->all() : [],
        ];
    }
}
