@extends('layouts.v3.master')

@section('content')
    <style>
        .nested-tabs { border-bottom: 1px solid #ddd; margin-bottom: 10px; }
        .nested-tab-btn {
            border: none; background: transparent; padding: 6px 20px;
            font-weight: 600; cursor: pointer; margin-right: 3px;
        }
        .nested-tab-btn.active {
            background: #c0392b; color: #fff; border-radius: 4px 4px 0 0;
        }
        .table-modern thead th {
            background-color: #f8fafc; color: #64748b; font-weight: 700;
            text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;
            padding: 14px 20px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 16px 20px; vertical-align: middle; color: #334155;
            font-size: 0.9rem; border-bottom: 1px solid #f1f5f9;
        }
    </style>

    @php
        $isQuotation = ($tender->type ?? '') === 'quotation';
    @endphp

    @if(empty($stosConfigured))
        <div class="alert alert-warning">STOS backend tidak dikonfigurasi. Simpan kehadiran tidak tersedia.</div>
    @endif

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        {{ $isQuotation ? 'Nama Sebut Harga' : 'Nama Tender' }}
                    </label>
                    <h6 class="text-primary mb-2">{{ $tender->name ?? '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        {{ $isQuotation ? 'No. Sebut Harga' : 'No. Tender' }}
                    </label>
                    <h6 class="text-primary">{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                    <h6 class="text-primary">{{ optional($tender->tenderer)->name ?? '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase">
                        {{ $tender->status ?? 'Dalam Proses' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(empty($uiTabs))
        <div class="alert alert-info">Tiada jawatankuasa yang layak. Sila lengkapkan pelantikan jawatankuasa terlebih dahulu.</div>
    @else
        <div class="nested-tabs">
            @foreach($uiTabs as $tab)
                <button type="button" class="nested-tab-btn {{ ($tab['jenis'] ?? '') === ($activeJenis ?? '') ? 'active' : '' }}"
                    data-tab="{{ $tab['ui'] }}" data-jenis="{{ $tab['jenis'] }}">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <div class="nested-content">
            @foreach($uiTabs as $tab)
                @include('newModule.penyediaanMesyuarat._kehadiran_tab_panel', [
                    'tab' => $tab,
                    'isFirst' => ($tab['jenis'] ?? '') === ($activeJenis ?? ''),
                    'meetingsByJenis' => $meetingsByJenis,
                    'membersByJenis' => $membersByJenis,
                    'selectedMeeting' => $selectedMeeting,
                    'untukKelulusan' => $untukKelulusan,
                    'tender' => $tender,
                ])
            @endforeach
        </div>
    @endif

    <script type="application/json" id="kehadiran-page-config">{!! json_encode([
        'tenderUuid' => $tender->uuid ?? '',
        'saveUrl' => route('kehadiranMesyuarat.simpan'),
        'pageUrl' => route('jawatankuasaPage'),
        'csrfToken' => csrf_token(),
        'stosConfigured' => $stosConfigured ?? false,
    ]) !!}</script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const config = JSON.parse(document.getElementById('kehadiran-page-config').textContent);
            const { tenderUuid, saveUrl, pageUrl, csrfToken, stosConfigured } = config;

            document.querySelectorAll('.nested-tabs').forEach(function (wrapper) {
                wrapper.addEventListener('click', function (e) {
                    const btn = e.target.closest('.nested-tab-btn');
                    if (!btn) return;

                    const tab = btn.dataset.tab;
                    const contentWrapper = wrapper.nextElementSibling;

                    wrapper.querySelectorAll('.nested-tab-btn').forEach(function (b) {
                        b.classList.remove('active');
                    });
                    btn.classList.add('active');

                    contentWrapper.querySelectorAll('.kehadiran-tab').forEach(function (div) {
                        div.classList.toggle('d-none', div.dataset.tab !== tab);
                    });
                });
            });

            document.querySelectorAll('.meeting-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    const meetingId = this.value;
                    if (!meetingId) return;
                    const url = new URL(pageUrl, window.location.origin);
                    url.searchParams.set('tender', tenderUuid);
                    url.searchParams.set('meeting_id', meetingId);
                    window.location.href = url.toString();
                });
            });

            document.querySelectorAll('.btn-simpan-kehadiran').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (!stosConfigured) {
                        alert('STOS backend tidak dikonfigurasi.');
                        return;
                    }

                    const uiTab = this.dataset.uiTab;
                    const jenis = this.dataset.jenis;
                    const tabEl = document.querySelector('.kehadiran-tab[data-tab="' + uiTab + '"]');
                    const meetingId = tabEl.querySelector('.meeting-select').value;
                    const untukKelulusan = tabEl.querySelector('.untuk-kelulusan-checkbox').checked;

                    if (!meetingId) {
                        alert('Sila pilih tarikh mesyuarat.');
                        return;
                    }

                    const attendance = [];
                    tabEl.querySelectorAll('.kehadiran-member-body tr[data-jawatankuasa-id]').forEach(function (row) {
                        attendance.push({
                            jawatankuasa_id: parseInt(row.dataset.jawatankuasaId, 10),
                            hadir: row.querySelector('.hadir-checkbox').checked ? 1 : 0,
                        });
                    });

                    if (!attendance.length) {
                        alert('Tiada ahli jawatankuasa untuk disimpan.');
                        return;
                    }

                    $.ajax({
                        url: saveUrl,
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            tender: tenderUuid,
                            jenis_jawatankuasa: jenis,
                            penyediaan_mesyuarat_id: meetingId,
                            untuk_kelulusan: untukKelulusan ? 1 : 0,
                            attendance: attendance,
                        },
                        success: function (res) {
                            alert(res.message || 'Berjaya.');
                            window.location.reload();
                        },
                        error: function (xhr) {
                            alert((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan kehadiran.');
                        },
                    });
                });
            });
        });
    </script>
@endsection
