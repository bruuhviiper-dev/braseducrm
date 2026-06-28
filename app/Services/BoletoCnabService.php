<?php

namespace App\Services;

use App\Models\Integracao;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Gera arquivo de remessa bancária no layout CNAB 400 (padrão FEBRABAN/CBR643).
 * As credenciais (banco, agência, conta, carteira, convênio) vêm da integração "boleto".
 */
class BoletoCnabService
{
    private array $cred;

    public function __construct()
    {
        $integracao = Integracao::where('chave', 'boleto')->first();
        $this->cred = $integracao->credenciais ?? [];
    }

    public function configurado(): bool
    {
        return !empty($this->cred['banco']) && !empty($this->cred['agencia']) && !empty($this->cred['conta']);
    }

    /**
     * Gera o conteúdo do arquivo de remessa CNAB 400 a partir de uma coleção de títulos.
     */
    public function gerarRemessa(Collection $titulos, int $sequencialArquivo = 1): string
    {
        $linhas = [];
        $linhas[] = $this->header($sequencialArquivo);

        $seq = 1;
        foreach ($titulos as $titulo) {
            $linhas[] = $this->detalhe($titulo, ++$seq);
        }

        $linhas[] = $this->trailer($seq + 1);

        // CNAB usa CRLF e registros de 400 posições.
        return implode("\r\n", $linhas) . "\r\n";
    }

    private function header(int $sequencial): string
    {
        $banco = $this->num($this->cred['banco'] ?? '', 3);
        $agencia = $this->num($this->cred['agencia'] ?? '', 5);
        $conta = $this->num($this->cred['conta'] ?? '', 7);
        $empresa = $this->texto(config('app.name', 'BRASEDUCRM'), 30);
        $data = now()->format('dmy');

        $r = '0';                              // 001 tipo de registro
        $r .= '1';                             // 002 código remessa
        $r .= 'REMESSA';                       // 003-009
        $r .= '01';                            // 010-011 código serviço
        $r .= $this->texto('COBRANCA', 15);    // 012-026
        $r .= $this->num('', 20);              // 027-046 agência/conta/zeros (simplificado)
        $r .= $empresa;                        // 047-076 nome empresa
        $r .= $banco;                          // 077-079 código do banco
        $r .= $this->texto('BANCO', 15);       // 080-094 nome do banco
        $r .= $data;                           // 095-100 data gravação
        $r .= $this->num('', 294);             // 101-394 brancos/zeros
        $r .= $this->num($sequencial, 6);      // 395-400 sequencial
        return $this->ajusta($r);
    }

    private function detalhe($titulo, int $sequencial): string
    {
        $carteira = $this->num($this->cred['carteira'] ?? '', 3);
        $agencia = $this->num($this->cred['agencia'] ?? '', 5);
        $conta = $this->num($this->cred['conta'] ?? '', 7);
        $nossoNumero = $this->num($titulo->nosso_numero ?: $titulo->id, 11);
        $valor = $this->valor($titulo->valor_original);
        $vencimento = optional($titulo->data_vencimento)->format('dmy') ?: '000000';
        $documento = $this->texto($titulo->numero_documento ?: $titulo->id, 10);
        $sacado = $this->texto($titulo->pessoa->nome ?? 'CLIENTE', 40);
        $cpfCnpj = $this->num(preg_replace('/\D/', '', $titulo->pessoa->cpf ?? $titulo->pessoa->cnpj ?? ''), 14);

        $r = '1';                              // 001 tipo registro detalhe
        $r .= $this->num('', 19);              // 002-020 inscrição/empresa (simplificado)
        $r .= $agencia . $conta;               // 021-032 agência/conta
        $r .= $this->texto('', 25);            // 033-057 uso empresa
        $r .= $nossoNumero;                    // 058-068 nosso número
        $r .= $this->num('', 20);              // 069-088 diversos
        $r .= $carteira;                       // 089-091 carteira
        $r .= $this->num('', 47);              // 092-138 diversos
        $r .= '01';                            // 139-140 código ocorrência (01 = remessa)
        $r .= $documento;                      // 141-150 nº documento
        $r .= $vencimento;                     // 151-156 vencimento
        $r .= $valor;                          // 157-169 valor do título
        $r .= $this->num('', 21);              // 170-190 banco/agência cobradora
        $r .= $this->num('', 2);               // 191-192 espécie
        $r .= 'N';                             // 193 aceite
        $r .= now()->format('dmy');            // 194-199 data emissão
        $r .= $this->num('', 25);              // 200-224 instruções/juros/desconto (simplificado)
        $r .= $this->num('', 13);              // 225-237 valor abatimento
        $r .= '02';                            // 238-239 tipo inscrição sacado (02=CPF... simplificado)
        $r .= $cpfCnpj;                        // 240-253 cpf/cnpj sacado
        $r .= $sacado;                         // 254-293 nome sacado
        $r .= $this->texto('', 40);            // 294-333 endereço (simplificado)
        $r .= $this->num('', 61);              // 334-394 diversos
        $r .= $this->num($sequencial, 6);      // 395-400 sequencial
        return $this->ajusta($r);
    }

    private function trailer(int $sequencial): string
    {
        $r = '9';                              // 001 tipo registro trailer
        $r .= $this->num('', 393);             // 002-394 brancos
        $r .= $this->num($sequencial, 6);      // 395-400 sequencial
        return $this->ajusta($r);
    }

    /** Garante exatamente 400 posições. */
    private function ajusta(string $linha): string
    {
        return substr(str_pad($linha, 400, ' '), 0, 400);
    }

    private function num($valor, int $tamanho): string
    {
        $valor = preg_replace('/\D/', '', (string) $valor);
        return str_pad(substr($valor, -$tamanho), $tamanho, '0', STR_PAD_LEFT);
    }

    private function texto($valor, int $tamanho): string
    {
        $valor = Str::ascii((string) $valor);
        $valor = strtoupper(preg_replace('/[^A-Za-z0-9 ]/', '', $valor));
        return substr(str_pad($valor, $tamanho, ' '), 0, $tamanho);
    }

    /** Valor em centavos com 13 posições. */
    private function valor($valor): string
    {
        $centavos = (int) round(((float) $valor) * 100);
        return str_pad((string) $centavos, 13, '0', STR_PAD_LEFT);
    }
}
