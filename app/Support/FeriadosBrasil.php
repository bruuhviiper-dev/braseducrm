<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Feriados nacionais brasileiros (fixos + móveis baseados na Páscoa).
 * Usado no Cadastro de Calendário (EDUQ função 35) para pré-marcar dias
 * não-letivos com a observação do feriado.
 */
class FeriadosBrasil
{
    /**
     * Retorna ['Y-m-d' => 'Nome do feriado'] para o ano informado.
     */
    public static function doAno(int $ano): array
    {
        $feriados = [
            "$ano-01-01" => 'Confraternização Universal',
            "$ano-04-21" => 'Tiradentes',
            "$ano-05-01" => 'Dia do Trabalho',
            "$ano-09-07" => 'Independência do Brasil',
            "$ano-10-12" => 'Nossa Senhora Aparecida',
            "$ano-11-02" => 'Finados',
            "$ano-11-15" => 'Proclamação da República',
            "$ano-11-20" => 'Consciência Negra',
            "$ano-12-25" => 'Natal',
        ];

        // Feriados móveis a partir do Domingo de Páscoa
        $pascoa = static::domingoDePascoa($ano);

        $moveis = [
            (clone $pascoa)->subDays(48)->format('Y-m-d') => 'Carnaval (segunda)',
            (clone $pascoa)->subDays(47)->format('Y-m-d') => 'Carnaval (terça)',
            (clone $pascoa)->subDays(2)->format('Y-m-d')  => 'Sexta-feira Santa',
            (clone $pascoa)->addDays(60)->format('Y-m-d')  => 'Corpus Christi',
        ];

        return array_merge($feriados, $moveis);
    }

    /** Algoritmo de Meeus/Jones/Butcher para o Domingo de Páscoa. */
    private static function domingoDePascoa(int $ano): Carbon
    {
        $a = $ano % 19;
        $b = intdiv($ano, 100);
        $c = $ano % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $mes = intdiv($h + $l - 7 * $m + 114, 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;

        return Carbon::createFromDate($ano, $mes, $dia)->startOfDay();
    }
}
