<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\VideoEad;
use Illuminate\Http\Request;

class VideoEadController extends Controller
{
    public function index()
    {
        $videos = VideoEad::orderByDesc('id')->paginate(20);

        return view('ead.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('ead.videos.form', ['video' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        if ($request->hasFile('arquivo')) {
            $data['arquivo'] = $request->file('arquivo')->store('ead/videos', 'public');
        }
        VideoEad::create($data);

        return redirect()->route('ead.videos.index')->with('success', 'Vídeo cadastrado com sucesso.');
    }

    public function edit(VideoEad $video)
    {
        return view('ead.videos.form', compact('video'));
    }

    public function update(Request $request, VideoEad $video)
    {
        $data = $this->validar($request);
        if ($request->hasFile('arquivo')) {
            $data['arquivo'] = $request->file('arquivo')->store('ead/videos', 'public');
        }
        $video->update($data);

        return redirect()->route('ead.videos.index')->with('success', 'Vídeo atualizado.');
    }

    public function destroy(VideoEad $video)
    {
        $video->delete();

        return redirect()->route('ead.videos.index')->with('success', 'Vídeo removido.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'arquivo' => 'nullable|file|mimetypes:video/mp4,video/webm,video/mpeg,video/ogg|max:204800',
        ]);
    }
}
