<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GrupoOperador;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OperadorController extends Controller
{
    public function index()
    {
        $operadores = User::with(['grupoOperador', 'departamento'])->orderBy('nome')->paginate(20);
        return view('admin.operadores.index', compact('operadores'));
    }

    public function create()
    {
        $grupos = GrupoOperador::where('ativo', true)->orderBy('nome')->get();
        $departamentos = Departamento::where('ativo', true)->orderBy('nome')->get();
        $profissionais = \App\Models\Profissional::with('pessoa')->where('ativo', true)->get();
        return view('admin.operadores.form', compact('grupos', 'departamentos', 'profissionais'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'grupo_operador_id' => 'nullable|exists:grupo_operadores,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'profissional_id' => 'nullable|exists:profissionais,id',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['ativo'] = $request->boolean('ativo', true);
        $data['is_admin'] = $request->boolean('is_admin');
        $data['exigir_troca_senha'] = $request->boolean('exigir_troca_senha');
        User::create($data);
        return redirect()->route('admin.operadores.index')->with('success', 'Operador criado com sucesso.');
    }

    public function edit(User $operador)
    {
        $grupos = GrupoOperador::where('ativo', true)->orderBy('nome')->get();
        $departamentos = Departamento::where('ativo', true)->orderBy('nome')->get();
        $profissionais = \App\Models\Profissional::with('pessoa')->where('ativo', true)->get();
        return view('admin.operadores.form', compact('operador', 'grupos', 'departamentos', 'profissionais'));
    }

    public function update(Request $request, User $operador)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'login' => ['required', 'string', 'max:255', Rule::unique('users', 'login')->ignore($operador->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($operador->id)],
            'password' => 'nullable|string|min:6',
            'grupo_operador_id' => 'nullable|exists:grupo_operadores,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'profissional_id' => 'nullable|exists:profissionais,id',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['ativo'] = $request->boolean('ativo', true);
        $data['is_admin'] = $request->boolean('is_admin');
        $data['exigir_troca_senha'] = $request->boolean('exigir_troca_senha');
        $operador->update($data);
        return redirect()->route('admin.operadores.index')->with('success', 'Operador atualizado com sucesso.');
    }

    public function destroy(User $operador)
    {
        if ($operador->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }
        $operador->delete();
        return redirect()->route('admin.operadores.index')->with('success', 'Operador removido com sucesso.');
    }
}
