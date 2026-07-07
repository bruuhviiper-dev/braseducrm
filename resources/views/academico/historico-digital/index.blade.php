@extends('layouts.app')
@section('title', 'Histórico Escolar Digital')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">226</span>
            <h1 class="text-lg font-bold text-gray-800">Histórico Escolar Digital</h1>
        </div>
        <div class="p-5 space-y-4">
            @if($assinatura)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm">
                <i class="fa-solid fa-file-signature mr-1"></i> Certificado A1 configurado ({{ $assinatura->credenciais['signatario'] ?? 'signatário institucional' }}): os históricos saem assinados digitalmente com código de verificação.
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Certificado digital A1 não configurado: os documentos saem como <strong>pendentes de assinatura</strong>. Configure em Integrações › Assinatura Digital A1 (Eduqsign).
            </div>
            @endif

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">CPF</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Documento</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($alunos as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $a->pessoa?->nome ?? 'Aluno #'.$a->id }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a->pessoa?->cpf ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('academico.historico-digital.gerar', $a) }}" target="_blank" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 inline-flex items-center gap-1"><i class="fa-solid fa-file-pdf"></i> Histórico Digital</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Nenhum aluno com matrícula.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
