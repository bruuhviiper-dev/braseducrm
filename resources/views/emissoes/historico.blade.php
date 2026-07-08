<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 12px; color: #222; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 2px; }
        .sub { text-align: center; color: #666; font-size: 11px; margin-bottom: 20px; }
        .info { margin-bottom: 16px; }
        .info td { padding: 2px 4px; }
        table.notas { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.notas th, table.notas td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        table.notas th { background: #f0f0f0; }
        .rodape { margin-top: 40px; font-size: 11px; text-align: center; color: #555; }
        .assinatura { margin-top: 60px; text-align: center; }
        .linha-assinatura { border-top: 1px solid #333; width: 250px; margin: 0 auto; padding-top: 4px; }
    </style>
</head>
<body>
    <h1>Histórico Escolar</h1>
    <div class="sub">{{ config('app.name', 'One') }}</div>

    <table class="info">
        <tr><td><strong>Aluno:</strong> {{ $aluno->pessoa->nome ?? '—' }}</td><td><strong>RA:</strong> {{ $aluno->ra ?? '—' }}</td></tr>
        @foreach($matriculas as $m)
        <tr><td colspan="2"><strong>Curso:</strong> {{ $m->turma?->curso?->nome ?? '—' }} — Turma {{ $m->turma?->nome ?? '—' }}</td></tr>
        @endforeach
    </table>

    <table class="notas">
        <thead>
            <tr><th>Disciplina</th><th style="text-align:center">Média Final</th><th style="text-align:center">Situação</th></tr>
        </thead>
        <tbody>
            @forelse($disciplinas as $d)
            <tr>
                <td>{{ $d['nome'] }}</td>
                <td style="text-align:center">{{ $d['media'] !== null ? number_format($d['media'], 2, ',', '.') : '—' }}</td>
                <td style="text-align:center">{{ ucfirst($d['situacao']) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center">Nenhuma nota lançada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="assinatura">
        <div class="linha-assinatura">Secretaria Acadêmica</div>
    </div>

    <div class="rodape">Documento emitido em {{ now()->format('d/m/Y H:i') }}.</div>
</body>
</html>
