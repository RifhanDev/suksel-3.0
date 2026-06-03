<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Laporan Jawatankuasa - {{ $tender->ref_number ?? '-' }} | {{ $tender->type === 'quotation' ? 'Sebut Harga' : 'Tender' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px 30px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2C3E9E;
            padding-bottom: 10px;
        }

        .report-header h2 {
            font-size: 16px;
            color: #2C3E9E;
            margin-bottom: 4px;
        }

        .report-header p {
            font-size: 11px;
            color: #555;
        }

        .tender-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .tender-info strong {
            color: #2C3E9E;
        }

        .section-title {
            background: #2C3E9E;
            color: #fff;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table thead th {
            background: #e8ebf7;
            color: #2C3E9E;
            padding: 6px 8px;
            text-align: center;
            font-size: 10px;
            border: 1px solid #bbb;
        }

        table tbody td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 10px;
            vertical-align: middle;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .catatan-section {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .catatan-section strong {
            color: #2C3E9E;
        }

        .empty-msg {
            text-align: center;
            padding: 10px;
            color: #999;
            font-style: italic;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            font-size: 9px;
            color: #888;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body {
                padding: 10px 20px;
            }

            .no-print {
                display: none !important;
            }

            .section-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table thead th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align:center; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding:8px 24px; font-size:13px; background:#2C3E9E; color:#fff; border:none; border-radius:4px; cursor:pointer;">
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding:8px 24px; font-size:13px; background:#888; color:#fff; border:none; border-radius:4px; cursor:pointer; margin-left:8px;">
            Tutup
        </button>
    </div>

    <div class="report-header">
        <h2>Laporan Pelantikan Jawatankuasa</h2>
        <p>Sistem Perolehan Selangor</p>
    </div>

    <div class="tender-info">
        <div style="padding-bottom: 6px; margin-bottom: 6px; border-bottom: 1px solid #ddd;">
            <strong>{{ $tender->type === 'quotation' ? 'Nama Sebut Harga' : 'Nama Tender' }}:</strong>
            {{ $tender->name ?? '-' }}
        </div>
        <div>
            <strong>{{ $tender->type === 'quotation' ? 'No. Sebut Harga' : 'No. Tender' }}:</strong> {{ $tender->ref_number ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>PTJ:</strong> {{ optional(optional($tender)->tenderer)->name ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Status:</strong> {{ $tender->status ?? '-' }}
        </div>
    </div>

    @php
        $tabLabels = [
            'spec' => 'Jawatankuasa Spesifikasi',
            'open' => 'Jawatankuasa Pembuka',
            'tech' => 'Jawatankuasa Penilaian Teknikal',
            'fin'  => 'Jawatankuasa Penilaian Kewangan',
        ];

        if (isset($tender) && $tender->tender_peringkat == 1) {
            unset($tabLabels['spec']);
        }

        $perananLabels = [
            '1' => 'Pengerusi',
            '2' => 'Setiausaha',
            '3' => 'Ahli',
        ];

        $ppLabels = [
            '1' => 'Ya',
            '0' => 'Tidak',
        ];
    @endphp

    @foreach ($tabLabels as $jenis => $label)
        <div class="section-title">{{ $label }}</div>

        @if (!empty($committeeDrafts[$jenis]['rows']) && count($committeeDrafts[$jenis]['rows']) > 0)
            <table>
                <thead>
                    <tr>
                        <th width="30">Bil.</th>
                        <th>No IC</th>
                        <th>Nama</th>
                        <th>Jawatan</th>
                        <th>Email</th>
                        <th width="50">Gred</th>
                        <th width="50">P&P</th>
                        <th width="80">Peranan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($committeeDrafts[$jenis]['rows'] as $index => $row)
                        <tr>
                            <td style="text-align:center;">{{ $index + 1 }}</td>
                            <td>{{ $row['ic_number'] ?? '-' }}</td>
                            <td>{{ $row['name'] ?? '-' }}</td>
                            <td>{{ $row['jawatan'] ?? '-' }}</td>
                            <td>{{ $row['email'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $row['gred'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $ppLabels[$row['p_p'] ?? ''] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $perananLabels[$row['peranan'] ?? ''] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (!empty($committeeDrafts[$jenis]['catatan']))
                <div class="catatan-section">
                    <strong>Catatan:</strong> {{ $committeeDrafts[$jenis]['catatan'] }}
                </div>
            @endif
        @else
            <div class="empty-msg">Tiada maklumat ahli jawatankuasa</div>
        @endif
    @endforeach

    <div class="footer">
        <span>&copy; {{ date('Y') }} Sistem Perolehan Selangor</span>
        <span>Tarikh Dijana: {{ date('d M Y H:i:s') }}</span>
    </div>

</body>
</html>
