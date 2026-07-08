<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #1f2937; font-size: 12px; margin: 0; padding: 30px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 22px; }
        .header p { margin: 2px 0 0; color: #6b7280; font-size: 11px; }
        .title { font-size: 16px; font-weight: bold; margin: 18px 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        td, th { padding: 8px 10px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; font-size: 11px; text-transform: uppercase; color: #6b7280; }
        .valor { font-size: 20px; color: #16a34a; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .assinatura { margin-top: 60px; text-align: center; }
        .assinatura .linha { border-top: 1px solid #4b5563; width: 260px; margin: 0 auto; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'One') }}</h1>
        <p>Proposta Comercial nº {{ str_pad($oportunidade->id, 5, '0', STR_PAD_LEFT) }} — {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="title">Dados do Cliente</div>
    <table>
        <tr><th>Interessado</th><td>{{ $oportunidade->interessado?->nome ?? '—' }}</td></tr>
        <tr><th>Contato</th><td>{{ $oportunidade->interessado?->email ?? $oportunidade->interessado?->celular ?? $oportunidade->interessado?->telefone ?? '—' }}</td></tr>
        <tr><th>Consultor</th><td>{{ $oportunidade->consultor?->nome ?? '—' }}</td></tr>
    </table>

    <div class="title">Proposta</div>
    <table>
        <tr><th>Oportunidade</th><td>{{ $oportunidade->titulo }}</td></tr>
        <tr><th>Produto / Serviço</th><td>{{ $oportunidade->produtoServico?->nome ?? '—' }}</td></tr>
        @if($oportunidade->curso)<tr><th>Curso</th><td>{{ $oportunidade->curso->nome }}</td></tr>@endif
        <tr><th>Previsão de fechamento</th><td>{{ optional($oportunidade->data_previsao_fechamento)->format('d/m/Y') ?? '—' }}</td></tr>
        @if($oportunidade->observacoes)<tr><th>Observações</th><td>{{ $oportunidade->observacoes }}</td></tr>@endif
    </table>

    <p>Valor total da proposta:</p>
    <p class="valor">R$ {{ number_format($oportunidade->valor, 2, ',', '.') }}</p>

    <div class="assinatura">
        <div class="linha">{{ $oportunidade->consultor?->nome ?? config('app.name') }}</div>
    </div>

    <div class="footer">Documento gerado eletronicamente por {{ config('app.name', 'One') }} em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
