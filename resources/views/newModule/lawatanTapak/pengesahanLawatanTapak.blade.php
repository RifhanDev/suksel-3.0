@extends('layouts.v3.master')

@section('content')
<style>
    .table-modern thead th {
        background-color: #f8fafc; color: #64748b; font-weight: 700;
        text-transform: uppercase; font-size: 0.7rem; padding: 14px 12px;
        border-bottom: 2px solid #e2e8f0; white-space: nowrap;
    }
    .table-modern tbody td {
        padding: 12px; vertical-align: middle; font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="mb-3">
    <a href="{{ route('lawatanTapakUrusetia') }}" class="btn btn-sm btn-light border">&larr; Kembali ke Senarai</a>
</div>

<div class="card border shadow-sm mb-3 rounded-3">
    <div class="card-body p-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                <h6 class="text-primary mb-0">{{ $tender->ref_number ?: $tender->no_tender ?: $tender->id }}</h6>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk</label>
                <h6 class="mb-0">{{ $tender->name }}</h6>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ / Agensi</label>
                <h6 class="mb-0">{{ $tender->tenderer?->name ?? '-' }}</h6>
            </div>
        </div>
    </div>
</div>

<div class="card border shadow-sm mb-4 rounded-3">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Pengesahan Kehadiran Lawatan Tapak</h5>
        <p class="text-muted small mb-0">Senarai syarikat yang mendaftar wakil, hadir lawatan tapak, atau telah membeli dokumen.</p>
    </div>
    <div class="card-body p-0">
        <form method="post" action="{{ route('pengesahanLawatanTapak.update', $tender->id) }}" id="formPengesahanLawatan">
            @csrf
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Bil</th>
                            <th>ROC / No. Syarikat</th>
                            <th>Nama Syarikat</th>
                            <th>Lawatan / Lokasi</th>
                            <th>Tarikh Lawatan</th>
                            <th>No. IC</th>
                            <th>Nama Wakil</th>
                            <th class="text-center">Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $bil = 0; @endphp
                        @forelse ($attendanceRows as $idx => $row)
                            @php $bil++; @endphp
                            <tr>
                                <td class="text-center">{{ $bil }}</td>
                                <td>{{ $row['vendor']->registration ?? '-' }}</td>
                                <td>{{ $row['vendor']->name ?? '-' }}</td>
                                <td>
                                    <small>{!! nl2br(e($row['visit']->meetpoint)) !!}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($row['visit']->datetime)->format('j M Y H:i') }}</td>
                                <td>
                                    <input type="hidden" name="rows[{{ $idx }}][visit_id]" value="{{ $row['visit']->id }}">
                                    <input type="hidden" name="rows[{{ $idx }}][vendor_id]" value="{{ $row['vendor']->id }}">
                                    @if ($row['rep'])
                                        <input type="hidden" name="rows[{{ $idx }}][rep_id]" value="{{ $row['rep']->id }}">
                                    @endif
                                    <input type="text" name="rows[{{ $idx }}][ic_no]" class="form-control form-control-sm"
                                        value="{{ $row['rep']?->ic_no }}" placeholder="No. IC" maxlength="32">
                                </td>
                                <td>
                                    <input type="text" name="rows[{{ $idx }}][name]" class="form-control form-control-sm"
                                        value="{{ $row['rep']?->name }}" placeholder="Nama wakil">
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="rows[{{ $idx }}][attended]" value="0">
                                    <input type="checkbox" name="rows[{{ $idx }}][attended]" value="1" class="form-check-input"
                                        @checked($row['rep']?->attended || $row['attended'])>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Tiada rekod syarikat untuk lawatan tapak tender ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($attendanceRows) > 0)
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">Tandakan <strong>Hadir</strong> untuk sahkan kehadiran. Simpan akan kemaskini rekod sistem.</small>
                    <div class="d-flex gap-2">
                        <a href="{{ route('kelulusanLawatanTapak', $tender->id) }}" class="btn btn-outline-secondary btn-sm">Ringkasan Kelulusan</a>
                        <button type="submit" class="btn btn-selangor">Simpan</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border shadow-sm mb-4 rounded-3">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Tambah Syarikat / Wakil</h6>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('pengesahanLawatanTapak.update', $tender->id) }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-3">
                <label class="form-label small">Lawatan</label>
                <select name="rows[999][visit_id]" class="form-select form-select-sm" required>
                    @foreach ($visits as $v)
                        <option value="{{ $v->id }}">{{ \Carbon\Carbon::parse($v->datetime)->format('j M Y H:i') }} — {{ Str::limit($v->meetpoint, 40) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">No. ROC / Syarikat</label>
                <input type="text" name="rows[999][vendor_registration]" class="form-control form-control-sm" placeholder="Contoh: 123456-A" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">No. IC</label>
                <input type="text" name="rows[999][ic_no]" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Nama</label>
                <input type="text" name="rows[999][name]" class="form-control form-control-sm">
            </div>
            <div class="col-md-1">
                <label class="form-label small">Hadir</label>
                <input type="hidden" name="rows[999][attended]" value="0">
                <input type="checkbox" name="rows[999][attended]" value="1" class="form-check-input mt-2" checked>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">Tambah</button>
            </div>
        </form>
        <p class="text-muted small mt-2 mb-0">Gunakan borang ini untuk syarikat yang hadir tetapi belum mendaftar wakil melalui portal vendor.</p>
    </div>
</div>

@endsection
