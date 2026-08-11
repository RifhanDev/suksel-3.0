<?php

namespace App\Support;

/**
 * Turns a paged.js print view into a Word-openable document.
 *
 * The letters are authored once for print. paged.js only runs in the browser, so the
 * server-rendered HTML is an inert <template> plus CSS margin boxes that Word knows
 * nothing about. This lifts the body out of the template, reads the running header and
 * signature block out of the @page rules, and hands both to a Word-flavoured shell.
 * Nothing here is letter-specific, so new paragraphs and conditionals carry over on
 * their own.
 */
class PrintViewToWord
{
    public static function convert(string $printHtml, string $title): string
    {
        return view('newModule.penyediaanSST.word.shell', [
            'title' => $title,
            'body' => self::body($printHtml),
            'headerLeft' => self::marginBox($printHtml, 'top-left'),
            'headerRight' => self::marginBox($printHtml, 'top-right'),
            'footerLeft' => self::marginBox($printHtml, 'bottom-left'),
        ])->render();
    }

    /** Everything paged.js would have paginated. */
    private static function body(string $printHtml): string
    {
        preg_match('/<template[^>]*data-ref="pagedjs-content"[^>]*>(.*?)<\/template>/s', $printHtml, $matches);

        return $matches[1] ?? '';
    }

    /**
     * Reads one @page margin box's content string. Returns the literal text with \A
     * escapes turned into line breaks, or an empty string when the box is a counter
     * expression rather than plain text.
     */
    private static function marginBox(string $printHtml, string $position): string
    {
        if (! preg_match('/@' . preg_quote($position, '/') . '\s*\{(.*?)\}/s', $printHtml, $block)) {
            return '';
        }

        if (! preg_match('/content:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $block[1], $content)) {
            return '';
        }

        $lines = preg_split('/\\\\A/', $content[1]);
        $lines = array_map(fn ($line) => e(trim($line)), $lines);

        return implode('<br>', array_filter($lines, fn ($line) => $line !== ''));
    }
}
