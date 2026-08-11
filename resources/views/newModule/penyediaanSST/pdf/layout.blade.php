<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>@yield('title')</title>

    {{-- Screen-only chrome — media="screen" keeps it out of paged.js's print content. --}}
    <style media="screen">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #e5e7eb;
        }

        .no-print {
            text-align: center;
            padding: 16px 0;
        }

        .no-print button {
            padding: 8px 24px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }

        .no-print .btn-cetak {
            background: #2C3E9E;
        }

        .no-print .btn-word {
            background: #2B579A;
            margin-left: 8px;
        }

        .no-print .btn-tutup {
            background: #888;
            margin-left: 8px;
        }

        .pagedjs_pages {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 24px 0;
        }

        .pagedjs_page {
            background: #fff;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
        }
    </style>

    {{-- Document content — what paged.js reads from the <template> and lays out into A4 pages. --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.5;
        }

        strong {
            font-weight: 700;
        }

        @page {
            size: A4;
        }

        /* A block that must never be split across two pages. */
        .avoid-break {
            break-inside: avoid;
        }

        /* Starts a new sheet — one per page section while the layout is fixed. */
        .page-break {
            break-before: page;
        }

        /* Keeps a heading glued to the block that follows it. */
        .keep-with-next {
            break-after: avoid;
        }

        /* A value filled in later — reserves the line without printing a hint. */
        .blank {
            display: inline-block;
            border-bottom: 1px dotted #000;
            vertical-align: baseline;
        }

        .blank-sm {
            min-width: 54px;
        }

        .blank-md {
            min-width: 130px;
        }

        .blank-lg {
            min-width: 210px;
        }

        /* Fill-in rule spanning its own line. */
        .line {
            display: block;
            border-bottom: 1px dotted #000;
            height: 15px;
        }

        /* Fill-in rule taking the remaining width of a flex row. */
        .line-fill {
            flex: 1;
            border-bottom: 1px dotted #000;
            align-self: flex-end;
            margin-bottom: 3px;
        }

        .value-with-prefix {
            display: flex;
            gap: 6px;
        }

        .doc-title {
            text-align: center;
            font-weight: 700;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .field-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .field-label {
            flex: 0 0 92px;
        }

        .field-colon {
            flex: 0 0 12px;
        }

        .recipient {
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .recipient-role {
            font-weight: 700;
        }

        .salutation {
            margin-bottom: 18px;
        }

        /* Numbered paragraph — the number hangs in the left gutter. */
        .para {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            text-align: justify;
        }

        .para-no {
            flex: 0 0 26px;
        }

        /* Paragraph with no number, running the full text width. */
        .para-plain {
            margin-bottom: 14px;
            text-align: justify;
        }

        /* Continuation of a numbered paragraph — lines up with that paragraph's text,
           not with its number. */
        .para-continued {
            margin-bottom: 14px;
            padding-left: 34px;
            text-align: justify;
        }

        /* Sits one step further in than the paragraph text it belongs to. */
        .list {
            padding-left: 68px;
            margin-bottom: 14px;
        }

        .list-item {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            text-align: justify;
        }

        .list-marker {
            flex: 0 0 24px;
        }

        /* Per-cell borders, not border-collapse — collapsed borders drop out when printing. */
        .doc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 10px 0 14px;
            font-size: 11.5px;
        }

        .doc-table th,
        .doc-table td {
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .doc-table th:last-child,
        .doc-table td:last-child {
            border-right: 1px solid #000;
        }

        .doc-table tr:last-child > th,
        .doc-table tr:last-child > td {
            border-bottom: 1px solid #000;
        }

        .doc-table th {
            background: #d9d9d9;
            font-weight: 700;
            text-align: center;
        }

        .signature {
            margin-top: 30px;
            line-height: 1.7;
            break-inside: avoid;
        }

        .signature-line {
            width: 300px;
            border-bottom: 1px dotted #000;
            margin-bottom: 2px;
        }
    </style>

    @stack('styles')

    {{-- data-pagedjs-ignore: paged.js unwraps @media print and applies it on screen too. --}}
    <style data-pagedjs-ignore>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <script src="{{ asset('js/report-format-pdf/paged.polyfill.js') }}"></script>
</head>
<body>

    <div class="no-print">
        <button class="btn-cetak" onclick="window.print()">Cetak / Simpan PDF</button>
        {{-- Same URL with ?format=word — the Word file is converted from this very view. --}}
        <button class="btn-word" onclick="window.location.search = (window.location.search ? window.location.search + '&' : '?') + 'format=word'">Muat Turun Word</button>
        <button class="btn-tutup" onclick="window.close()">Tutup</button>
    </div>

    {{-- paged.js reads this tag instead of the whole <body>, keeping the buttons out of the output. --}}
    <template data-ref="pagedjs-content">
        @yield('content')
    </template>

</body>
</html>
