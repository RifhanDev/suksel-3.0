@php
    $uiTab = $tab['ui'];
    $jenis = $tab['jenis'];
    $label = $tab['label'];
    $meetings = $meetingsByJenis[$jenis] ?? [];
    $members = $membersByJenis[$jenis] ?? [];
    $selectedId = (($selectedMeeting['jenis_jawatankuasa'] ?? '') === $jenis)
        ? ($selectedMeeting['id'] ?? null)
        : null;
    $showKelulusan = (bool) $untukKelulusan && $selectedId;
@endphp

<div class="tab-content kehadiran-tab {{ empty($isFirst) ? 'd-none' : '' }}" data-tab="{{ $uiTab }}" data-jenis="{{ $jenis }}">
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Kehadiran {{ $label }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Urusetia</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row mb-3 g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Mesyuarat</label>
                    <select class="form-select form-select-sm meeting-select" data-ui-tab="{{ $uiTab }}" data-jenis="{{ $jenis }}">
                        <option value="">Sila Pilih</option>
                        @foreach($meetings as $meeting)
                            <option value="{{ $meeting['id'] ?? '' }}" @selected($selectedId == ($meeting['id'] ?? null))>
                                {{ $meeting['label'] ?? (($meeting['tarikh_mesyuarat'] ?? '') . ' ' . ($meeting['masa'] ?? '')) }}
                                @if(!empty($meeting['tempat'])) — {{ $meeting['tempat'] }} @endif
                            </option>
                        @endforeach
                    </select>
                    @if(empty($meetings))
                        <small class="text-muted">Tiada mesyuarat dihantar untuk tab ini. Sila lengkapkan perincian mesyuarat terlebih dahulu.</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <div>
                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase">
                            {{ $tender->status ?? 'Dalam Proses' }}
                        </span>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input untuk-kelulusan-checkbox" type="checkbox"
                            id="untuk_kelulusan_{{ $uiTab }}" data-ui-tab="{{ $uiTab }}"
                            @checked($showKelulusan)>
                        <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_{{ $uiTab }}">
                            Untuk Kelulusan
                        </label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3">No. Kad Pengenalan</th>
                            <th class="text-center py-3">Nama</th>
                            <th class="text-center py-3">Jawatan</th>
                            <th class="text-center py-3">E-mel</th>
                            <th class="text-center py-3">Gred</th>
                            <th class="text-center py-3">P&P</th>
                            <th class="text-center py-3">Peranan</th>
                            <th class="text-center py-3" style="width:80px;">Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="kehadiran-member-body" data-ui-tab="{{ $uiTab }}">
                        @forelse($members as $member)
                            <tr data-jawatankuasa-id="{{ $member['id'] }}">
                                <td class="text-center">{{ $member['ic_number'] ?? '-' }}</td>
                                <td>{{ $member['name'] ?? '-' }}</td>
                                <td>{{ $member['jawatan'] ?? '-' }}</td>
                                <td>{{ $member['email'] ?? '-' }}</td>
                                <td class="text-center">{{ $member['gred'] ?? '-' }}</td>
                                <td class="text-center">{{ $member['p_p'] ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                                        {{ $member['peranan'] ?? 'Ahli' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center mb-0">
                                        <input class="form-check-input hadir-checkbox" type="checkbox"
                                            value="{{ $member['id'] }}"
                                            @checked(!empty($member['hadir']))>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Tiada ahli jawatankuasa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        <button type="button" class="btn-form btn-form-success btn-simpan-kehadiran" data-ui-tab="{{ $uiTab }}" data-jenis="{{ $jenis }}">
            Simpan
        </button>
    </div>
</div>
