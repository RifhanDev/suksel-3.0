@php
    $rows = $content['rows'] ?? [];
    $hasKerjaCols = collect($rows)->contains(fn ($row) => isset($row['ya_tidak']) || isset($row['catatan']));
@endphp

@if (! empty($content['document_title']))
    <div class="fw-semibold mb-2" style="font-size:0.84rem;">{{ $content['document_title'] }}</div>
@endif

<div class="table-responsive">
    <table class="table table-sm table-bordered mb-0" style="font-size:0.78rem;">
        <thead class="table-light">
            <tr>
                <th style="width:48px;" class="text-center">Bil</th>
                <th>Spesifikasi / Perkara</th>
                @unless ($hasKerjaCols)
                    <th style="width:90px;" class="text-center">Kuantiti</th>
                    <th style="width:80px;" class="text-center">Unit</th>
                @endunless
                @if ($hasKerjaCols)
                    <th style="width:90px;" class="text-center">Ya/Tidak</th>
                    <th>Catatan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td class="text-center text-muted">{{ $row['bil'] ?? '-' }}</td>
                    <td>{{ $row['title'] ?? '-' }}</td>
                    @unless ($hasKerjaCols)
                        <td class="text-center">{{ $row['quantity'] ?? '—' }}</td>
                        <td class="text-center">{{ $row['unit'] ?? '—' }}</td>
                    @endunless
                    @if ($hasKerjaCols)
                        <td class="text-center">{{ $row['ya_tidak'] ?? '—' }}</td>
                        <td>{{ $row['catatan'] ?? '—' }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $hasKerjaCols ? 4 : 4 }}" class="text-center text-muted py-3">
                        Tiada data spesifikasi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
