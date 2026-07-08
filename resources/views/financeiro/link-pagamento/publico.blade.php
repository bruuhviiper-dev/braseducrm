<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento — One</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-6">
            <span class="text-2xl font-extrabold tracking-tight">One</span>
            <p class="text-xs text-gray-400 mt-1">Pagamento seguro</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4 text-center">
            <i class="fa-solid fa-circle-check text-green-500 text-2xl block mb-1"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="border rounded-xl p-5 space-y-3 mb-6">
            <div class="flex justify-between text-sm"><span class="text-gray-500">Pagador</span><span class="font-semibold text-gray-800">{{ $titulo->pessoa?->nome }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Descrição</span><span class="text-gray-700">{{ $titulo->observacoes ?? 'Pagamento' }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Vencimento</span><span class="text-gray-700">{{ $titulo->data_vencimento?->format('d/m/Y') }}</span></div>
            <div class="flex justify-between items-center border-t pt-3">
                <span class="text-gray-500 text-sm">Valor</span>
                <span class="text-2xl font-bold text-gray-900">R$ {{ number_format($titulo->valor_original - ($titulo->valor_desconto ?? 0), 2, ',', '.') }}</span>
            </div>
        </div>

        @if($titulo->situacao === 'pago')
        <div class="text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-bold"><i class="fa-solid fa-circle-check"></i> Pagamento confirmado em {{ $titulo->data_pagamento?->format('d/m/Y') }}</span>
        </div>
        @else
        <form method="POST" action="{{ route('pagamento.publico.pagar', $titulo->token_pagamento) }}" class="space-y-3">
            @csrf
            <button type="submit" class="w-full py-3.5 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-brands fa-pix mr-2"></i>Pagar com Pix</button>
            <button type="submit" class="w-full py-3.5 bg-gray-800 hover:bg-gray-700 text-white rounded-full text-sm font-bold"><i class="fa-solid fa-credit-card mr-2"></i>Pagar com Cartão de Crédito</button>
            <p class="text-[11px] text-gray-400 text-center">Ambiente de demonstração: o pagamento é simulado e a baixa é processada automaticamente.</p>
        </form>
        @endif
    </div>
</body>
</html>
