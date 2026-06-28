@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <i class="fa-solid fa-user-circle text-primary-500 text-xl"></i>
        <h1 class="text-2xl font-bold text-gray-800">Meu Perfil</h1>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl border p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="w-24 h-24 bg-primary-500 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                {{ strtoupper(substr($user->nome, 0, 1)) }}
            </div>
            <div class="text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->nome }}</h2>
                <p class="text-gray-500">{{ $user->login }}</p>
                <div class="flex flex-wrap gap-3 mt-2 justify-center sm:justify-start">
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                        <i class="fa-solid fa-envelope text-xs"></i> {{ $user->email }}
                    </span>
                    @if($user->grupoOperador)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">
                        <i class="fa-solid fa-users text-xs"></i> {{ $user->grupoOperador->nome }}
                    </span>
                    @endif
                    @if($user->departamento)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                        <i class="fa-solid fa-building text-xs"></i> {{ $user->departamento->nome }}
                    </span>
                    @endif
                </div>
                @if($user->ultimo_acesso)
                <p class="text-xs text-gray-400 mt-2">
                    <i class="fa-solid fa-clock mr-1"></i> Ultimo acesso: {{ $user->ultimo_acesso->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Edit Profile Form --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-primary-500"></i> Editar Dados
            </h3>
            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $user->nome) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                    @error('nome')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                    <input type="text" value="{{ $user->login }}" disabled
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 text-sm cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">O login nao pode ser alterado.</p>
                </div>

                <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-check mr-1"></i> Salvar Alteracoes
                </button>
            </form>
        </div>

        {{-- Change Password Form --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-lock text-primary-500"></i> Alterar Senha
            </h3>
            <form method="POST" action="{{ route('perfil.update-password') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="senha_atual" class="block text-sm font-medium text-gray-700 mb-1">Senha Atual</label>
                    <input type="password" name="senha_atual" id="senha_atual"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                    @error('senha_atual')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nova_senha" class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                    <input type="password" name="nova_senha" id="nova_senha"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                    @error('nova_senha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nova_senha_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                    <input type="password" name="nova_senha_confirmation" id="nova_senha_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                </div>

                <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 transition">
                    <i class="fa-solid fa-key mr-1"></i> Alterar Senha
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
