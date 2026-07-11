<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula Online — One</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-[#0d0f12] text-white py-4">
        <div class="max-w-xl mx-auto px-4 flex items-center justify-between">
            <span class="text-2xl font-extrabold tracking-tight">One</span>
            <span class="text-xs text-gray-400">Matrícula Online</span>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 py-8">
        @if($expirado)
        <div class="bg-white rounded-2xl shadow p-8 text-center">
            <i class="fa-regular fa-clock text-5xl text-red-400 mb-4"></i>
            <h1 class="text-xl font-bold text-gray-800 mb-2">Este link de matrícula expirou</h1>
            <p class="text-sm text-gray-500">A validade das condições oferecidas terminou em {{ $link->expira_em->format('d/m/Y H:i') }}. Entre em contato com o seu consultor para gerar uma nova oferta.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-5">
                <p class="text-xs uppercase tracking-wide text-blue-200">{{ $link->abertura->nome ?? 'Processo de Inscrição' }}</p>
                <h1 class="text-xl font-bold">{{ $link->abertura->curso->nome ?? 'Matrícula' }}</h1>
                @if($link->expira_em)
                <p class="text-xs text-blue-100 mt-1"><i class="fa-regular fa-clock mr-1"></i>Oferta válida até {{ $link->expira_em->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            <div class="px-6 py-5 space-y-4">
                @if($link->abertura?->valor_matricula || $link->abertura?->valor_curso)
                <div class="flex gap-3">
                    @if($link->abertura->valor_matricula)
                    <div class="flex-1 border rounded-xl p-3 text-center"><p class="text-[11px] text-gray-400 uppercase">Matrícula</p><p class="text-lg font-bold text-gray-800">R$ {{ number_format($link->abertura->valor_matricula, 2, ',', '.') }}</p></div>
                    @endif
                    @if($link->abertura->valor_curso)
                    <div class="flex-1 border rounded-xl p-3 text-center"><p class="text-[11px] text-gray-400 uppercase">Curso</p><p class="text-lg font-bold text-gray-800">R$ {{ number_format($link->abertura->valor_curso, 2, ',', '.') }}</p></div>
                    @endif
                </div>
                @endif
                <form method="POST" action="{{ route('matricula-link.inscrever', $link->token) }}" class="space-y-3">
                    @csrf
                    <div><label class="block text-xs text-gray-500 mb-1">Nome completo *</label>
                        <input type="text" name="nome" required value="{{ $link->oportunidade->interessado->nome ?? '' }}" class="w-full border rounded-lg px-3 py-2.5 text-sm"></div>
                    <div><label class="block text-xs text-gray-500 mb-1">E-mail *</label>
                        <input type="email" name="email" required value="{{ $link->oportunidade->interessado->email ?? '' }}" class="w-full border rounded-lg px-3 py-2.5 text-sm"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-xs text-gray-500 mb-1">Telefone</label>
                            <input type="text" name="telefone" value="{{ $link->oportunidade->interessado->celular ?? $link->oportunidade->interessado->telefone ?? '' }}" class="w-full border rounded-lg px-3 py-2.5 text-sm"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">CPF</label>
                            <input type="text" name="cpf" value="{{ $link->oportunidade->interessado->cpf ?? '' }}" class="w-full border rounded-lg px-3 py-2.5 text-sm"></div>
                    </div>
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition"><i class="fa-solid fa-lock mr-2"></i>Confirmar matrícula e pagar</button>
                    <p class="text-[11px] text-gray-400 text-center">Pagamento seguro. Ao confirmar você aceita o contrato de prestação de serviços educacionais.</p>
                </form>
            </div>
        </div>
        @endif
    </main>
</body>
</html>
