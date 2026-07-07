<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; text-align: center; margin-bottom: 2px; }
    .sub { text-align: center; color: #666; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
    th { background: #f0f0f0; font-size: 10px; text-transform: uppercase; }
    .box { border: 1px solid #ccc; padding: 8px 10px; margin-bottom: 12px; }
    .assinatura { margin-top: 24px; border: 2px solid {{ $assinatura ? '#15803d' : '#b45309' }}; padding: 10px 12px; }
    .assinatura h3 { margin: 0 0 4px; font-size: 12px; color: {{ $assinatura ? '#15803d' : '#b45309' }}; }
    .cod { font-family: DejaVu Sans Mono, monospace; letter-spacing: 1px; }
    .muted { color: #666; font-size: 10px; }
</style>
</head>
<body>
    <h1>HISTÓRICO ESCOLAR DIGITAL</h1>
    <p class="sub">Documento eletrônico — emitido em {{ $emitidoEm->format('d/m/Y H:i') }}</p>

    <div class="box">
        <strong>Aluno:</strong> {{ $aluno->pessoa?->nome }}<br>
        <strong>CPF:</strong> {{ $aluno->pessoa?->cpf ?? '—' }} &nbsp;·&nbsp;
        <strong>Data de nascimento:</strong> {{ $aluno->pessoa?->data_nascimento ? \Carbon\Carbon::parse($aluno->pessoa->data_nascimento)->format('d/m/Y') : '—' }}
    </div>

    <table>
        <thead><tr><th>Curso / Turma</th><th>Situação da matrícula</th></tr></thead>
        <tbody>
            @forelse($matriculas as $m)
            <tr>
                <td>{{ $m->turma?->curso?->nome ?? '—' }} {{ $m->turma?->nome ? '— '.$m->turma->nome : '' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $m->situacao)) }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Sem matrículas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table>
        <thead><tr><th>Disciplina</th><th>Média Final</th><th>Situação</th><th>Origem</th></tr></thead>
        <tbody>
            @forelse($disciplinas as $d)
            <tr>
                <td>{{ $d['nome'] }}</td>
                <td>{{ $d['media'] !== null ? number_format((float) $d['media'], 2, ',', '.') : '—' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', (string) $d['situacao'])) }}</td>
                <td class="muted">{{ $d['origem'] }}</td>
            </tr>
            @empty
            <tr><td colspan="4">Nenhuma disciplina lançada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="assinatura">
        @if($assinatura)
        <h3>ASSINADO DIGITALMENTE — CERTIFICADO A1</h3>
        <p>Signatário: <strong>{{ $assinatura->credenciais['signatario'] ?? 'Responsável institucional' }}</strong></p>
        @else
        <h3>PENDENTE DE ASSINATURA DIGITAL</h3>
        <p>O certificado digital A1 ainda não foi configurado em Integrações. Este documento não possui validade jurídica até ser assinado.</p>
        @endif
        <p>Código de verificação: <span class="cod">{{ $codigoVerificacao }}</span></p>
        <p class="muted">A autenticidade deste documento pode ser conferida informando o código de verificação à secretaria acadêmica.</p>
    </div>
</body>
</html>
