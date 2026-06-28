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
    <h1>Declaração de Matrícula</h1>

    <div class="corpo">
        <p>Declaramos para os devidos fins que
            <strong>{{ $matricula->aluno?->pessoa?->nome ?? '—' }}</strong>
            @if($matricula->aluno?->ra), portador(a) do RA {{ $matricula->aluno->ra }},@endif
            encontra-se regularmente matriculado(a) no curso
            <strong>{{ $matricula->turma?->curso?->nome ?? '—' }}</strong>,
            turma <strong>{{ $matricula->turma?->nome ?? '—' }}</strong>,
            nesta instituição de ensino, com situação <strong>{{ ucfirst($matricula->situacao) }}</strong>.
        </p>

        <p>Matrícula nº {{ $matricula->numero_matricula ?? $matricula->id }}, realizada em
            {{ optional($matricula->data_matricula)->format('d/m/Y') ?? '—' }}.
        </p>

        <p>Por ser expressão da verdade, firmamos a presente declaração.</p>
    </div>

    <div class="assinatura">
        <div class="linha-assinatura">Secretaria Acadêmica</div>
    </div>

    <div class="rodape">{{ config('app.name', 'BrasEduCRM') }} — Documento emitido em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
