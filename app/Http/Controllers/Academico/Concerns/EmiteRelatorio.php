<?php

namespace App\Http\Controllers\Academico\Concerns;

use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Export de relatório nos 3 formatos do EDUQ (PDF / CSV / XLSX) sem dependência externa.
 * Reutilizado pelos construtores de relatório dinâmicos das emissões acadêmicas.
 */
trait EmiteRelatorio
{
    protected function emitirRelatorio(string $formato, string $titulo, ?string $subtitulo, array $colunas, array $linhas, string $arquivo, string $orientacao = 'landscape', string $papel = 'a4')
    {
        $formato = strtolower($formato);
        if ($formato === 'csv') {
            return $this->relatorioCsv($colunas, $linhas, $arquivo);
        }
        if ($formato === 'xlsx') {
            return $this->relatorioXlsx($colunas, $linhas, $arquivo);
        }

        return Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper($papel, $orientacao)
            ->stream($arquivo . '.pdf');
    }

    protected function relatorioCsv(array $colunas, array $linhas, string $arquivo)
    {
        return response()->streamDownload(function () use ($colunas, $linhas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $colunas, ';');
            foreach ($linhas as $l) {
                fputcsv($out, array_values((array) $l), ';');
            }
            fclose($out);
        }, $arquivo . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function relatorioXlsx(array $colunas, array $linhas, string $arquivo)
    {
        $esc = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $colLetra = fn ($n) => chr(65 + ($n % 26));
        $rowXml = fn ($cells, $rowNum) => '<row r="' . $rowNum . '">' . implode('', array_map(
            fn ($c, $i) => '<c r="' . $colLetra($i) . $rowNum . '" t="inlineStr"><is><t xml:space="preserve">' . $esc($c) . '</t></is></c>',
            $cells, array_keys($cells)
        )) . '</row>';
        $xml = $rowXml(array_values($colunas), 1);
        $r = 2;
        foreach ($linhas as $l) {
            $xml .= $rowXml(array_values((array) $l), $r++);
        }
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $xml . '</sheetData></worksheet>';
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Relatório" sheetId="1" r:id="rId1"/></sheets></workbook>';
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        return response()->download($tmp, $arquivo . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
