<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;

/** Laporan Jawatankuasa Penilaian print view to Word; numbered rows become borderless tables since Word ignores flexbox. */
class LaporanPenilaianWord
{
    /** Row class => [indent pt, number column pt]. */
    private const BARIS = [
        'laporan-subitem' => [15, 24],
        'laporan-subsubitem' => [36, 34],
        'laporan-roman-item' => [36, 20],
    ];

    public static function convert(string $printHtml, string $title): string
    {
        return view('newModule.penilaian_kewangan.word.shell', [
            'title' => $title,
            'body' => self::susun(PrintViewToWord::body($printHtml)),
            'headerLeft' => self::hurufBesar(PrintViewToWord::marginBox($printHtml, 'top-left')),
            'headerRight' => self::hurufBesar(PrintViewToWord::marginBox($printHtml, 'top-right')),
            'footerRight' => self::hurufBesar(PrintViewToWord::marginBox($printHtml, 'bottom-right')),
        ])->render();
    }

    private static function susun(string $body): string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="laporan-root">' . $body . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);

        foreach (self::BARIS as $class => [$indent, $lebarNombor]) {
            foreach (self::cari($xpath, $class) as $row) {
                $anak = self::anak($row);
                $nombor = count($anak) > 1 ? array_shift($anak) : null;
                $inden = self::adaKelas($row->parentNode, 'laporan-roman-list--nested') ? 63 : $indent;
                self::jadikanJadual($dom, $row, 6, array_values(array_filter([
                    ["width:{$inden}pt", []],
                    $nombor ? ["width:{$lebarNombor}pt", [$nombor]] : null,
                    ['text-align:justify', $anak],
                ])));
            }
        }

        foreach (self::cari($xpath, 'jpt-member') as $row) {
            $anak = self::anak($row);
            $tebal = self::adaKelas($row->parentNode, 'jpt-members--lantikan') ? ';font-weight:700' : '';
            self::jadikanJadual($dom, $row, self::adaKelas($row->parentNode, 'pengesahan-block') ? 22 : 10, [
                ['width:36pt', []],
                ["width:66pt{$tebal}", array_slice($anak, 0, 1)],
                ['width:10pt', array_slice($anak, 1, 1)],
                ['', array_slice($anak, 2)],
            ]);
        }

        foreach (self::cari($xpath, 'signature-line') as $garis) {
            $garis->appendChild($dom->createTextNode(str_repeat('.', 60)));
        }

        foreach (self::cari($xpath, 'force-new-page') as $blok) {
            $putus = $dom->createElement('br');
            $putus->setAttribute('clear', 'all');
            $putus->setAttribute('style', 'page-break-before:always');
            $blok->parentNode->insertBefore($putus, $blok);
        }

        // Word only matches single-class elements, so keep each element's main class.
        foreach ($xpath->query('//*[@class]') as $el) {
            $el->setAttribute('class', strtok(trim($el->getAttribute('class')), ' ') ?: '');
        }

        $html = '';
        foreach ($dom->getElementById('laporan-root')->childNodes as $node) {
            $html .= $dom->saveHTML($node);
        }

        return $html;
    }

    /** @return DOMElement[] */
    private static function cari(DOMXPath $xpath, string $class): array
    {
        return iterator_to_array($xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')]"));
    }

    private static function adaKelas($node, string $class): bool
    {
        return $node instanceof DOMElement && in_array($class, explode(' ', (string) $node->getAttribute('class')), true);
    }

    /** @return DOMElement[] */
    private static function anak(DOMElement $row): array
    {
        return array_values(array_filter(iterator_to_array($row->childNodes), fn ($n) => $n instanceof DOMElement));
    }

    /** Replaces $row with a one-row borderless table; each cell is [inline style, elements whose content it takes]. */
    private static function jadikanJadual(DOMDocument $dom, DOMElement $row, int $jarakBawah, array $sel): void
    {
        $table = $dom->createElement('table');
        $table->setAttribute('class', 'word-baris');
        $table->setAttribute('style', 'width:100%;border-collapse:collapse');
        $tr = $table->appendChild($dom->createElement('tr'));

        foreach ($sel as [$gaya, $sumber]) {
            $td = $tr->appendChild($dom->createElement('td'));
            $td->setAttribute('valign', 'top');
            $td->setAttribute('style', trim("padding:0 0 {$jarakBawah}pt 0;{$gaya}", ';'));
            foreach ($sumber as $el) {
                while ($el->firstChild) {
                    $td->appendChild($el->firstChild);
                }
            }
        }

        $row->parentNode->replaceChild($table, $row);
    }

    /** Uppercases a margin-box string (escaped text joined by <br>), since Word drops text-transform in headers. */
    private static function hurufBesar(string $kotak): string
    {
        return implode('<br>', array_map(fn ($baris) => e(mb_strtoupper(html_entity_decode($baris))), explode('<br>', $kotak)));
    }
}
