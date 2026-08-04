@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <style>
        .review-section-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #475569;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        .review-file-list {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
        }
        .review-file-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #334155;
            margin: 4px;
            text-decoration: none;
        }
        .review-file-chip:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .review-file-chip i {
            color: var(--sg-red, #c41e3a);
        }
    </style>
@endsection

@section('content')
    @php $total = collect($items)->sum(fn ($item) => (float) ($item['nilai_kerja'] ?? 0)); @endphp

    <h6 class="review-section-label">Kerja Dalam Tangan</h6>

    <div class="table-responsive mb-4">
        <table id="tbl-kerja-dalam-tangan-review" class="table table-bordered table-slate align-middle mb-0 w-100">
            <thead class="text-center">
                <tr>
                    <th style="width: 50px;">Bil.</th>
                    <th>Senarai Kerja Dalam Tangan</th>
                    <th>PIC</th>
                    <th>Nombor Telefon PIC</th>
                    <th class="text-end">Nilai Kerja (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $item['tajuk'] ?? '-' }}</td>
                        <td>{{ $item['pic'] ?? '-' }}</td>
                        <td>{{ $item['telefon_pic'] ?? '-' }}</td>
                        <td class="text-end">{{ number_format((float) ($item['nilai_kerja'] ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tiada rekod kerja dalam tangan.</td>
                    </tr>
                @endforelse
            </tbody>
            @if (count($items))
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Jumlah</th>
                        <th class="text-end">{{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <h6 class="review-section-label">Dokumen Sokongan</h6>

    <div class="review-file-list">
        @forelse ($dokumens as $doc)
            <a href="{{ $doc['url'] }}" target="_blank" rel="noopener noreferrer" class="review-file-chip">
                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                {{ $doc['original_name'] ?? 'Dokumen' }}
            </a>
        @empty
            <p class="text-muted small mb-0">Tiada dokumen sokongan dimuat naik.</p>
        @endforelse
    </div>
@endsection
