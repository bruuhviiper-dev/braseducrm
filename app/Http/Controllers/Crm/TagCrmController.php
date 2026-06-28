<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\TagCrm;
use Illuminate\Http\Request;

class TagCrmController extends Controller
{
    public function index()
    {
        $tags = TagCrm::orderBy('nome')->paginate(20);
        return view('crm.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('crm.tags.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7',
        ]);
        TagCrm::create($data);
        return redirect()->route('crm.tags.index')->with('success', 'Tag criada com sucesso.');
    }

    public function edit(TagCrm $tag)
    {
        return view('crm.tags.form', compact('tag'));
    }

    public function update(Request $request, TagCrm $tag)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7',
        ]);
        $tag->update($data);
        return redirect()->route('crm.tags.index')->with('success', 'Tag atualizada com sucesso.');
    }

    public function destroy(TagCrm $tag)
    {
        $tag->delete();
        return redirect()->route('crm.tags.index')->with('success', 'Tag removida com sucesso.');
    }
}
