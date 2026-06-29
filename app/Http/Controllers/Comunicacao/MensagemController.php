<?php

namespace App\Http\Controllers\Comunicacao;

use App\Http\Controllers\Controller;
use App\Models\MensagemEnviada;
use App\Models\TemplateMensagem;
use App\Models\Pessoa;
use App\Models\TituloReceber;
use App\Services\MensagemService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MensagemController extends Controller
{
    /** Histórico de mensagens enviadas. */
    public function index()
    {
        $mensagens = MensagemEnviada::with('pessoa')->orderByDesc('id')->paginate(20);
        return view('comunicacao.mensagens.index', compact('mensagens'));
    }

    /** Mensagem avulsa (84). */
    public function avulsaCreate()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $templates = TemplateMensagem::where('ativo', true)->orderBy('nome')->get();
        return view('comunicacao.mensagens.avulsa', compact('pessoas', 'templates'));
    }

    public function avulsaStore(Request $request, MensagemService $service)
    {
        $data = $request->validate([
            'pessoa_id' => 'nullable|exists:pessoas,id',
            'canal' => 'required|in:email,sms,whatsapp',
            'destinatario' => 'required|string|max:255',
            'assunto' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'template_id' => 'nullable|exists:templates_mensagem,id',
        ]);

        $msg = $service->enviar(
            $data['canal'], $data['destinatario'], $data['conteudo'],
            $data['assunto'] ?? null, $data['pessoa_id'] ?? null, $data['template_id'] ?? null
        );

        $tipo = $msg->situacao === 'enviada' ? 'success' : 'error';
        $texto = $msg->situacao === 'enviada' ? 'Mensagem enviada.' : 'Falha no envio: ' . $msg->erro;
        return redirect()->route('comunicacao.mensagens.index')->with($tipo, $texto);
    }

    /** Avisos de vencimento (86) e cobrança (88). */
    public function avisos(Request $request)
    {
        $modo = $request->get('modo', 'vencimento'); // vencimento | cobranca

        $query = TituloReceber::with('pessoa')->where('situacao', 'aberto');
        if ($modo === 'cobranca') {
            $query->where('data_vencimento', '<', now());
        } else {
            $query->whereBetween('data_vencimento', [now(), now()->addDays(7)]);
        }
        $titulos = $query->orderBy('data_vencimento')->paginate(20)->withQueryString();

        return view('comunicacao.mensagens.avisos', compact('titulos', 'modo'));
    }

    public function enviarAviso(Request $request, TituloReceber $titulo, MensagemService $service)
    {
        $data = $request->validate(['canal' => 'required|in:email,sms,whatsapp']);
        $titulo->load('pessoa');

        $destinatario = match ($data['canal']) {
            'email' => $titulo->pessoa->email ?? null,
            default => $titulo->pessoa->celular ?? $titulo->pessoa->telefone ?? null,
        };

        if (!$destinatario) {
            return back()->with('error', 'Pessoa sem contato para o canal selecionado.');
        }

        $venc = Carbon::parse($titulo->data_vencimento)->format('d/m/Y');
        $valor = number_format($titulo->valor_original, 2, ',', '.');
        $conteudo = "Olá {$titulo->pessoa->nome}, consta o título {$titulo->numero_documento} no valor de R$ {$valor} com vencimento em {$venc}. Em caso de pagamento já efetuado, desconsidere.";

        $service->enviar($data['canal'], $destinatario, $conteudo, 'Aviso financeiro', $titulo->pessoa_id);
        return back()->with('success', 'Aviso enviado para ' . $titulo->pessoa->nome . '.');
    }
}
