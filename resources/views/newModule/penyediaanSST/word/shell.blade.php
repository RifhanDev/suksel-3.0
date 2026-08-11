<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $title }}</title>

    {{-- Opens in print layout rather than web layout. --}}
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
        </w:WordDocument>
    </xml>
    <![endif]-->

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
        }

        /* Word applies page setup, headers and footers only through a named section. */
        @page WordSection1 {
            size: 210mm 297mm;
            margin: 28mm 20mm 32mm 26mm;
            mso-header: url("#") h1;
            mso-footer: url("#") f1;
            mso-header-margin: 14mm;
            mso-footer-margin: 14mm;
        }

        div.WordSection1 {
            page: WordSection1;
        }

        p.MsoHeader, p.MsoFooter {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        .hdr {
            width: 100%;
            border-collapse: collapse;
        }

        .hdr td {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            padding: 0;
            vertical-align: top;
        }

        .hdr .right {
            text-align: right;
            font-weight: 700;
        }

        /* Explicit breaks placed in the letter. */
        .page-break {
            page-break-before: always;
        }

        /* Fill-in rules. */
        .blank, .line, .line-fill, .decl-line, .witness-rule, .signature-line, .commissioner-line {
            border-bottom: 1px dotted #000;
        }

        .blank {
            display: inline-block;
            min-width: 120px;
        }

        .line {
            display: block;
            height: 13px;
        }

        .decl-line {
            border-bottom: 1px solid #000;
            display: block;
            height: 13px;
        }

        .signature-line { width: 300px; height: 30px; }
        .commissioner-line { width: 260px; margin: 34px auto 6px; }
        .witness-rule { margin-bottom: 6px; }

        /* Headings and standalone paragraphs. */
        .doc-title { text-align: center; font-weight: 700; margin-bottom: 14px; }
        .doc-subtitle { text-align: center; font-weight: 700; text-decoration: underline; margin-bottom: 20px; }
        .lampiran-label { text-align: right; font-weight: 700; font-style: italic; margin-bottom: 12px; }
        .section-heading { font-weight: 700; text-decoration: underline; margin: 16px 0 8px; }
        .salutation { margin-bottom: 12px; }
        .recipient { margin-bottom: 24px; }
        .recipient-role { font-weight: 700; }
        .para-plain { margin-bottom: 10px; text-align: justify; }
        .para-continued { margin-bottom: 10px; margin-left: 34px; text-align: justify; }
        .note-small { font-size: 8.5pt; }
        .notes-title { margin-bottom: 6px; }
        .formula { text-align: center; margin: 12px 0; }
        .formula-line { font-weight: 700; text-decoration: underline; }
        .copy-block { margin-bottom: 14px; }
        .copy-postcode { font-weight: 700; }
        .signature { margin-top: 26px; }
        .commissioner { text-align: center; margin-top: 28px; }
        .ref-block { width: 46%; margin-left: auto; margin-bottom: 22px; }

        /* Word has no flexbox — every side-by-side row becomes a CSS table. */
        .para, .field-row, .list-item, .sub-item, .clause-item, .notes-item,
        .detail-row, .decl-row, .value-with-prefix, .oath, .witness-grid, .oath-field {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .para > span, .field-row > span, .list-item > span, .sub-item > span,
        .clause-item > span, .notes-item > span, .detail-row > div, .decl-row > span,
        .value-with-prefix > span, .oath > div, .witness-grid > div, .oath-field > span {
            display: table-cell;
            vertical-align: top;
        }

        .para > span:last-child, .list-item > span:last-child,
        .sub-item > span:last-child, .clause-item > span:last-child {
            text-align: justify;
        }

        .para-no { width: 30px; }
        .list-marker { width: 34px; }
        .sub-marker { width: 40px; }
        .clause-marker { width: 32px; }
        .notes-marker { width: 34px; }
        .field-label { width: 100px; }
        .subject-label { width: 200px; font-weight: 700; }
        .field-colon, .detail-colon, .decl-colon { width: 14px; }
        .detail-label { width: 32%; }
        .decl-label { width: 110px; }
        .oath-left { width: 52%; }
        .witness-col { width: 50%; }

        /* Indentation for the lettered lists. */
        .list { margin-left: 68px; margin-bottom: 10px; }
        .sub-list, .clause-list { margin-left: 34px; margin-bottom: 10px; }
        .notes { margin-top: 22px; }

        /* Bordered tables keep real borders in Word. */
        .doc-table { width: 100%; border-collapse: collapse; margin: 8px 0 12px; }
        .doc-table th, .doc-table td { border: 1px solid #000; padding: 5px 7px; vertical-align: top; }
        .doc-table th { background: #d9d9d9; font-weight: 700; text-align: center; }

        /* Screen-only chrome must never reach the document. */
        .no-print { display: none; }
    </style>
</head>
<body>

{{-- Repeats on every page. --}}
<div style="mso-element:header" id="h1">
    <p class="MsoHeader">
        <table class="hdr">
            <tr>
                <td>{!! $headerLeft !!}</td>
                <td class="right">{!! $headerRight !!}</td>
            </tr>
        </table>
    </p>
</div>

<div style="mso-element:footer" id="f1">
    <p class="MsoFooter">
        <table class="hdr">
            <tr>
                <td>{!! $footerLeft !!}</td>
                <td class="right">
                    <span style="mso-field-code:PAGE"></span> daripada
                    <span style="mso-field-code:NUMPAGES"></span>
                </td>
            </tr>
        </table>
    </p>
</div>

<div class="WordSection1">
    {!! $body !!}
</div>

</body>
</html>
