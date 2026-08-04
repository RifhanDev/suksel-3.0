@extends('layouts.v3.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Demo: Penyerahan Petender</h1>
            <p class="text-muted mb-0 small">
                Pilih tender untuk lihat contoh cara memuatkan semua dokumen / jawapan vendor selepas beli tender.
            </p>
        </div>
        <span class="badge bg-warning text-dark">Dummy / Rujukan</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>No. Tender</th>
                            <th>Nama</th>
                            <th class="text-center">Pembeli</th>
                            <th class="text-end pe-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenders as $tender)
                            <tr>
                                <td class="ps-4 text-muted">{{ $tender->id }}</td>
                                <td class="fw-semibold">{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($tender->name ?? '-', 60) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border">{{ $tender->buyer_count }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('demo.vendorSubmissions.show', $tender) }}" class="btn btn-sm btn-primary">
                                        Lihat Penyerahan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Tiada tender dijumpai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
