<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 13px; color: #222; line-height: 1.7; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .sub { text-align: center; color: #666; font-size: 11px; margin-bottom: 24px; }
        .valor { font-size: 22px; font-weight: bold; text-align: center; margin: 16px 0; }
        .corpo { text-align: justify; margin: 0 30px; }
        table.det { width: 100%; border-collapse: collapse; margin: 16px 0; }
        table.det td { padding: 4px 8px; border-bottom: 1px solid #ddd; }
        .assinatura { margin-top: 70px; text-align: center; }
        .linha-assinatura { border-top: 1px solid #333; width: 280px; margin: 0 auto; padding-top: 4px; }
        .rodape { margin-top: 40px; font-size: 11px; text-align: center; color: #555; }
    </style>
</head>
<body>
    <h1>Recibo de Pagamento</h1>
    <div class="sub">{{ config('app.name', 'BrasEduCRM') }}</div>

    <div class="valor">R$ {{ number_format($titulo->valor_pago ?: $titulo->valor_original, 2, ',', '.') }}</div>

    <div class="corpo">
        <p>Recebemos de <strong>{{ $titulo->pessoa->nome ?? '—' }}</strong>
            a importância acima referente ao documento
            <strong>{{ $titulo->numero_documento ?? $titulo->id }}</strong>.
        </p>
    </div>

    <table class="det">
        <tr><td><strong>Vencimento</strong></td><td>{{ optional($titulo->data_vencimento)->format('d/m/Y') ?? '—' }}</td></tr>
        <tr><td><strong>Pagamento</strong></td><td>{{ optional($titulo->data_pagamento)->format('d/m/Y') ?? '—' }}</td></tr>
        <tr><td><strong>Situação</strong></td><td>{{ ucfirst($titulo->situacao) }}</td></tr>
    </table>

    <div class="assinatura">
        <div class="linha-assinatura">Setor Financeiro</div>
    </div>

    <div class="rodape">Documento emitido em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
