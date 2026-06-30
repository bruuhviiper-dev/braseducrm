<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 13px; color: #222; line-height: 1.8; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 30px; }
        .corpo { text-align: justify; margin: 0 30px; }
        .assinatura { margin-top: 80px; text-align: center; }
        .linha-assinatura { border-top: 1px solid #333; width: 280px; margin: 0 auto; padding-top: 4px; }
        .rodape { margin-top: 40px; font-size: 11px; text-align: center; color: #555; }
    </style>
</head>
<body>
    <h1>Declaração de Aula Ministrada</h1>
    <div class="corpo">
        <p>Declaramos para os devidos fins que o(a) professor(a)
            <strong>{{ $professor->pessoa?->nome ?? '—' }}</strong>
            ministrou aulas da disciplina <strong>{{ $disciplina->nome }}</strong>
            no período de <strong>{{ $inicio->format('d/m/Y') }}</strong> a
            <strong>{{ $fim->format('d/m/Y') }}</strong> nesta instituição de ensino.</p>

        <p>Por ser expressão da verdade, firmamos a presente declaração.</p>
    </div>

    <div class="assinatura">
        <div class="linha-assinatura">Coordenação Acadêmica</div>
    </div>

    <div class="rodape">{{ config('app.name', 'BrasEduCRM') }} — Documento emitido em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
