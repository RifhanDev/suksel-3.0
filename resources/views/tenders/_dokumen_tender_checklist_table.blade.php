@php
    $dokumenList = $dokumenList ?? ($tenderDokumen->items() ?? []);
@endphp

<div class="table-responsive">
    <table class="table table-bordered mb-0" style="font-size:0.82rem;">
        <thead>
            <tr>
                <th style="width:52px;" class="text-center">No.</th>
                <th>Tender / Sebut Harga</th>
                <th style="width:200px;" class="text-center">Tindakan Oleh Petender</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumenList as $i => $dok)
                <tr>
                    <td class="text-center text-muted">{{ $i + 1 }}</td>
                    <td>{{ $dok['nama'] }}</td>
                    <td class="text-center">
                        <span class="badge-status {{ $dok['badge_class'] }}">
                            {{ $dok['tindakan'] }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                        Tiada dokumen tender direkodkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
