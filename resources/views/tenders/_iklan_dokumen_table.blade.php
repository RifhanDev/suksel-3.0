@php
    $iklanDokumenRows = $tender->relationLoaded('iklanDokumens')
        ? $tender->iklanDokumens
        : $tender->iklanDokumens()->get();
@endphp

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
        <thead style="background:#f8fafc;">
            <tr>
                <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Nama Dokumen</th>
                <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Fail</th>
                <th class="py-3 pe-4 text-center" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:140px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($iklanDokumenRows as $document)
                <tr style="border-color:#e5e7eb;">
                    <td class="ps-4">{{ $document->name }}</td>
                    <td>{{ $document->original_name ?: '-' }}</td>
                    <td class="pe-4 text-center">
                        <a href="{{ route('tenderIklanDokumen.download', [$tender->id, $document->id]) }}"
                            class="btn btn-sm btn-primary rounded-8 px-3">Muat Turun</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Tiada dokumen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
