<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 2px; }
        .sub { font-size: 11px; color: #555; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f0f2f5; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; color: #444; border-bottom: 2px solid #ccc; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #fafafa; }
        .vazio { text-align: center; color: #999; padding: 20px; }
        .rodape { margin-top: 24px; font-size: 9px; text-align: center; color: #888; }
        .total { margin-top: 8px; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    @if(!empty($subtitulo))<div class="sub">{{ $subtitulo }}</div>@endif

    <table>
        <thead>
            <tr>@foreach($colunas as $c)<th>{{ $c }}</th>@endforeach</tr>
        </thead>
        <tbody>
            @forelse($linhas as $linha)
            <tr>@foreach($linha as $celula)<td>{{ $celula }}</td>@endforeach</tr>
            @empty
            <tr><td class="vazio" colspan="{{ count($colunas) }}">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">Total de registros: {{ count($linhas) }}</div>
    <div class="rodape">{{ config('app.name', 'BrasEduCRM') }} — Emitido em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
