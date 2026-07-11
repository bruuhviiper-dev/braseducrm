<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula confirmada — One</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow p-10 text-center max-w-md">
        <i class="fa-solid fa-circle-check text-6xl text-green-500 mb-4"></i>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Matrícula confirmada!</h1>
        <p class="text-sm text-gray-500">Parabéns, {{ $nome }}! Sua inscrição em <b>{{ $link->abertura->curso->nome ?? $link->abertura->nome ?? 'seu curso' }}</b> foi recebida. Nossa secretaria acadêmica entrará em contato com o contrato e o acesso ao portal do aluno.</p>
    </div>
</body>
</html>
