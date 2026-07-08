@extends('layouts.v3.master')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Iklan</h3>
            <p class="text-muted small m-0">Senarai tender dengan status proses 4 (selesai spesifikasi kewangan), sedia untuk penyediaan iklan.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded overflow-hidden mb-4 shadow-sm border">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>No. Tender</th>
                        <th>Tajuk Perolehan</th>
                        <th>PTJ</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenders as $row)
                        <tr>
                            <td class="fw-semibold text-danger">{{ $row['no_tender'] }}</td>
                            <td>{{ $row['tajuk'] }}</td>
                            <td class="small text-muted">{{ $row['ptj'] }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $row['status_label'] }}</span></td>
                            <td class="text-end">
                                <a href="{{ $row['show_url'] }}" class="btn btn-sm btn-selangor">Sedia Iklan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Tiada tender dalam peringkat Penyediaan Iklan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
