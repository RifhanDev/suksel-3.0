@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/bq-upload-tokens.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/bq-upload.css') }}" rel="stylesheet">
@endsection

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

    <div class="modal fade" id="modal-kemaskini-ahli" tabindex="-1" aria-labelledby="modal-kemaskini-ahli-title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="modal-kemaskini-ahli-title">Kemaskini Ahli Jawatankuasa</h5>
                        <div class="small text-muted" id="kemaskini-ahli-jenis-label"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="cari-pegawai-jawatankuasa" class="form-label fw-semibold small">Tambah pegawai</label>
                    <input type="search" id="cari-pegawai-jawatankuasa" class="form-control form-control-sm" autocomplete="off" placeholder="Cari nama, nombor kad pengenalan atau e-mel">
                    <div id="hasil-carian-pegawai-jawatankuasa" class="list-group mt-1 d-none"></div>
                    <div class="form-text mb-3">Pegawai yang ditambah, dibuang atau ditukar peranannya akan menerima e-mel pemakluman.</div>
                    <div id="senarai-kemaskini-ahli" class="d-flex flex-column gap-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-simpan-kemaskini-ahli" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
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
        'membersByJenis' => collect($uiTabs ?? [])->mapWithKeys(function ($tab) use ($membersByJenis) {
            return [$tab['jenis'] => collect($membersByJenis[$tab['jenis']] ?? [])->map(function ($member) {
                return [
                    'user_id' => (int) $member->user_id,
                    'ic_number' => $member->user->ic_number ?? '',
                    'name' => $member->user->name ?? '-',
                    'email' => $member->user->email ?? '',
                    'jawatan' => $member->user->jawatan ?? '-',
                    'gred' => $member->user->gred ?? '-',
                    'p_p' => (string) $member->p_p,
                    'peranan' => (string) $member->peranan,
                ];
            })->values()->all()];
        })->all(),
        'updateCommitteeUrl' => route('penyediaanMesyuarat.kemaskiniAhli'),
        'searchUsersUrl' => route('jawatankuasa.searchUsers'),
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
                membersByJenis,
                updateCommitteeUrl,
                searchUsersUrl,
            } = config;

            const meetingDialog = window.Swal && typeof window.Swal.mixin === 'function'
                ? window.Swal.mixin({
                    buttonsStyling: false,
                    heightAuto: false,
                    scrollbarPadding: false,
                    showClass: { popup: 'bq-dialog-fade-in', backdrop: 'bq-backdrop-fade-in' },
                    hideClass: { popup: 'bq-dialog-fade-out', backdrop: 'bq-backdrop-fade-out' },
                    customClass: {
                        container: 'bq-dialog-container',
                        popup: 'bq-dialog',
                        confirmButton: 'bq-dialog-button',
                        cancelButton: 'bq-dialog-button bq-dialog-cancel'
                    }
                })
                : null;

            function fireMeetingDialog(options) {
                if (meetingDialog) {
                    return meetingDialog.fire(options);
                }

                if (typeof window.showConfirmModal === 'function') {
                    return window.showConfirmModal({
                        title: options.title,
                        message: options.text || '',
                        icon: options.icon || 'warning',
                        confirmText: options.confirmButtonText || 'OK',
                        cancelText: options.cancelButtonText || 'Batal',
                        showCancel: options.showCancelButton !== false
                    }).then(function (isConfirmed) {
                        return { isConfirmed: isConfirmed };
                    });
                }

                return Promise.resolve({ isConfirmed: false });
            }

            function showMeetingNotice(message, icon) {
                return fireMeetingDialog({
                    title: icon === 'success' ? 'Berjaya' : (icon === 'error' ? 'Ralat' : 'Perhatian'),
                    text: message,
                    icon: icon || 'warning',
                    confirmButtonText: 'OK',
                    showCancelButton: false
                });
            }

            let committeeSearchResults = [];
            let committeeSearchTimer = null;
            let editingCommitteeJenis = null;

            function buildCommitteeMemberRow(member) {
                const $row = $('<div class="committee-edit-row border rounded-2 p-3 d-flex flex-column flex-md-row align-items-md-center gap-3"></div>')
                    .attr('data-user-id', member.user_id);
                const $identity = $('<div class="flex-grow-1"></div>');
                $identity.append($('<div class="fw-semibold"></div>').text(member.name || '-'));
                $identity.append($('<div class="small text-muted"></div>').text((member.ic_number || '-') + ' · ' + (member.email || '-')));

                const $role = $('<select class="form-select form-select-sm committee-role" aria-label="Peranan"></select>')
                    .append('<option value="1">Pengerusi</option><option value="2">Setiausaha</option><option value="3">Ahli</option>')
                    .val(String(member.peranan || '3'));
                const $ppWrap = $('<label class="form-check d-flex align-items-center gap-2 mb-0 small text-nowrap"><input type="checkbox" class="form-check-input committee-pp m-0"><span>P&P</span></label>');
                $ppWrap.find('input').prop('checked', String(member.p_p) === '1');
                const $remove = $('<button type="button" class="btn btn-sm btn-outline-danger btn-remove-committee-member">Buang</button>');

                $row.append($identity, $('<div class="committee-role-wrap" style="min-width:150px"></div>').append($role), $ppWrap, $remove);
                return $row;
            }

            function openCommitteeEditor(jenis, label) {
                editingCommitteeJenis = jenis;
                $('#kemaskini-ahli-jenis-label').text(label || jenis);
                $('#senarai-kemaskini-ahli').empty();
                $('#cari-pegawai-jawatankuasa').val('');
                $('#hasil-carian-pegawai-jawatankuasa').addClass('d-none').empty();
                (membersByJenis[jenis] || []).forEach(function (member) {
                    $('#senarai-kemaskini-ahli').append(buildCommitteeMemberRow(member));
                });

                const modalElement = document.getElementById('modal-kemaskini-ahli');
                if (window.bootstrap && window.bootstrap.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                } else if (typeof $('#modal-kemaskini-ahli').modal === 'function') {
                    $('#modal-kemaskini-ahli').modal('show');
                }
            }

            function searchCommitteeUsers(term) {
                const $results = $('#hasil-carian-pegawai-jawatankuasa');
                if (term.length < 2) {
                    $results.addClass('d-none').empty();
                    return;
                }
                $.getJSON(searchUsersUrl, { q: term }).done(function (users) {
                    committeeSearchResults = users || [];
                    $results.empty();
                    committeeSearchResults.forEach(function (user, index) {
                        const label = (user.name || '-') + ' · ' + (user.ic_number || '-') + ' · ' + (user.email || '-');
                        $results.append($('<button type="button" class="list-group-item list-group-item-action small"></button>')
                            .attr('data-user-index', index).text(label));
                    });
                    if (committeeSearchResults.length) $results.removeClass('d-none');
                    else $results.addClass('d-none');
                }).fail(function () {
                    $results.addClass('d-none').empty();
                });
            }

            $(document).on('click', '.btn-kemaskini-ahli', function () {
                openCommitteeEditor($(this).data('jenis'), $(this).data('label'));
            });

            $('#cari-pegawai-jawatankuasa').on('input', function () {
                const term = $(this).val().trim();
                clearTimeout(committeeSearchTimer);
                committeeSearchTimer = setTimeout(function () { searchCommitteeUsers(term); }, 250);
            });

            $('#hasil-carian-pegawai-jawatankuasa').on('click', 'button[data-user-index]', function () {
                const member = committeeSearchResults[parseInt($(this).attr('data-user-index'), 10)];
                if (!member) return;
                const exists = $('#senarai-kemaskini-ahli .committee-edit-row').toArray().some(function (row) {
                    return String($(row).attr('data-user-id')) === String(member.id);
                });
                if (!exists) {
                    $('#senarai-kemaskini-ahli').append(buildCommitteeMemberRow({
                        user_id: member.id,
                        name: member.name,
                        ic_number: member.ic_number,
                        email: member.email,
                        peranan: '3',
                        p_p: '0',
                    }));
                }
                $('#cari-pegawai-jawatankuasa').val('');
                $('#hasil-carian-pegawai-jawatankuasa').addClass('d-none').empty();
            });

            $('#senarai-kemaskini-ahli').on('click', '.btn-remove-committee-member', function () {
                $(this).closest('.committee-edit-row').remove();
            });

            $('#btn-simpan-kemaskini-ahli').on('click', async function () {
                const rows = $('#senarai-kemaskini-ahli .committee-edit-row').toArray().map(function (row) {
                    const $row = $(row);
                    return {
                        user_id: parseInt($row.attr('data-user-id'), 10),
                        peranan: $row.find('.committee-role').val(),
                        p_p: $row.find('.committee-pp').is(':checked') ? '1' : '0',
                    };
                });
                const confirmation = await fireMeetingDialog({
                    title: 'Simpan perubahan ahli?',
                    text: 'Ahli yang terjejas akan menerima e-mel pemakluman. Roster kehadiran mesyuarat akan dikemaskini untuk mesyuarat akan datang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                });
                if (!confirmation.isConfirmed) return;

                const $button = $(this).prop('disabled', true);
                $.ajax({
                    url: updateCommitteeUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: csrfToken,
                        tender: tenderUuid,
                        jenis_jawatankuasa: editingCommitteeJenis,
                        rows: rows,
                    }),
                    success: function (response) {
                        const modalElement = document.getElementById('modal-kemaskini-ahli');
                        if (window.bootstrap && window.bootstrap.Modal) {
                            window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                        } else if (typeof $('#modal-kemaskini-ahli').modal === 'function') {
                            $('#modal-kemaskini-ahli').modal('hide');
                        }
                        showMeetingNotice(response.message || 'Senarai ahli berjaya dikemaskini.', response.partial ? 'warning' : 'success').then(function () {
                            window.location.reload();
                        });
                    },
                    error: function (xhr) {
                        const response = xhr.responseJSON || {};
                        const errors = response.errors || {};
                        const firstError = Object.values(errors).flat().find(Boolean);
                        showMeetingNotice(firstError || response.message || 'Gagal mengemaskini senarai ahli.', 'error');
                    },
                    complete: function () { $button.prop('disabled', false); },
                });
            });

            function buildRow(bil, meetingId) {
                const $row = $('<tr class="mesyuarat-row">' +
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
                if (meetingId) $row.attr('data-meeting-id', meetingId);
                return $row;
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
                    const $row = buildRow(index + 1, row.id);
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
                        rows.push({
                            id: $(this).attr('data-meeting-id') || null,
                            tarikh_mesyuarat: tarikh,
                            masa: masa,
                            tempat: tempat
                        });
                    }
                });
                if (hasPastDate) {
                    showMeetingNotice('Tarikh mesyuarat mestilah hari ini atau selepasnya.', 'warning');
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

            async function postMeeting(action, uiTab) {
                if (!stosConfigured) {
                    showMeetingNotice('STOS backend tidak dikonfigurasi.', 'warning');
                    return;
                }
                const rows = collectRows(uiTab);
                if (rows === null) {
                    return;
                }
                if (!rows.length) {
                    showMeetingNotice('Sila lengkapkan sekurang-kurangnya satu perincian mesyuarat.', 'warning');
                    return;
                }
                if (action === 'hantar') {
                    const confirmation = await fireMeetingDialog({
                        title: 'Hantar jemputan mesyuarat?',
                        text: 'Jemputan akan dihantar kepada ahli jawatankuasa tab ini sahaja.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hantar',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    });
                    if (!confirmation.isConfirmed) return;
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
                        hideLoading();
                        showMeetingNotice(res.message || 'Berjaya.', 'success').then(function () {
                            window.location.reload();
                        });
                    },
                    error: function (xhr) {
                        hideLoading();
                        showMeetingNotice((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memproses permintaan.', 'error');
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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
