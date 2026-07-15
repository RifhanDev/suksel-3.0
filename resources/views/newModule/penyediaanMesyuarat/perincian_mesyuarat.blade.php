@extends('layouts.v3.master')

@section('content')
    <style>
        .stats-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }
        .stats-card::before {
            content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
            background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
        }
        .stats-card-header {
            padding: 20px 16px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .stats-card-title {
            margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
        }
        .table-modern thead th, .table-modern tfoot th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-modern tbody tr:hover {
            background-color: #fff9f9;
        }
        .heartbeat {
            display: inline-block;
            animation: heartbeat 1.2s infinite;
        }
        @keyframes heartbeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.05); }
            40% { transform: scale(1); }
            60% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .nested-tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .nested-tab-btn {
            border: none;
            background: transparent;
            padding: 6px 20px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 3px;
        }
        .nested-tab-btn.active {
            background: #c0392b;
            color: #fff;
            border-radius: 4px 4px 0 0;
        }
        #mesyuarat-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            z-index: 20000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #mesyuarat-loading-overlay.show {
            display: flex;
        }
        .mesyuarat-loading-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 34px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            min-width: 260px;
        }
        .mesyuarat-loading-spinner {
            width: 46px;
            height: 46px;
            border: 4px solid #fee2e2;
            border-top-color: #c0392b;
            border-radius: 50%;
            animation: mesyuarat-spin 0.8s linear infinite;
        }
        @keyframes mesyuarat-spin {
            to { transform: rotate(360deg); }
        }
        .mesyuarat-loading-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1rem;
        }
        .mesyuarat-loading-sub {
            color: #64748b;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>

    @php
        $isQuotation = ($tender->type ?? '') === 'quotation';
    @endphp

    @if(empty($stosConfigured))
        <div class="alert alert-warning">STOS backend tidak dikonfigurasi. Simpan dan Hantar tidak tersedia.</div>
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
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Peringkat</label>
                    <h6 class="text-primary">{{ (int) ($tender->tender_peringkat ?? 2) === 1 ? '1 Peringkat' : '2 Peringkat' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                    <h6 class="text-primary">{{ optional($tender->tenderer)->name ?? '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase heartbeat" style="font-size: 0.8rem;">
                        {{ $tender->status ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="nested-tabs">
            @foreach($uiTabs as $tab)
                <button type="button" class="nested-tab-btn {{ $loop->first ? 'active' : '' }}" data-tab="{{ $tab['ui'] }}" data-jenis="{{ $tab['jenis'] }}">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <div class="nested-content">
            @foreach($uiTabs as $tab)
                @include('newModule.penyediaanMesyuarat._tab_panel', [
                    'tab' => $tab,
                    'isFirst' => $loop->first,
                    'membersByJenis' => $membersByJenis,
                    'meetingStatusByJenis' => $meetingStatusByJenis,
                ])
            @endforeach
        </div>

    <datalist id="tempat-mesyuarat-options">
        @foreach($tempatMesyuarat ?? [] as $tempat)
            <option value="{{ $tempat }}"></option>
        @endforeach
    </datalist>

    <div id="mesyuarat-loading-overlay" role="alertdialog" aria-live="assertive" aria-busy="true">
        <div class="mesyuarat-loading-card">
            <div class="mesyuarat-loading-spinner"></div>
            <div class="mesyuarat-loading-title" id="mesyuarat-loading-title">Menghantar jemputan mesyuarat...</div>
            <div class="mesyuarat-loading-sub">Sila tunggu, sistem sedang menjana memo dan menghantar emel kepada ahli jawatankuasa.</div>
        </div>
    </div>

    <script type="application/json" id="mesyuarat-page-config">{!! json_encode([
        'tenderUuid' => $tender->uuid ?? '',
        'jenisByUiTab' => $jenisByUiTab ?? [],
        'savedMeetings' => $meetingsForJs ?? [],
        'tempatMesyuarat' => $tempatMesyuarat ?? [],
        'todayDate' => now()->format('Y-m-d'),
        'saveUrl' => route('penyediaanMesyuarat.simpan'),
        'hantarUrl' => route('penyediaanMesyuarat.hantar'),
        'csrfToken' => csrf_token(),
        'stosConfigured' => $stosConfigured ?? false,
    ]) !!}</script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const config = JSON.parse(document.getElementById('mesyuarat-page-config').textContent);
            const {
                tenderUuid,
                jenisByUiTab,
                savedMeetings,
                tempatMesyuarat,
                todayDate,
                saveUrl,
                hantarUrl,
                csrfToken,
                stosConfigured,
            } = config;

            function buildRow(bil) {
                return $('<tr class="mesyuarat-row">' +
                    '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                    '<td><input type="date" class="form-control form-control-sm" min="' + todayDate + '"></td>' +
                    '<td><input type="time" class="form-control form-control-sm"></td>' +
                    '<td><input type="text" class="form-control form-control-sm" list="tempat-mesyuarat-options" placeholder="Tempat mesyuarat..."></td>' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
                            'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang baris">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path></svg>' +
                        '</button>' +
                    '</td>' +
                '</tr>');
            }

            function seedMeetingRows(uiTab) {
                const $body = $('#tbl-mesyuarat-' + uiTab + '-body');
                const saved = savedMeetings[uiTab] || [];
                $body.empty();
                if (!saved.length) {
                    $body.append(buildRow(1));
                    return;
                }
                saved.forEach(function (row, index) {
                    const $row = buildRow(index + 1);
                    $row.find('input[type="date"]').val(row.tarikh_mesyuarat || '');
                    $row.find('input[type="time"]').val(row.masa || '');
                    $row.find('input[type="text"]').val(row.tempat || '');
                    $body.append($row);
                });
            }

            function renumberRows($body) {
                $body.find('.row-bil').each(function (index) {
                    $(this).text(index + 1);
                });
            }

            function collectRows(uiTab) {
                const rows = [];
                let hasPastDate = false;
                $('#tbl-mesyuarat-' + uiTab + '-body tr').each(function () {
                    const tarikh = $(this).find('input[type="date"]').val();
                    const masa = $(this).find('input[type="time"]').val();
                    const tempat = $(this).find('input[type="text"]').val();
                    if (tarikh && tarikh < todayDate) {
                        hasPastDate = true;
                    }
                    if (tarikh && masa && tempat) {
                        rows.push({ tarikh_mesyuarat: tarikh, masa: masa, tempat: tempat });
                    }
                });
                if (hasPastDate) {
                    alert('Tarikh mesyuarat mestilah hari ini atau selepasnya.');
                    return null;
                }
                return rows;
            }

            const $loadingOverlay = $('#mesyuarat-loading-overlay');
            const $loadingTitle = $('#mesyuarat-loading-title');

            function showLoading(action) {
                $loadingTitle.text(action === 'hantar' ? 'Menghantar jemputan mesyuarat...' : 'Menyimpan perincian mesyuarat...');
                $loadingOverlay.addClass('show');
            }

            function hideLoading() {
                $loadingOverlay.removeClass('show');
            }

            function postMeeting(action, uiTab) {
                if (!stosConfigured) {
                    alert('STOS backend tidak dikonfigurasi.');
                    return;
                }
                const rows = collectRows(uiTab);
                if (rows === null) {
                    return;
                }
                if (!rows.length) {
                    alert('Sila lengkapkan sekurang-kurangnya satu perincian mesyuarat.');
                    return;
                }
                if (action === 'hantar' && !confirm('Hantar jemputan mesyuarat kepada ahli jawatankuasa tab ini sahaja?')) {
                    return;
                }
                showLoading(action);
                $.ajax({
                    url: action === 'hantar' ? hantarUrl : saveUrl,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        tender: tenderUuid,
                        jenis_jawatankuasa: jenisByUiTab[uiTab],
                        rows: rows,
                    },
                    success: function (res) {
                        alert(res.message || 'Berjaya.');
                        window.location.reload();
                    },
                    error: function (xhr) {
                        hideLoading();
                        alert((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memproses permintaan.');
                    },
                });
            }

            Object.keys(jenisByUiTab).forEach(function (uiTab) {
                const $body = $('#tbl-mesyuarat-' + uiTab + '-body');
                seedMeetingRows(uiTab);

                $('#btn-tambah-row-mesyuarat-' + uiTab).on('click', function () {
                    const nextBil = $body.find('.mesyuarat-row').length + 1;
                    $body.append(buildRow(nextBil));
                });

                $body.on('click', '.btn-hapus-row', function () {
                    $(this).closest('.mesyuarat-row').remove();
                    if (!$body.find('.mesyuarat-row').length) {
                        $body.append(buildRow(1));
                    }
                    renumberRows($body);
                });
            });

            $(document).on('click', '.btn-simpan-mesyuarat', function () {
                postMeeting('simpan', $(this).data('ui-tab'));
            });

            $(document).on('click', '.btn-hantar-mesyuarat', function () {
                postMeeting('hantar', $(this).data('ui-tab'));
            });

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

                    contentWrapper.querySelectorAll('.tab-content').forEach(function (div) {
                        div.classList.toggle('d-none', div.dataset.tab !== tab);
                    });
                });
            });
        });
    </script>
@endsection
