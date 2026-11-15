<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $title }}</title>

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
        }

        p, div {
            margin: 0;
        }

        @page WordSection1 {
            size: 210mm 297mm;
            margin: 26mm 18mm 20mm 32mm;
            mso-header: url("#") h1;
            mso-footer: url("#") f1;
            mso-header-margin: 12mm;
            mso-footer-margin: 10mm;
        }

        div.WordSection1 {
            page: WordSection1;
        }

        p.MsoHeader, p.MsoFooter {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        /* Word only reads header/footer definitions from here; parking the table off-page keeps them out of the body. */
        table#hdrftr {
            margin: 0 0 0 900in;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }

        .doc-title-block {
            text-align: center;
            border-bottom: 2.25pt solid #000;
            padding-bottom: 10pt;
            margin-bottom: 16pt;
        }

        .doc-title {
            font-weight: 700;
            font-size: 12pt;
            text-transform: uppercase;
            line-height: 1.5;
        }

        .doc-no-tender {
            font-weight: 700;
            font-size: 11pt;
            text-transform: uppercase;
            margin-top: 12pt;
        }

        .laporan-heading {
            font-weight: 700;
            font-size: 11pt;
            text-transform: uppercase;
            margin: 18pt 0 8pt;
            page-break-after: avoid;
        }

        .laporan-subheading {
            margin: 12pt 0 6pt 15pt;
            page-break-after: avoid;
        }

        .jpt-member-name, .pengesahan-name {
            font-weight: 700;
        }

        .pengesahan-name {
            text-transform: uppercase;
        }

        .signature-line {
            margin-bottom: 4pt;
        }

        .pengesahan-block {
            margin-top: 24pt;
        }

        table.laporan-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6pt 0 4pt;
            font-size: 10pt;
        }

        table.laporan-table th, table.laporan-table td {
            border: 1px solid #333;
            padding: 4pt 6pt;
            vertical-align: top;
        }

        table.laporan-table th {
            background: #d9d9d9;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            font-size: 9.5pt;
        }

        table.laporan-table tr {
            page-break-inside: avoid;
        }

        td.col-bil {
            text-align: center;
        }

        td.col-harga {
            text-align: right;
        }

        td.col-kod {
            text-align: center;
            font-weight: 700;
        }

        td.col-rule {
            text-align: center;
            font-weight: 700;
            font-style: italic;
            background: #f9fafb;
        }

        .laporan-table-caption {
            text-align: center;
            font-weight: 700;
            font-size: 10pt;
            margin-bottom: 14pt;
        }

        .keep-with-next {
            page-break-after: avoid;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .no-print {
            display: none;
        }
    </style>
</head>
<body>

<div class="WordSection1">
    {!! $body !!}

    <table id="hdrftr" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div style="mso-element:header" id="h1">
                    <table style="width:100%;border-collapse:collapse">
                        <tr>
                            <td style="font-family:Arial,sans-serif;font-size:9pt;font-weight:700;border-bottom:solid windowtext 2.25pt;padding:0 0 4pt 0" valign="bottom">{!! $headerLeft !!}</td>
                            <td style="font-family:Arial,sans-serif;font-size:9pt;font-weight:700;border-bottom:solid windowtext 2.25pt;padding:0 0 4pt 0;text-align:right" valign="bottom">{!! $headerRight !!}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div style="mso-element:footer" id="f1">
                    <table style="width:100%;border-collapse:collapse">
                        <tr>
                            <td style="width:33%;font-family:Arial,sans-serif;font-size:9pt"></td>
                            <td style="width:34%;font-family:Arial,sans-serif;font-size:9pt;text-align:center"><span style="mso-field-code:PAGE"></span></td>
                            <td style="width:33%;font-family:Arial,sans-serif;font-size:9pt;font-weight:700;text-align:right">{!! $footerRight !!}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
