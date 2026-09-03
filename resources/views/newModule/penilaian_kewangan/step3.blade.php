{{-- Step 3: Pematuhan Spesifikasi Kewangan (Kewangan + Rumusan) --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-3" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-3" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content mt-4">
    <div class="tab-pane fade show active step3-kewangan-pane" id="kewangan-3" role="tabpanel" aria-labelledby="kewangan-3-tab">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary-subtle p-2 rounded-2 me-3">
                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Pematuhan Spesifikasi Kewangan</h5>
                <p class="text-secondary small mb-0">Papar maklumat penilaian cadangan kewangan.</p>
            </div>
        </div>
        <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af; animation: alertPopBuzz 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div>
                <span class="small fw-medium text-info-emphasis"><strong>Informasi:</strong></span>
                <p class="mb-0 small">Klik butang <strong>Papar</strong> untuk meneruskan penilaian.</p>
            </div>
        </div>

        <div class="table-responsive mb-3 rounded-3 shadow-sm border bg-white">
            <table class="table table-hover align-middle mb-0 w-100 step3-kewangan-table">
                <thead>
                    <tr class="bg-light">
                        <th class="py-3 px-3 text-start fw-bold text-secondary text-uppercase" style="width: 42%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-file-earmark-text text-danger me-1"></i>Tajuk / Dokumen
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 22%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-gear text-danger me-1"></i>Mekanisma
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 20%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-shield-check text-danger me-1"></i>Status Penilaian
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 16%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-sliders text-danger me-1"></i>Tindakan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kewanganItems as $index => $item)
                        @php
                            $itemTitle = $item['title'] ?? $item['nama'] ?? 'Item Senarai Semak Kewangan';
                            $itemMekanisma = $item['tindakan'] ?? $item['mekanisma'] ?? 'Spesifikasi';
                            $itemUuid = $item['uuid'] ?? '';

                            $itemPayload = $semakPayload[$itemUuid] ?? null;
                            $itemVendors = $itemPayload['vendors'] ?? [];

                            $failedStep1VendorIds = collect($semakPayload ?? [])
                                ->reject(function ($payload, $uuid) use ($penyataBankItems) {
                                    return in_array($uuid, collect($penyataBankItems ?? [])->pluck('uuid')->all(), true);
                                })
                                ->flatMap(function ($payload) {
                                    return collect($payload['vendors'] ?? [])
                                        ->filter(fn ($v) => $v['status_pematuhan'] === 'tidak_mematuhi' || $v['status_pematuhan'] === 0 || $v['status_pematuhan'] === '0')
                                        ->pluck('vendor_id');
                                })
                                ->unique()
                                ->toArray();

                            $eligibleVendors = collect($itemVendors)->reject(function ($v) use ($failedStep1VendorIds) {
                                return in_array((int) $v['vendor_id'], $failedStep1VendorIds, true);
                            });

                            $totalEligible = $eligibleVendors->count();

                            $reviewedCount = $eligibleVendors->filter(function($v) use ($item) {
                                return !empty($v['step3_evaluated']);
                            })->count();

                            $isItemSelesai = ($totalEligible > 0 && $reviewedCount === $totalEligible);
                        @endphp
                        <tr data-item-uuid="{{ $itemUuid }}">
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-file-earmark-text fs-6 text-success"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $itemTitle }}</span>
                                </div>
                            </td>
                            <td class="text-center px-3">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace fw-medium">
                                    {{ $itemMekanisma }}
                                </span>
                            </td>
                            <td class="status-penilaian text-center px-3">
                                @if($isItemSelesai)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                        <i class="bi bi-clock me-1"></i>Menunggu Penilaian
                                    </span>
                                @endif
                            </td>
                            <td class="text-center px-3">
                                @if(strtolower(trim($itemMekanisma)) === 'spesifikasi')
                                    <button type="button" class="btn btn-sm btn-success btn-papar-cadangan-kewangan-step3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#modalPaparCadanganKewanganStep3"
                                        data-dokumen="{{ $itemTitle }}"
                                        data-uuid="{{ $itemUuid }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Menilai</span>
                                    </button>
                                @elseif(str_contains(strtolower($itemTitle), 'profil') || strtolower(trim($itemMekanisma)) === 'borang atas talian' || strtolower(trim($itemMekanisma)) === 'online_form')
                                    <button type="button" class="btn btn-sm btn-success btn-papar-profil-petender-step3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#modalPaparProfilPetenderStep3"
                                        data-dokumen="{{ $itemTitle }}"
                                        data-uuid="{{ $itemUuid }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Menilai</span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-success btn-papar-muat-naik-step3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#modalMuatNaikStep3"
                                        data-dokumen="{{ $itemTitle }}"
                                        data-uuid="{{ $itemUuid }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Menilai</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada item spesifikasi kewangan dijumpai bagi tender ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row mb-3 mt-2">
            <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    @php
        $r3Data = $rumusanStep3Data ?? [
            'passing_percentage' => 50,
            'passing_score' => 0,
            'total_max_score' => 100,
            'pembekal_melepasi' => [],
            'pembekal_tidak_melepasi' => [],
            'count_melepasi' => 0,
            'count_tidak_melepasi' => 0,
        ];
    @endphp

    <div class="tab-pane fade step3-rumusan-pane" id="rumusan-3" role="tabpanel" aria-labelledby="rumusan-3-tab">
        <div class="container-fluid mt-2 px-0">

            {{-- Header Banner --}}
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary-subtle p-2.5 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #e0f2fe;">
                    <i class="bi bi-clipboard-data text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Rumusan Penilaian Kewangan</h5>
                    <p class="text-secondary small mb-0">Rumusan keseluruhan dan kedudukan petender bagi Pematuhan Spesifikasi Kewangan.</p>
                </div>
            </div>

            {{-- SECTION 1: Senarai Pembekal Yang Melepasi --}}
            <div class="mb-2 mt-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Yang Melepasi Penilaian Kewangan
                        </h6>
                        <div class="small text-muted mt-1" id="step3TotalMelepasiText">{{ $r3Data['count_melepasi'] }} pembekal melepasi</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.875rem;">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">Kedudukan</th>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 30%; font-size: 0.725rem; letter-spacing: 0.5px;">Kod Pembekal</th>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 30%; font-size: 0.725rem; letter-spacing: 0.5px;">Jumlah Skor</th>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 25%; font-size: 0.725rem; letter-spacing: 0.5px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="step3RumusanMelepasiTableBody">
                        @forelse($r3Data['pembekal_melepasi'] as $p)
                            <tr>
                                <td class="text-center py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1.5 rounded-pill font-monospace fw-bold fs-6">{{ $p['kedudukan'] }}</span>
                                </td>
                                <td class="text-center py-3 font-monospace fw-bold text-dark">{{ $p['kod'] }}</td>
                                <td class="text-center py-3 font-monospace fw-bold text-primary fs-6">{{ $p['score_fmt'] }}</td>
                                <td class="text-center py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1.5 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>{{ $p['status_label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-4" colspan="4" style="font-size: 0.875rem;">
                                    <i class="bi bi-inbox me-1 fs-5"></i>Tiada pembekal melepasi penanda aras kewangan setakat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Benchmarking & Threshold Info Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-3.5">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="rounded-2 p-2 me-3 d-flex align-items-center justify-content-center" style="background: #e0f2fe; width: 40px; height: 40px;">
                                <i class="bi bi-speedometer2 text-primary fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block text-uppercase fw-semibold text-muted" style="font-size: 0.65rem; letter-spacing: 0.05em;">Kriteria Kelulusan</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Penetapan Penanda Aras Tahap Lulus (%)</span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-md-end gap-4">
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 small text-secondary fw-medium">Penanda Aras:</label>
                                <div class="input-group input-group-sm" style="width: 90px;">
                                    <input type="text" class="form-control text-center font-monospace fw-bold bg-white" value="{{ (float)$r3Data['passing_percentage'] == (int)$r3Data['passing_percentage'] ? (int)$r3Data['passing_percentage'] : number_format((float)$r3Data['passing_percentage'], 2) }}" readonly aria-label="Penanda aras tahap lulus">
                                    <span class="input-group-text bg-white fw-bold">%</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 small text-secondary fw-medium">Bil. Melepasi:</label>
                                <span class="badge bg-success px-3 py-2 font-monospace fw-bold fs-6" id="step3MelepasiBadgeCount">{{ $r3Data['count_melepasi'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengesahan Akhir Card --}}
            <div class="card bg-light border-0 shadow-none mb-4 rounded-3">
                <div class="card-body p-3.5">
                    <h6 class="fw-bold text-dark mb-2.5"><i class="bi bi-shield-check me-2 text-primary"></i>Pengesahan Akhir</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep3" name="confirm_layak_step3" {{ ($progress && $progress->isStep3Confirmed()) ? 'checked disabled' : '' }}>
                        <label class="form-check-label small fw-medium text-dark ms-1" for="confirmLayakStep3">
                            Saya mengesahkan petender di atas <span class="text-success fw-bold">layak</span> untuk disyorkan kepada Lembaga.
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Senarai Pembekal Tidak Melepasi --}}
            <div class="mb-2 mt-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Penilaian Kewangan
                        </h6>
                        <div class="small text-muted mt-1" id="step3TotalTidakMelepasiText">{{ $r3Data['count_tidak_melepasi'] }} pembekal tidak melepasi</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.875rem;">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 25%; font-size: 0.725rem; letter-spacing: 0.5px;">Kod Pembekal</th>
                            <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 25%; font-size: 0.725rem; letter-spacing: 0.5px;">Jumlah Skor</th>
                            <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 50%; font-size: 0.725rem; letter-spacing: 0.5px;">Catatan / Sebab</th>
                        </tr>
                    </thead>
                    <tbody id="step3RumusanTidakMelepasiTableBody">
                        @forelse($r3Data['pembekal_tidak_melepasi'] as $p)
                            <tr>
                                <td class="text-center py-3 font-monospace fw-bold text-dark">{{ $p['kod'] }}</td>
                                <td class="text-center py-3 font-monospace fw-bold text-danger fs-6">{{ $p['score_fmt'] }}</td>
                                <td class="text-start py-3 small text-secondary">{{ $p['catatan'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-4" colspan="3" style="font-size: 0.875rem;">
                                    <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center gap-2 mb-4">
                <span class="small text-secondary fw-medium">Bilangan Pembekal Tidak Melepasi:</span>
                <span class="badge bg-secondary px-3 py-1.5 font-monospace fw-bold" id="step3TidakMelepasiBadgeCount">{{ $r3Data['count_tidak_melepasi'] }}</span>
            </div>

            {{-- Navigation Buttons --}}
            <div class="row mb-3 pt-2">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sebelumnya px-4 fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        <span>Sebelumnya</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-seterusnya px-4 fw-bold d-inline-flex align-items-center gap-2">
                        <span>Seterusnya</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaparCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background: #dbeafe;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Tajuk / Dokumen</span>
                        <h6 id="step3ModalDokumenTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
                    </div>
                    <div class="mx-3 align-self-stretch" style="width: 1px; background: #d1d5db;"></div>
                    <span class="text-secondary" style="font-size: 0.78rem;">Penilaian pematuhan spesifikasi kewangan mengikut petender.</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="table-responsive rounded-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 12%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Kod Pembekal</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 18%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Status Bumiputera</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 22%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Harga Tawaran (RM)</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 16%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Jumlah Skor</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 16%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Perbezaan</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 16%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="step3VendorTableBody">
                            @php
                                $failedStep1And2VendorIds = collect($semakPayload ?? [])
                                    ->flatMap(function ($payload) {
                                        return collect($payload['vendors'] ?? [])
                                            ->filter(fn ($v) => $v['status_pematuhan'] === 'tidak_mematuhi' || $v['status_pematuhan'] === 0 || $v['status_pematuhan'] === '0')
                                            ->pluck('vendor_id');
                                    })
                                    ->unique()
                                    ->toArray();

                                $allTenderVendors = collect($vendors ?? []);
                                $step3EligibleVendors = $allTenderVendors->reject(function ($v) use ($failedStep1And2VendorIds) {
                                    return in_array((int) $v['vendor_id'], $failedStep1And2VendorIds, true);
                                })->values();
                            @endphp
                            @forelse($step3EligibleVendors as $idx => $v)
                                @php
                                    $kodDisplay = $v['kod'] ?: (($idx + 1) . '/' . $step3EligibleVendors->count());
                                    $bumiStatus = $v['bumiputera_status'] ?? ($v['is_bumiputera'] ? 'Bumiputera' : 'Bukan Bumiputera');
                                    $hargaDisplay = isset($v['harga_tawaran']) && $v['harga_tawaran'] > 0
                                        ? number_format((float)$v['harga_tawaran'], 2)
                                        : ($v['harga_tawaran_fmt'] ?? '-');
                                @endphp
                                <tr>
                                    <td class="text-center font-monospace fw-medium">{{ $kodDisplay }}</td>
                                    <td class="text-center">{{ $bumiStatus }}</td>
                                    <td class="text-end fw-medium px-3">{{ $hargaDisplay }}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-success btn-step3-papar-vendor px-3 py-1 font-monospace d-inline-flex align-items-center gap-1"
                                            data-vendor-id="{{ $v['vendor_id'] }}"
                                            data-vendor-kod="{{ $kodDisplay }}"
                                            data-vendor-status="{{ $bumiStatus }}"
                                            data-harga="{{ $hargaDisplay }}"
                                            data-skor-automatik="-"
                                            data-perbezaan-harga="-"
                                            data-perbezaan-peratus="-">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Menilai</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-1"></i>Tiada petender yang melepasi penilaian kewangan setakat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Anggaran Jabatan Sebenar Card --}}
                <div class="rounded-3 p-3 mt-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <span class="fw-semibold text-dark" style="font-size: 0.85rem;">Anggaran Jabatan Sebenar (RM):</span>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <input type="text" class="form-control form-control-sm text-center fw-bold bg-white" value="{{ isset($tender->anggaran_jabatan) && is_numeric($tender->anggaran_jabatan) ? number_format((float)$tender->anggaran_jabatan, 2) : '0.00' }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    Sila klik butang <strong>Menilai</strong> untuk menjalankan penilaian pematuhan spesifikasi kewangan petender.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaparVendorCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparVendorCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: #dbeafe;">
                        <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #6b7280;">Penilaian Kewangan</span>
                        <h6 id="modalPaparVendorCadanganKewanganStep3Label" class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Penilaian Cadangan Kewangan (Spesifikasi)</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">

                {{-- Vendor & Document Info Summary Banner --}}
                <div class="rounded-3 p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3 border-end border-slate-200">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Kod Pembekal</span>
                            <span id="step3VendorKod" class="fw-bold text-dark fs-6">-</span>
                        </div>
                        <div class="col-md-9">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Tajuk / Dokumen</span>
                            <span id="step3VendorDokumenTitle" class="fw-semibold text-primary small text-truncate d-block">-</span>
                        </div>
                    </div>
                </div>

                {{-- Section: Spesifikasi Table --}}
                <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th class="py-2.5 px-3 text-uppercase fw-bold" style="width: 40%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Item / Spesifikasi</th>
                                <th class="text-center py-2.5 px-3 text-uppercase fw-bold" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Kekerapan / Kuantiti</th>
                                <th class="text-center py-2.5 px-3 text-uppercase fw-bold" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Unit / Ukuran</th>
                                <th class="text-end py-2.5 px-3 text-uppercase fw-bold" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Anggaran Jabatan (RM)</th>
                                <th class="text-end py-2.5 px-3 text-uppercase fw-bold" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Tawaran Harga (RM)</th>
                            </tr>
                        </thead>
                        <tbody id="step3VendorDetailTableBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4"><i class="bi bi-arrow-repeat spin me-1"></i>Memuatkan maklumat spesifikasi...</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-center py-2.5 text-uppercase">JUMLAH</td>
                                <td id="step3TotalAnggaranJabatan" class="text-end px-3 py-2.5 text-dark font-monospace">RM 0.00</td>
                                <td id="step3TotalTawaranHarga" class="text-end px-3 py-2.5 text-dark font-monospace">RM 0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Evaluation Form: Skor & Catatan --}}
                <div class="card border-0 bg-light rounded-3 p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="step3VendorSkorInput" class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">
                                Skor Penilaian <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" max="100" id="step3VendorSkorInput" class="form-control fw-bold text-center" placeholder="0.00" aria-label="Skor">
                                <span id="step3VendorMaxSkorLabel" class="input-group-text font-monospace bg-white">/ 100</span>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Masukkan skor penilaian bagi petender ini.</div>
                        </div>
                        <div class="col-md-8">
                            <label for="step3VendorCatatanInput" class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">
                                Catatan Penilai
                            </label>
                            <textarea id="step3VendorCatatanInput" class="form-control form-control-sm" rows="3" placeholder="Masukkan ulasan atau catatan penilaian spesifikasi kewangan..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    Pastikan skor dan catatan telah disemak sebelum menekan butang <strong>Simpan Penilaian</strong>.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali
                </button>
                <button type="button" id="btnSimpanPenilaianStep3" class="btn btn-sm btn-success px-4 fw-bold d-inline-flex align-items-center gap-1">
                    <i class="bi bi-save"></i>
                    <span>Simpan Penilaian</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 1: Vendor Listing Modal for Maklumat Profil Petender --}}
<div class="modal fade" id="modalPaparProfilPetenderStep3" tabindex="-1" aria-labelledby="modalPaparProfilPetenderStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: #dbeafe;">
                        <i class="bi bi-file-earmark-person fs-4 text-primary"></i>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #6b7280;">Penilaian Kewangan</span>
                        <h6 id="modalPaparProfilPetenderStep3Label" class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Penilaian Cadangan Kewangan (Maklumat Profil Petender)</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="rounded-3 p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="row g-3 align-items-center">
                        <div class="col-12">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Dokumen / Borang Atas Talian</span>
                            <span id="step3ProfilPetenderModalDokumenTitle" class="fw-bold text-dark fs-6">Maklumat Profil Petender</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th rowspan="2" class="text-center text-uppercase fw-bold py-2" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important; vertical-align: middle;">Kod Pembekal</th>
                                <th rowspan="2" class="text-center text-uppercase fw-bold py-2" style="width: 30%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important; vertical-align: middle;">Dokumen</th>
                                <th colspan="2" class="text-center text-uppercase fw-bold py-1 border-bottom" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Jumlah Skor</th>
                                <th rowspan="2" class="text-center text-uppercase fw-bold py-2" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important; vertical-align: middle;">Tindakan</th>
                            </tr>
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-1" style="width: 20%; font-size: 0.65rem; letter-spacing: 0.05em; background-color: #e5e7eb !important; color: #3f3f3f !important;">Modal Berbayar</th>
                                <th class="text-center text-uppercase fw-bold py-1" style="width: 20%; font-size: 0.65rem; letter-spacing: 0.05em; background-color: #e5e7eb !important; color: #3f3f3f !important;">Modal Dibenarkan</th>
                            </tr>
                        </thead>
                        <tbody id="step3ProfilPetenderVendorTableBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4"><i class="bi bi-arrow-repeat spin me-1"></i>Memuatkan senarai petender...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    Sila klik butang <strong>Menilai</strong> untuk menjalankan penilaian maklumat profil petender.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: Vendor Evaluation Modal for Maklumat Profil Petender --}}
<div class="modal fade" id="modalPaparVendorProfilPetenderStep3" tabindex="-1" aria-labelledby="modalPaparVendorProfilPetenderStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: #dbeafe;">
                        <i class="bi bi-person-lines-fill fs-4 text-primary"></i>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #6b7280;">Penilaian Kewangan</span>
                        <h6 id="modalPaparVendorProfilPetenderStep3Label" class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Penilaian Maklumat Profil Petender</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">

                {{-- Vendor & Document Banner --}}
                <div class="rounded-3 p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3 border-end border-slate-200">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Kod Pembekal</span>
                            <span id="step3ProfilVendorKod" class="fw-bold text-dark fs-6">-</span>
                        </div>
                        <div class="col-md-9">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Nama Syarikat</span>
                            <span id="step3ProfilVendorNamaSyarikat" class="fw-semibold text-primary small text-truncate d-block">-</span>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Read-Only Vendor Profile Information --}}
                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                        <i class="bi bi-info-circle text-primary"></i>
                        Maklumat Profil Petender (Borang Dihantar - Read-Only)
                    </h6>
                    <div class="row g-3" style="font-size: 0.85rem;">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Nama Syarikat</label>
                            <input type="text" id="viewProfilNamaSyarikat" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Jenis Syarikat</label>
                            <input type="text" id="viewProfilJenisSyarikat" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Taraf Petender</label>
                            <input type="text" id="viewProfilTarafPetender" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">No. Pendaftaran SSM</label>
                            <input type="text" id="viewProfilNoSSM" class="form-control form-control-sm bg-light font-monospace" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">No. Pendaftaran MOF</label>
                            <input type="text" id="viewProfilNoMOF" class="form-control form-control-sm bg-light font-monospace" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Tempoh Sah MOF</label>
                            <input type="text" id="viewProfilTempohMOF" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Alamat Syarikat</label>
                            <textarea id="viewProfilAlamat" class="form-control form-control-sm bg-light" rows="2" readonly></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Nama Pegawai Khidmat</label>
                            <input type="text" id="viewProfilPegawaiNama" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">No. Telefon Pegawai</label>
                            <input type="text" id="viewProfilPegawaiTel" class="form-control form-control-sm bg-light font-monospace" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Emel Pegawai</label>
                            <input type="text" id="viewProfilPegawaiEmel" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Bil. Pekerja Pengurusan</label>
                            <input type="text" id="viewProfilBilPekerja" class="form-control form-control-sm bg-light text-center font-monospace" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Bil. Pekerja Teknikal</label>
                            <input type="text" id="viewProfilBilPekerjaTeknikal" class="form-control form-control-sm bg-light text-center font-monospace" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Modal Berbayar (RM)</label>
                            <input type="text" id="viewProfilModalBerbayar" class="form-control form-control-sm bg-light text-end font-monospace fw-bold text-dark" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Modal Dibenarkan (RM)</label>
                            <input type="text" id="viewProfilModalDibenarkan" class="form-control form-control-sm bg-light text-end font-monospace fw-bold text-dark" readonly>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Modal Berbayar Scoring Reference & Evaluation --}}
                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="background: #fafafa; border: 1px solid #e5e7eb !important;">
                    <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                        <i class="bi bi-wallet2 text-success"></i>
                        Penilaian 1: Modal Berbayar
                    </h6>
                    <p class="text-muted small mb-3">Jadual rujukan julat pematuhan bagi Modal Berbayar (konfigurasi pegawai):</p>
                    
                    <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                        <table class="table table-sm align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2 px-3 text-uppercase fw-bold text-muted" style="width: 60%; font-size: 0.7rem;">Julat Modal Berbayar (RM)</th>
                                    <th class="text-center py-2 px-3 text-uppercase fw-bold text-muted" style="width: 40%; font-size: 0.7rem;">Skor Kelayakan</th>
                                </tr>
                            </thead>
                            <tbody id="step3ModalBerbayarScoringTbody">
                                <tr><td colspan="2" class="text-center text-muted py-2">Tiada skema julat dikonfigurasikan.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row align-items-center g-2">
                        <div class="col-md-4">
                            <label for="step3ModalBerbayarSkorInput" class="form-label fw-bold text-dark mb-1" style="font-size: 0.83rem;">
                                Skor Modal Berbayar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" id="step3ModalBerbayarSkorInput" class="form-control fw-bold text-center" placeholder="0.00">
                                <span id="step3ModalBerbayarMaxLabel" class="input-group-text font-monospace bg-white">/ 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Modal Dibenarkan Scoring Reference & Evaluation --}}
                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="background: #fafafa; border: 1px solid #e5e7eb !important;">
                    <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                        <i class="bi bi-bank text-info"></i>
                        Penilaian 2: Modal Dibenarkan
                    </h6>
                    <p class="text-muted small mb-3">Jadual rujukan julat pematuhan bagi Modal Dibenarkan (konfigurasi pegawai):</p>
                    
                    <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                        <table class="table table-sm align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2 px-3 text-uppercase fw-bold text-muted" style="width: 60%; font-size: 0.7rem;">Julat Modal Dibenarkan (RM)</th>
                                    <th class="text-center py-2 px-3 text-uppercase fw-bold text-muted" style="width: 40%; font-size: 0.7rem;">Skor Kelayakan</th>
                                </tr>
                            </thead>
                            <tbody id="step3ModalDibenarkanScoringTbody">
                                <tr><td colspan="2" class="text-center text-muted py-2">Tiada skema julat dikonfigurasikan.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row align-items-center g-2">
                        <div class="col-md-4">
                            <label for="step3ModalDibenarkanSkorInput" class="form-label fw-bold text-dark mb-1" style="font-size: 0.83rem;">
                                Skor Modal Dibenarkan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" id="step3ModalDibenarkanSkorInput" class="form-control fw-bold text-center" placeholder="0.00">
                                <span id="step3ModalDibenarkanMaxLabel" class="input-group-text font-monospace bg-white">/ 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Catatan Penilai --}}
                <div class="card border-0 bg-light rounded-3 p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="step3VendorProfilCatatanInput" class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">
                                Catatan Penilai
                            </label>
                            <textarea id="step3VendorProfilCatatanInput" class="form-control form-control-sm" rows="3" placeholder="Masukkan ulasan atau catatan penilaian profil petender..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    Pastikan skor Modal Berbayar, Modal Dibenarkan, dan catatan telah disemak sebelum menekan <strong>Simpan Penilaian</strong>.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali
                </button>
                <button type="button" id="btnSimpanPenilaianProfilStep3" class="btn btn-sm btn-success px-4 fw-bold d-inline-flex align-items-center gap-1">
                    <i class="bi bi-save"></i>
                    <span>Simpan Penilaian</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Vendor Evaluation Modal for Muat Naik Mechanism --}}
<div class="modal fade" id="modalMuatNaikStep3" tabindex="-1" aria-labelledby="modalMuatNaikStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: #dbeafe;">
                        <i class="bi bi-file-earmark-arrow-up fs-4 text-primary"></i>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #6b7280;">Penilaian Kewangan</span>
                        <h6 id="modalMuatNaikStep3Label" class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Penilaian Cadangan Kewangan (Muat Naik)</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">

                {{-- Document / Item Title Banner --}}
                <div class="rounded-3 p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="row g-3 align-items-center">
                        <div class="col-12">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Dokumen / Item Kewangan</span>
                            <span id="step3MuatNaikModalDokumenTitle" class="fw-bold text-dark fs-6">-</span>
                        </div>
                    </div>
                </div>

                {{-- Vendor Evaluation Table --}}
                <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-2.5 px-3" style="width: 25%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Kod Pembekal</th>
                                <th class="text-center text-uppercase fw-bold py-2.5 px-3" style="width: 50%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dokumen</th>
                                <th class="text-center text-uppercase fw-bold py-2.5 px-3" style="width: 25%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Skor</th>
                            </tr>
                        </thead>
                        <tbody id="step3MuatNaikVendorTableBody">
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4"><i class="bi bi-arrow-repeat spin me-1"></i>Memuatkan senarai petender...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Informational Confirmation Note Alert --}}
                <div id="step3MuatNaikNoteAlert" class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    <span><strong>Nota:</strong> Sila sahkan semua skor penilaian dengan menekan butang <strong>"Simpan Penilaian"</strong>. Jika penilaian tidak disimpan, semua pembekal akan kekal sebagai <strong>belum dinilai</strong>.</span>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali
                </button>
                <button type="button" id="btnSimpanPenilaianMuatNaikStep3" class="btn btn-sm btn-success px-4 fw-bold d-inline-flex align-items-center gap-1">
                    <i class="bi bi-save"></i>
                    <span>Simpan Penilaian</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .step3-kewangan-pane .step3-kewangan-table {
        border-color: #bfc5d1;
    }

    .step3-kewangan-pane .step3-kewangan-table thead th {
        background-color: #324e9b;
        border-color: #3b5398;
        font-weight: 500;
        font-size: 13px;
        padding: 10px 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-kewangan-pane .step3-kewangan-table tbody td {
        border-color: #c9ced8;
        font-size: 13px;
        padding: 11px 12px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(1),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(1) {
        width: 63%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(2),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(2) {
        width: 14%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(3),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(3) {
        width: 15%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(4),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(4) {
        width: 8%;
        min-width: 110px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan,
    .step3-papar-modal .btn-papar-semakan-kewangan {
        background-color: #16A34A;
        border-color: #16A34A;
        color: #fff;
        min-width: 110px;
        font-size: 13px;
        line-height: 1.25;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan:hover,
    .step3-kewangan-pane .btn-papar-semakan-kewangan:focus,
    .step3-papar-modal .btn-papar-semakan-kewangan:hover,
    .step3-papar-modal .btn-papar-semakan-kewangan:focus {
        background-color: #15803D;
        border-color: #15803D;
        color: #fff;
    }

    .step3-rumusan-pane .form-select-sm,
    .step3-rumusan-pane .form-control-sm {
        height: 32px;
        font-size: 12px;
    }

    .step3-rumusan-strip {
        background-color: #d9d9d9;
        border: 1px solid #d2d4da;
        border-bottom: 0;
        color: #222;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
        text-transform: uppercase;
    }

    .step3-rumusan-table {
        border-color: #a6adba;
    }

    .step3-rumusan-table thead th {
        background-color: #324e9b;
        border-color: #5f6fa4;
        color: #fff;
        font-weight: 500;
        font-size: 12px;
        padding: 6px 10px;
        vertical-align: middle;
    }

    .step3-rumusan-table td {
        border-color: #a6adba;
        font-size: 12px;
        padding: 7px 10px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-rumusan-pane .form-check-input {
        width: 12px;
        height: 12px;
        border-radius: 0;
        margin-top: 0.35em;
    }

    .step3-rumusan-pane .form-check-label {
        font-size: 14px;
    }

    .step3-papar-modal .modal-body {
        background: #f6f7f9;
    }

    .step3-modal-section-title {
        background-color: #d9d9d9;
        font-size: 14px;
        font-weight: 700;
        padding: 6px 10px;
        color: #222;
    }

    .step3-modal-hint {
        font-size: 12px;
        color: #2b5cba;
        font-style: italic;
    }

    .step3-modal-table {
        border-color: #9aa3b3;
    }

    .step3-modal-table thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-weight: 500;
        font-size: 13px;
        padding: 8px 10px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .step3-modal-table td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 10px;
        background: #fff;
    }

    .step3-modal-table-detail {
        border-color: #9aa3b3;
    }

    .step3-modal-table-detail thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 8px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-modal-table-detail td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 8px;
        background-color: #fff;
        vertical-align: middle;
    }

    .step3-modal-table-detail th:nth-child(1),
    .step3-modal-table-detail td:nth-child(1) {
        min-width: 220px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep3ItemUuid = null;
    let currentStep3VendorId = null;

    function getFailedVendorIdsStep3() {
        const failedSet = new Set();
        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD) {
            Object.keys(SEMAK_PAYLOAD).forEach(uuid => {
                const itemObj = SEMAK_PAYLOAD[uuid];
                (itemObj?.vendors || []).forEach(v => {
                    if (v.status_pematuhan === 'tidak_mematuhi' || v.status_pematuhan === 0 || v.status_pematuhan === '0') {
                        failedSet.add(parseInt(v.vendor_id));
                    }
                });
            });
        }
        return Array.from(failedSet);
    }

    function updateMainTableStatusPenilaian(itemUuid) {
        if (!itemUuid || typeof SEMAK_PAYLOAD === 'undefined' || !SEMAK_PAYLOAD[itemUuid]) return;

        const failedVendorIds = getFailedVendorIdsStep3();
        const rawVendors = SEMAK_PAYLOAD[itemUuid].vendors || [];
        const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

        const totalEligible = eligibleVendors.length;
        const evaluatedCount = eligibleVendors.filter(v => {
            return !!v.step3_evaluated;
        }).length;

        const isSelesai = totalEligible > 0 && evaluatedCount === totalEligible;

        const tr = document.querySelector(`tr[data-item-uuid="${itemUuid}"]`);
        if (tr) {
            const tdStatus = tr.querySelector('.status-penilaian');
            if (tdStatus) {
                if (isSelesai) {
                    tdStatus.innerHTML = `
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill">
                            <i class="bi bi-check-circle me-1"></i>Selesai
                        </span>
                    `;
                } else {
                    tdStatus.innerHTML = `
                        <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill">
                            <i class="bi bi-clock me-1"></i>Menunggu Penilaian
                        </span>
                    `;
                }
            }
        }
    }

    function renderStep3VendorTableModal(itemUuid) {
        const tbody = document.getElementById('step3VendorTableBody');
        if (!tbody) return;

        const failedVendorIds = getFailedVendorIdsStep3();
        let rawVendors = [];

        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid && SEMAK_PAYLOAD[itemUuid]) {
            rawVendors = SEMAK_PAYLOAD[itemUuid].vendors || [];
        } else if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD) {
            const firstKey = Object.keys(SEMAK_PAYLOAD)[0];
            if (firstKey && SEMAK_PAYLOAD[firstKey]) {
                rawVendors = SEMAK_PAYLOAD[firstKey].vendors || [];
            }
        }

        const itemObj = (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid) ? SEMAK_PAYLOAD[itemUuid] : null;
        const specDetail = itemObj ? (itemObj.spesifikasi_detail || {}) : {};
        const maxScore = parseFloat(specDetail.max_score || itemObj?.max_score || 100);
        const fmtMax = (maxScore % 1 === 0) ? maxScore.toFixed(0) : maxScore.toFixed(2);

        const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

        if (!eligibleVendors.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-info-circle me-1"></i>Tiada petender yang melepasi penilaian kewangan setakat ini.</td></tr>';
            return;
        }

        tbody.innerHTML = '';
        eligibleVendors.forEach((v, idx) => {
            const kodDisplay = v.kod ? v.kod : ((idx + 1) + '/' + eligibleVendors.length);
            const bumiStatus = v.bumiputera_status || (v.is_bumiputera ? 'Bumiputera' : 'Bukan Bumiputera');
            const hargaFmt = v.harga_tawaran_fmt || (v.harga_tawaran ? parseFloat(v.harga_tawaran).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-');

            const isEvaluated = (v.skor !== null && v.skor !== undefined && v.skor !== '');
            let skorDisplay = '-';
            if (isEvaluated) {
                const numericSkor = parseFloat(v.skor);
                const fmtSkor = (numericSkor % 1 === 0) ? numericSkor.toFixed(0) : numericSkor.toFixed(2);
                skorDisplay = `${fmtSkor}/${fmtMax}`;
            }

            let actionBtnHtml = '';
            if (isEvaluated) {
                actionBtnHtml = `
                    <button type="button" class="btn btn-sm btn-primary btn-step3-papar-vendor px-3 py-1 font-monospace d-inline-flex align-items-center gap-1"
                        data-vendor-id="${v.vendor_id}"
                        data-vendor-kod="${escapeHtml(kodDisplay)}"
                        data-vendor-status="${escapeHtml(bumiStatus)}"
                        data-harga="${escapeHtml(hargaFmt)}">
                        <i class="bi bi-eye"></i>
                        <span>Papar</span>
                    </button>
                `;
            } else {
                actionBtnHtml = `
                    <button type="button" class="btn btn-sm btn-success btn-step3-papar-vendor px-3 py-1 font-monospace d-inline-flex align-items-center gap-1"
                        data-vendor-id="${v.vendor_id}"
                        data-vendor-kod="${escapeHtml(kodDisplay)}"
                        data-vendor-status="${escapeHtml(bumiStatus)}"
                        data-harga="${escapeHtml(hargaFmt)}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Menilai</span>
                    </button>
                `;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace fw-medium">${escapeHtml(kodDisplay)}</td>
                <td class="text-center">${escapeHtml(bumiStatus)}</td>
                <td class="text-end fw-medium px-3">${escapeHtml(hargaFmt)}</td>
                <td class="text-center font-monospace fw-bold ${isEvaluated ? 'text-primary' : 'text-muted'}">${escapeHtml(skorDisplay)}</td>
                <td class="text-center">-</td>
                <td class="text-center">
                    ${actionBtnHtml}
                </td>
            `;
            tbody.appendChild(tr);
        });

        bindStep3VendorDetailButtons();
    }

    document.querySelectorAll('.btn-papar-cadangan-kewangan-step3').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dokumen = btn.getAttribute('data-dokumen') || '';
            const uuid = btn.getAttribute('data-uuid') || '';
            currentStep3ItemUuid = uuid;
            const titleEl = document.getElementById('step3ModalDokumenTitle');
            if (titleEl && dokumen) {
                titleEl.textContent = `${dokumen}.`;
            }
            renderStep3VendorTableModal(uuid);
        });
    });

    const firstModalEl = document.getElementById('modalPaparCadanganKewanganStep3');
    const secondModalEl = document.getElementById('modalPaparVendorCadanganKewanganStep3');
    const firstModal = firstModalEl ? bootstrap.Modal.getOrCreateInstance(firstModalEl) : null;
    const secondModal = secondModalEl ? bootstrap.Modal.getOrCreateInstance(secondModalEl) : null;

    function bindStep3VendorDetailButtons() {
        document.querySelectorAll('.btn-step3-papar-vendor').forEach(function(btn) {
            btn.removeEventListener('click', handleVendorDetailClick);
            btn.addEventListener('click', handleVendorDetailClick);
        });
    }

    function handleVendorDetailClick(e) {
        const btn = e.currentTarget;
        currentStep3VendorId = parseInt(btn.getAttribute('data-vendor-id')) || null;

        if (!currentStep3ItemUuid || !currentStep3VendorId || typeof SEMAK_PAYLOAD === 'undefined') return;

        const itemObj = SEMAK_PAYLOAD[currentStep3ItemUuid];
        if (!itemObj) return;

        const vendorObj = (itemObj.vendors || []).find(v => parseInt(v.vendor_id) === currentStep3VendorId);
        if (!vendorObj) return;

        const specDetail = itemObj.spesifikasi_detail || {};

        // 1. Populate Vendor Info
        const elKod = document.getElementById('step3VendorKod');
        const elDokumen = document.getElementById('step3VendorDokumenTitle');
        if (elKod) elKod.textContent = vendorObj.kod || ('Petender #' + currentStep3VendorId);
        if (elDokumen) elDokumen.textContent = itemObj.title || '-';

        // 2. Render Table Rows (Items & Sub-items)
        const tbody = document.getElementById('step3VendorDetailTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            let totalAnggaran = 0;
            let totalTawaran = 0;

            const rows = specDetail.rows || itemObj.admin_content?.rows || [];
            const pricingItems = specDetail.pricing_items || {};
            const itemPricesMap = vendorObj.item_prices || {};

            if (!rows.length) {
                const anggaranVal = parseFloat(specDetail.anggaran_jabatan || 0);
                const tawaranVal = parseFloat(vendorObj.harga_tawaran || 0);
                totalAnggaran = anggaranVal;
                totalTawaran = tawaranVal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-3 py-2.5 fw-semibold text-dark">${escapeHtml(itemObj.title)}</td>
                    <td class="text-center px-3 py-2.5">1</td>
                    <td class="text-center px-3 py-2.5">Activity Unit</td>
                    <td class="text-end px-3 py-2.5 font-monospace fw-medium">${anggaranVal > 0 ? anggaranVal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}</td>
                    <td class="text-end px-3 py-2.5 font-monospace fw-semibold text-dark">${tawaranVal > 0 ? tawaranVal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}</td>
                `;
                tbody.appendChild(tr);
            } else {
                const groups = [];
                let currGroup = null;
                rows.forEach(r => {
                    const kind = r.kind || 'item';
                    if (kind === 'item') {
                        if (currGroup) groups.push(currGroup);
                        currGroup = { item: r, details: [] };
                    } else if (currGroup) {
                        currGroup.details.push(r);
                    } else {
                        groups.push({ item: r, details: [] });
                    }
                });
                if (currGroup) groups.push(currGroup);

                groups.forEach((group, gIdx) => {
                    const item = group.item;
                    const itemUuid = item.item_uuid || item.uuid || '';
                    const qty = parseFloat(item.quantity) || (item.quantity === 0 ? 0 : 1);
                    const unitStr = item.unit || item.uom || 'Unit';

                    // Anggaran Jabatan (status_process_id = 3)
                    const pricingItem = pricingItems[itemUuid] || pricingItems[item.spec_item_id] || pricingItems[item.id] || pricingItems[item.uuid] || null;
                    let itemAnggaranTotal = pricingItem ? parseFloat(pricingItem.harga || 0) : 0;
                    if (!itemAnggaranTotal && groups.length === 1 && specDetail.anggaran_jabatan) {
                        itemAnggaranTotal = parseFloat(specDetail.anggaran_jabatan || 0);
                    }
                    totalAnggaran += itemAnggaranTotal;

                    // Tawaran Harga (status_process_id = 5)
                    let vendorSubmittedHarga = 0;
                    const possibleKeys = [
                        itemUuid,
                        item.spec_item_id,
                        item.id,
                        item.uuid,
                        (gIdx + 1),
                        (gIdx + 1).toString()
                    ];

                    for (const key of possibleKeys) {
                        if (key !== undefined && key !== null && key !== '' && itemPricesMap[key] !== undefined && itemPricesMap[key] !== null && itemPricesMap[key] !== '') {
                            const parsed = parseFloat(itemPricesMap[key]);
                            if (!isNaN(parsed) && parsed >= 0) {
                                vendorSubmittedHarga = parsed;
                                break;
                            }
                        }
                    }
                    if (!vendorSubmittedHarga && groups.length === 1 && vendorObj.harga_tawaran) {
                        vendorSubmittedHarga = parseFloat(vendorObj.harga_tawaran || 0);
                    }
                    if (isNaN(vendorSubmittedHarga)) vendorSubmittedHarga = 0;
                    totalTawaran += vendorSubmittedHarga;

                    const trItem = document.createElement('tr');
                    trItem.className = 'bg-light bg-opacity-50';
                    trItem.innerHTML = `
                        <td class="px-3 py-2.5 fw-bold text-dark">
                            <span class="badge bg-primary bg-opacity-10 text-primary me-2 font-monospace">${item.bil || (gIdx + 1)}</span>
                            ${escapeHtml(item.title || '-')}
                        </td>
                        <td class="text-center px-3 py-2.5 font-monospace">${qty}</td>
                        <td class="text-center px-3 py-2.5 text-uppercase font-monospace">${escapeHtml(unitStr)}</td>
                        <td class="text-end px-3 py-2.5 font-monospace fw-medium">${itemAnggaranTotal > 0 ? itemAnggaranTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}</td>
                        <td class="text-end px-3 py-2.5 font-monospace fw-semibold text-dark">${vendorSubmittedHarga > 0 ? vendorSubmittedHarga.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}</td>
                    `;
                    tbody.appendChild(trItem);

                    group.details.forEach((dt, dIdx) => {
                        const trDetail = document.createElement('tr');
                        trDetail.innerHTML = `
                            <td colspan="5" class="ps-4 py-2 text-secondary small">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-arrow-return-right text-primary opacity-75"></i>
                                    <span class="fw-semibold text-dark me-1">${dt.bil || (gIdx + 1 + '.' + (dIdx + 1))}</span>
                                    <span>${escapeHtml(dt.title || '-')}</span>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(trDetail);
                    });
                });
            }

            const elTotalAnggaran = document.getElementById('step3TotalAnggaranJabatan');
            const elTotalTawaran = document.getElementById('step3TotalTawaranHarga');
            if (elTotalAnggaran) elTotalAnggaran.textContent = totalAnggaran > 0 ? 'RM ' + totalAnggaran.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'RM 0.00';
            if (elTotalTawaran) elTotalTawaran.textContent = totalTawaran > 0 ? 'RM ' + totalTawaran.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'RM 0.00';
        }

        // 3. Dynamic Max Score & Pre-fill Skor & Catatan
        const maxScore = parseFloat(specDetail.max_score || itemObj.max_score || 100);
        const fmtMax = (maxScore % 1 === 0) ? maxScore.toFixed(0) : maxScore.toFixed(2);

        const elSkorInput = document.getElementById('step3VendorSkorInput');
        const elMaxLabel = document.getElementById('step3VendorMaxSkorLabel');
        const elCatatanInput = document.getElementById('step3VendorCatatanInput');

        if (elMaxLabel) elMaxLabel.textContent = '/ ' + fmtMax;
        if (elSkorInput) {
            elSkorInput.setAttribute('max', maxScore);
            elSkorInput.value = (vendorObj.skor !== null && vendorObj.skor !== undefined && vendorObj.skor !== '') ? vendorObj.skor : '';
            elSkorInput.oninput = function() {
                let val = parseFloat(this.value);
                if (!isNaN(val) && val > maxScore) {
                    this.value = maxScore;
                }
            };
        }
        if (elCatatanInput) elCatatanInput.value = vendorObj.catatan || '';

        // Show Second Modal
        if (firstModal && secondModal) {
            firstModalEl.addEventListener('hidden.bs.modal', function handleHidden() {
                secondModal.show();
                firstModalEl.removeEventListener('hidden.bs.modal', handleHidden);
            });
            firstModal.hide();
        } else if (secondModal) {
            secondModal.show();
        }
    }

    // Save Evaluation (Simpan Penilaian) Handler
    const btnSimpan = document.getElementById('btnSimpanPenilaianStep3');
    if (btnSimpan) {
        btnSimpan.addEventListener('click', function() {
            if (!currentStep3ItemUuid || !currentStep3VendorId) return;

            const skorVal = parseFloat(document.getElementById('step3VendorSkorInput')?.value);
            const catatanVal = document.getElementById('step3VendorCatatanInput')?.value || '';

            const itemObj = SEMAK_PAYLOAD[currentStep3ItemUuid] || {};
            const specDetail = itemObj.spesifikasi_detail || {};
            const maxScore = parseFloat(specDetail.max_score || itemObj.max_score || 100);
            const fmtMax = (maxScore % 1 === 0) ? maxScore.toFixed(0) : maxScore.toFixed(2);

            if (isNaN(skorVal) || skorVal < 0 || skorVal > maxScore) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Ralat Validation Skor',
                        text: `Skor tidak boleh melebihi skor maksimum (${fmtMax}). Sila masukkan skor antara 0 dan ${fmtMax}.`,
                        icon: 'warning',
                        confirmButtonColor: '#1e293b'
                    });
                } else {
                    alert(`Skor tidak boleh melebihi skor maksimum (${fmtMax}).`);
                }
                return;
            }

            const tenderId = '{{ $tender->id }}';

            if (typeof $ !== 'undefined') {
                $.ajax({
                    url: '{{ route("penilaianKewangan.simpanPematuhan") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tender: tenderId,
                        vendor_id: currentStep3VendorId,
                        checklist_item_uuid: currentStep3ItemUuid,
                        status_pematuhan: 1,
                        skor: skorVal,
                        catatan: catatanVal,
                        step: 3
                    },
                    success: function(resp) {
                        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD[currentStep3ItemUuid]) {
                            const vRow = SEMAK_PAYLOAD[currentStep3ItemUuid].vendors.find(v => parseInt(v.vendor_id) === currentStep3VendorId);
                            if (vRow) {
                                vRow.skor = skorVal;
                                vRow.catatan = catatanVal;
                                vRow.status_pematuhan = 'mematuhi';
                                vRow.step3_evaluated = true;
                            }
                        }

                        if (secondModal) {
                            secondModal.hide();
                        }

                        setTimeout(function() {
                            if (firstModal) {
                                firstModal.show();
                            }
                            renderStep3VendorTableModal(currentStep3ItemUuid);
                            updateMainTableStatusPenilaian(currentStep3ItemUuid);
                        }, 300);

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Berjaya!',
                                text: 'Penilaian pematuhan spesifikasi telah disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#1e293b'
                            });
                        } else {
                            alert('Penilaian pematuhan spesifikasi telah disimpan.');
                        }
                    },
                    error: function(err) {
                        const msg = err.responseJSON?.message || 'Gagal menyimpan penilaian spesifikasi.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Ralat',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#1e293b'
                            });
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    }

    // ----------------------------------------------------
    // PROFIL PETENDER (BORANG ATAS TALIAN) STEP 3 LOGIC
    // ----------------------------------------------------
    const profilModal1El = document.getElementById('modalPaparProfilPetenderStep3');
    const profilModal2El = document.getElementById('modalPaparVendorProfilPetenderStep3');
    const profilModal1 = profilModal1El ? bootstrap.Modal.getOrCreateInstance(profilModal1El) : null;
    const profilModal2 = profilModal2El ? bootstrap.Modal.getOrCreateInstance(profilModal2El) : null;

    document.querySelectorAll('.btn-papar-profil-petender-step3').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dokumen = btn.getAttribute('data-dokumen') || 'Maklumat Profil Petender';
            const uuid = btn.getAttribute('data-uuid') || '';
            currentStep3ItemUuid = uuid;
            const titleEl = document.getElementById('step3ProfilPetenderModalDokumenTitle');
            if (titleEl) {
                titleEl.textContent = dokumen;
            }
            renderStep3ProfilPetenderVendorTableModal(uuid);
        });
    });

    function renderStep3ProfilPetenderVendorTableModal(itemUuid) {
        const tbody = document.getElementById('step3ProfilPetenderVendorTableBody');
        if (!tbody) return;

        const failedVendorIds = getFailedVendorIdsStep3();
        let rawVendors = [];

        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid && SEMAK_PAYLOAD[itemUuid]) {
            rawVendors = SEMAK_PAYLOAD[itemUuid].vendors || [];
        } else if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD) {
            const firstKey = Object.keys(SEMAK_PAYLOAD)[0];
            if (firstKey && SEMAK_PAYLOAD[firstKey]) {
                rawVendors = SEMAK_PAYLOAD[firstKey].vendors || [];
            }
        }

        const itemObj = (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid) ? SEMAK_PAYLOAD[itemUuid] : null;
        const profilDetail = itemObj ? (itemObj.profil_petender_detail || {}) : {};
        const maxBerbayar = parseFloat(profilDetail.max_modal_berbayar || 0);
        const maxDibenarkan = parseFloat(profilDetail.max_modal_dibenarkan || 0);

        const fmtMaxBerbayar = (maxBerbayar % 1 === 0) ? maxBerbayar.toFixed(0) : maxBerbayar.toFixed(2);
        const fmtMaxDibenarkan = (maxDibenarkan % 1 === 0) ? maxDibenarkan.toFixed(0) : maxDibenarkan.toFixed(2);

        const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

        if (!eligibleVendors.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-info-circle me-1"></i>Tiada petender yang melepasi penilaian kewangan setakat ini.</td></tr>';
            return;
        }

        tbody.innerHTML = '';
        eligibleVendors.forEach((v, idx) => {
            const kodDisplay = v.kod ? v.kod : ((idx + 1) + '/' + eligibleVendors.length);

            const isEvaluated = (v.skor !== null && v.skor !== undefined && v.skor !== '');
            let skorBerbayarDisplay = '-';
            let skorDibenarkanDisplay = '-';

            if (isEvaluated) {
                const sBerbayar = parseFloat(v.skor_modal_berbayar ?? 0);
                const sDibenarkan = parseFloat(v.skor_modal_dibenarkan ?? 0);
                const fmtSB = (sBerbayar % 1 === 0) ? sBerbayar.toFixed(0) : sBerbayar.toFixed(2);
                const fmtSD = (sDibenarkan % 1 === 0) ? sDibenarkan.toFixed(0) : sDibenarkan.toFixed(2);
                skorBerbayarDisplay = `${fmtSB}/${fmtMaxBerbayar}`;
                skorDibenarkanDisplay = `${fmtSD}/${fmtMaxDibenarkan}`;
            }

            let actionBtnHtml = '';
            if (isEvaluated) {
                actionBtnHtml = `
                    <button type="button" class="btn btn-sm btn-primary btn-step3-papar-profil-vendor px-3 py-1 font-monospace d-inline-flex align-items-center gap-1"
                        data-vendor-id="${v.vendor_id}"
                        data-vendor-kod="${escapeHtml(kodDisplay)}">
                        <i class="bi bi-eye"></i>
                        <span>Papar</span>
                    </button>
                `;
            } else {
                actionBtnHtml = `
                    <button type="button" class="btn btn-sm btn-success btn-step3-papar-profil-vendor px-3 py-1 font-monospace d-inline-flex align-items-center gap-1"
                        data-vendor-id="${v.vendor_id}"
                        data-vendor-kod="${escapeHtml(kodDisplay)}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Menilai</span>
                    </button>
                `;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace fw-medium">${escapeHtml(kodDisplay)}</td>
                <td class="text-center fw-medium text-dark">Maklumat Profil Petender</td>
                <td class="text-center font-monospace fw-bold ${isEvaluated ? 'text-primary' : 'text-muted'}">${escapeHtml(skorBerbayarDisplay)}</td>
                <td class="text-center font-monospace fw-bold ${isEvaluated ? 'text-primary' : 'text-muted'}">${escapeHtml(skorDibenarkanDisplay)}</td>
                <td class="text-center">${actionBtnHtml}</td>
            `;
            tbody.appendChild(tr);
        });

        bindStep3ProfilVendorButtons();
    }

    function bindStep3ProfilVendorButtons() {
        document.querySelectorAll('.btn-step3-papar-profil-vendor').forEach(function(btn) {
            btn.removeEventListener('click', handleVendorProfilDetailClick);
            btn.addEventListener('click', handleVendorProfilDetailClick);
        });
    }

    function handleVendorProfilDetailClick(e) {
        const btn = e.currentTarget;
        currentStep3VendorId = parseInt(btn.getAttribute('data-vendor-id')) || null;

        if (!currentStep3ItemUuid || !currentStep3VendorId || typeof SEMAK_PAYLOAD === 'undefined') return;

        const itemObj = SEMAK_PAYLOAD[currentStep3ItemUuid];
        if (!itemObj) return;

        const vendorObj = (itemObj.vendors || []).find(v => parseInt(v.vendor_id) === currentStep3VendorId);
        if (!vendorObj) return;

        const profilDetail = itemObj.profil_petender_detail || {};
        const vendorPayloads = profilDetail.vendor_payloads || {};
        const vData = vendorPayloads[currentStep3VendorId] || {};

        // 1. Populate Summary Banner
        const elKod = document.getElementById('step3ProfilVendorKod');
        const elNamaSyarikatBanner = document.getElementById('step3ProfilVendorNamaSyarikat');
        if (elKod) elKod.textContent = vendorObj.kod || ('Petender #' + currentStep3VendorId);
        if (elNamaSyarikatBanner) elNamaSyarikatBanner.textContent = vData.nama_syarikat || vendorObj.name || '-';

        // 2. Populate Read-Only Form Fields
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = (val !== null && val !== undefined && val !== '') ? val : '-';
        };

        setVal('viewProfilNamaSyarikat', vData.nama_syarikat || vendorObj.name);
        setVal('viewProfilJenisSyarikat', (vData.jenis_syarikat || '-').toUpperCase().replace('_', ' '));
        setVal('viewProfilTarafPetender', (vData.taraf_petender || vendorObj.bumiputera_status || '-').toUpperCase());
        setVal('viewProfilNoSSM', vData.no_ssm);
        setVal('viewProfilNoMOF', vData.no_mof);
        setVal('viewProfilTempohMOF', vData.tempoh_sah_mof);
        setVal('viewProfilAlamat', vData.alamat);
        setVal('viewProfilPegawaiNama', vData.pegawai_nama);
        setVal('viewProfilPegawaiTel', vData.pegawai_telefon);
        setVal('viewProfilPegawaiEmel', vData.pegawai_emel);
        setVal('viewProfilBilPekerja', vData.bil_pekerja);
        setVal('viewProfilBilPekerjaTeknikal', vData.bil_pekerja_teknikal);

        const mBerbayar = parseFloat(vData.modal_berbayar || 0);
        const mDibenarkan = parseFloat(vData.modal_dibenarkan || 0);
        setVal('viewProfilModalBerbayar', mBerbayar > 0 ? 'RM ' + mBerbayar.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'RM 0.00');
        setVal('viewProfilModalDibenarkan', mDibenarkan > 0 ? 'RM ' + mDibenarkan.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'RM 0.00');

        // 3. Render Modal Berbayar Scoring Reference Table
        const tbodyBerbayar = document.getElementById('step3ModalBerbayarScoringTbody');
        const berbayarItems = profilDetail.modal_berbayar_items || [];
        const maxBerbayar = parseFloat(profilDetail.max_modal_berbayar || 0);
        const fmtMaxBerbayar = (maxBerbayar % 1 === 0) ? maxBerbayar.toFixed(0) : maxBerbayar.toFixed(2);

        if (tbodyBerbayar) {
            if (!berbayarItems.length) {
                tbodyBerbayar.innerHTML = '<tr><td colspan="2" class="text-center text-muted py-2">Tiada skema julat dikonfigurasikan.</td></tr>';
            } else {
                tbodyBerbayar.innerHTML = '';
                berbayarItems.forEach(item => {
                    const dari = parseFloat(item.dari || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    const hingga = item.hingga ? parseFloat(item.hingga).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'dan ke atas';
                    const julatStr = `RM ${dari} hingga ${hingga === 'dan ke atas' ? 'ke atas' : 'RM ' + hingga}`;
                    const skemaVal = parseFloat(item.skema || 0);
                    const fmtSkema = (skemaVal % 1 === 0) ? skemaVal.toFixed(0) : skemaVal.toFixed(2);

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-3 py-2 fw-medium text-dark">${escapeHtml(julatStr)}</td>
                        <td class="text-center px-3 py-2 font-monospace fw-bold text-success">${fmtSkema}</td>
                    `;
                    tbodyBerbayar.appendChild(tr);
                });
            }
        }

        const elBerbayarInput = document.getElementById('step3ModalBerbayarSkorInput');
        const elBerbayarMaxLabel = document.getElementById('step3ModalBerbayarMaxLabel');
        if (elBerbayarMaxLabel) elBerbayarMaxLabel.textContent = '/ ' + fmtMaxBerbayar;
        if (elBerbayarInput) {
            elBerbayarInput.setAttribute('max', maxBerbayar);
            elBerbayarInput.value = (vendorObj.skor_modal_berbayar !== null && vendorObj.skor_modal_berbayar !== undefined && vendorObj.skor_modal_berbayar !== '') ? vendorObj.skor_modal_berbayar : '';
            elBerbayarInput.oninput = function() {
                let val = parseFloat(this.value);
                if (!isNaN(val) && val > maxBerbayar) {
                    this.value = maxBerbayar;
                }
            };
        }

        // 4. Render Modal Dibenarkan Scoring Reference Table
        const tbodyDibenarkan = document.getElementById('step3ModalDibenarkanScoringTbody');
        const dibenarkanItems = profilDetail.modal_dibenarkan_items || [];
        const maxDibenarkan = parseFloat(profilDetail.max_modal_dibenarkan || 0);
        const fmtMaxDibenarkan = (maxDibenarkan % 1 === 0) ? maxDibenarkan.toFixed(0) : maxDibenarkan.toFixed(2);

        if (tbodyDibenarkan) {
            if (!dibenarkanItems.length) {
                tbodyDibenarkan.innerHTML = '<tr><td colspan="2" class="text-center text-muted py-2">Tiada skema julat dikonfigurasikan.</td></tr>';
            } else {
                tbodyDibenarkan.innerHTML = '';
                dibenarkanItems.forEach(item => {
                    const dari = parseFloat(item.dari || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    const hingga = item.hingga ? parseFloat(item.hingga).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 'dan ke atas';
                    const julatStr = `RM ${dari} hingga ${hingga === 'dan ke atas' ? 'ke atas' : 'RM ' + hingga}`;
                    const skemaVal = parseFloat(item.skema || 0);
                    const fmtSkema = (skemaVal % 1 === 0) ? skemaVal.toFixed(0) : skemaVal.toFixed(2);

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-3 py-2 fw-medium text-dark">${escapeHtml(julatStr)}</td>
                        <td class="text-center px-3 py-2 font-monospace fw-bold text-info">${fmtSkema}</td>
                    `;
                    tbodyDibenarkan.appendChild(tr);
                });
            }
        }

        const elDibenarkanInput = document.getElementById('step3ModalDibenarkanSkorInput');
        const elDibenarkanMaxLabel = document.getElementById('step3ModalDibenarkanMaxLabel');
        if (elDibenarkanMaxLabel) elDibenarkanMaxLabel.textContent = '/ ' + fmtMaxDibenarkan;
        if (elDibenarkanInput) {
            elDibenarkanInput.setAttribute('max', maxDibenarkan);
            elDibenarkanInput.value = (vendorObj.skor_modal_dibenarkan !== null && vendorObj.skor_modal_dibenarkan !== undefined && vendorObj.skor_modal_dibenarkan !== '') ? vendorObj.skor_modal_dibenarkan : '';
            elDibenarkanInput.oninput = function() {
                let val = parseFloat(this.value);
                if (!isNaN(val) && val > maxDibenarkan) {
                    this.value = maxDibenarkan;
                }
            };
        }

        // 5. Catatan Penilai
        const elProfilCatatan = document.getElementById('step3VendorProfilCatatanInput');
        if (elProfilCatatan) elProfilCatatan.value = vendorObj.catatan || '';

        // Transition from Modal 1 to Modal 2
        if (profilModal1 && profilModal2) {
            profilModal1El.addEventListener('hidden.bs.modal', function handleHidden() {
                profilModal2.show();
                profilModal1El.removeEventListener('hidden.bs.modal', handleHidden);
            });
            profilModal1.hide();
        } else if (profilModal2) {
            profilModal2.show();
        }
    }

    // Save Evaluation (Simpan Penilaian) Handler for Profil Petender
    const btnSimpanProfil = document.getElementById('btnSimpanPenilaianProfilStep3');
    if (btnSimpanProfil) {
        btnSimpanProfil.addEventListener('click', function() {
            if (!currentStep3ItemUuid || !currentStep3VendorId) return;

            const skorBerbayarVal = parseFloat(document.getElementById('step3ModalBerbayarSkorInput')?.value);
            const skorDibenarkanVal = parseFloat(document.getElementById('step3ModalDibenarkanSkorInput')?.value);
            const catatanVal = document.getElementById('step3VendorProfilCatatanInput')?.value || '';

            const itemObj = SEMAK_PAYLOAD[currentStep3ItemUuid] || {};
            const profilDetail = itemObj.profil_petender_detail || {};
            const maxBerbayar = parseFloat(profilDetail.max_modal_berbayar || 0);
            const maxDibenarkan = parseFloat(profilDetail.max_modal_dibenarkan || 0);

            const fmtMaxB = (maxBerbayar % 1 === 0) ? maxBerbayar.toFixed(0) : maxBerbayar.toFixed(2);
            const fmtMaxD = (maxDibenarkan % 1 === 0) ? maxDibenarkan.toFixed(0) : maxDibenarkan.toFixed(2);

            if (isNaN(skorBerbayarVal) || skorBerbayarVal < 0 || (maxBerbayar > 0 && skorBerbayarVal > maxBerbayar)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Ralat Validation Skor',
                        text: `Skor Modal Berbayar tidak boleh melebihi skor maksimum (${fmtMaxB}).`,
                        icon: 'warning',
                        confirmButtonColor: '#1e293b'
                    });
                } else {
                    alert(`Skor Modal Berbayar tidak boleh melebihi skor maksimum (${fmtMaxB}).`);
                }
                return;
            }

            if (isNaN(skorDibenarkanVal) || skorDibenarkanVal < 0 || (maxDibenarkan > 0 && skorDibenarkanVal > maxDibenarkan)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Ralat Validation Skor',
                        text: `Skor Modal Dibenarkan tidak boleh melebihi skor maksimum (${fmtMaxD}).`,
                        icon: 'warning',
                        confirmButtonColor: '#1e293b'
                    });
                } else {
                    alert(`Skor Modal Dibenarkan tidak boleh melebihi skor maksimum (${fmtMaxD}).`);
                }
                return;
            }

            const tenderId = '{{ $tender->id }}';

            if (typeof $ !== 'undefined') {
                $.ajax({
                    url: '{{ route("penilaianKewangan.simpanPematuhan") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tender: tenderId,
                        vendor_id: currentStep3VendorId,
                        checklist_item_uuid: currentStep3ItemUuid,
                        status_pematuhan: 1,
                        skor_modal_berbayar: skorBerbayarVal,
                        skor_modal_dibenarkan: skorDibenarkanVal,
                        max_modal_berbayar: maxBerbayar,
                        max_modal_dibenarkan: maxDibenarkan,
                        catatan: catatanVal,
                        step: 3
                    },
                    success: function(resp) {
                        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD[currentStep3ItemUuid]) {
                            const vRow = SEMAK_PAYLOAD[currentStep3ItemUuid].vendors.find(v => parseInt(v.vendor_id) === currentStep3VendorId);
                            if (vRow) {
                                vRow.skor_modal_berbayar = skorBerbayarVal;
                                vRow.skor_modal_dibenarkan = skorDibenarkanVal;
                                vRow.skor = skorBerbayarVal + skorDibenarkanVal;
                                vRow.catatan = catatanVal;
                                vRow.status_pematuhan = 'mematuhi';
                                vRow.step3_evaluated = true;
                            }
                        }

                        if (profilModal2) {
                            profilModal2.hide();
                        }

                        setTimeout(function() {
                            if (profilModal1) {
                                profilModal1.show();
                            }
                            renderStep3ProfilPetenderVendorTableModal(currentStep3ItemUuid);
                            updateMainTableStatusPenilaian(currentStep3ItemUuid);
                        }, 300);

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Berjaya!',
                                text: 'Penilaian maklumat profil petender telah disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#1e293b'
                            });
                        } else {
                            alert('Penilaian maklumat profil petender telah disimpan.');
                        }
                    },
                    error: function(err) {
                        const msg = err.responseJSON?.message || 'Gagal menyimpan penilaian profil petender.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Ralat',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#1e293b'
                            });
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    }

    // ----------------------------------------------------
    // MUAT NAIK STEP 3 LOGIC
    // ----------------------------------------------------
    const muatNaikModalEl = document.getElementById('modalMuatNaikStep3');
    const muatNaikModal = muatNaikModalEl ? bootstrap.Modal.getOrCreateInstance(muatNaikModalEl) : null;

    document.querySelectorAll('.btn-papar-muat-naik-step3').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dokumen = btn.getAttribute('data-dokumen') || 'Item Kewangan (Muat Naik)';
            const uuid = btn.getAttribute('data-uuid') || '';
            currentStep3ItemUuid = uuid;
            const titleEl = document.getElementById('step3MuatNaikModalDokumenTitle');
            if (titleEl) {
                titleEl.textContent = dokumen;
            }
            renderStep3MuatNaikVendorTableModal(uuid);
        });
    });

    function renderStep3MuatNaikVendorTableModal(itemUuid) {
        const tbody = document.getElementById('step3MuatNaikVendorTableBody');
        if (!tbody) return;

        const failedVendorIds = getFailedVendorIdsStep3();
        let rawVendors = [];

        if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid && SEMAK_PAYLOAD[itemUuid]) {
            rawVendors = SEMAK_PAYLOAD[itemUuid].vendors || [];
        } else if (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD) {
            const firstKey = Object.keys(SEMAK_PAYLOAD)[0];
            if (firstKey && SEMAK_PAYLOAD[firstKey]) {
                rawVendors = SEMAK_PAYLOAD[firstKey].vendors || [];
            }
        }

        const itemObj = (typeof SEMAK_PAYLOAD !== 'undefined' && SEMAK_PAYLOAD && itemUuid) ? SEMAK_PAYLOAD[itemUuid] : null;
        const maxScore = parseFloat(itemObj?.max_score || 10);
        const fmtMax = (maxScore % 1 === 0) ? maxScore.toFixed(0) : maxScore.toFixed(2);

        const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

        if (!eligibleVendors.length) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4"><i class="bi bi-info-circle me-1"></i>Tiada petender yang melepasi penilaian kewangan setakat ini.</td></tr>';
            return;
        }

        const allEvaluated = eligibleVendors.length > 0 && eligibleVendors.every(v => !!v.step3_evaluated);
        const noteAlert = document.getElementById('step3MuatNaikNoteAlert');
        if (noteAlert) {
            if (allEvaluated) {
                noteAlert.classList.add('d-none');
            } else {
                noteAlert.classList.remove('d-none');
            }
        }

        tbody.innerHTML = '';
        eligibleVendors.forEach((v, idx) => {
            const kodDisplay = v.kod ? v.kod : ((idx + 1) + '/' + eligibleVendors.length);

            // Document Display Logic
            let docHtml = '<span class="text-muted small">Tiada Fail</span>';
            const files = v.files || [];
            if (files.length > 0) {
                docHtml = files.map(f => {
                    const fUrl = f.url || f.path || '#';
                    const fName = f.original_name || f.name || f.filename || 'Dokumen';
                    const ext = (fName.split('.').pop() || '').toLowerCase();

                    if (ext === 'pdf') {
                        return `<button type="button" class="btn btn-sm btn-link text-decoration-none p-0 text-start text-primary fw-semibold me-2" onclick="openPdfPreview('${escapeHtml(fUrl)}', '${escapeHtml(fName)}');">
                            <i class="bi bi-file-earmark-pdf text-danger me-1 fs-6"></i>${escapeHtml(fName)}
                        </button>`;
                    } else {
                        return `<a href="${escapeHtml(fUrl)}" download="${escapeHtml(fName)}" class="btn btn-sm btn-outline-secondary py-0 px-2 font-monospace me-2" style="font-size: 0.75rem;">
                            <i class="bi bi-download me-1"></i>${escapeHtml(fName)}
                        </a>`;
                    }
                }).join('<br>');
            }

            // Score Logic: Based on Step 1 status_pematuhan
            const isMematuhi = (v.status_pematuhan === 'mematuhi' || v.status_pematuhan === 1 || v.status_pematuhan === '1');
            const scoreVal = isMematuhi ? maxScore : 0;
            const fmtScore = (scoreVal % 1 === 0) ? scoreVal.toFixed(0) : scoreVal.toFixed(2);
            const scoreDisplay = `${fmtScore}/${fmtMax}`;
            const badgeClass = isMematuhi ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-20' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace fw-medium">${escapeHtml(kodDisplay)}</td>
                <td class="text-center">${docHtml}</td>
                <td class="text-center">
                    <span class="badge ${badgeClass} font-monospace px-3 py-1.5 fs-6 fw-bold">
                        ${escapeHtml(scoreDisplay)}
                    </span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Save Evaluation (Simpan Penilaian) Handler for Muat Naik
    const btnSimpanMuatNaik = document.getElementById('btnSimpanPenilaianMuatNaikStep3');
    if (btnSimpanMuatNaik) {
        btnSimpanMuatNaik.addEventListener('click', function() {
            if (!currentStep3ItemUuid || typeof SEMAK_PAYLOAD === 'undefined') return;

            const itemObj = SEMAK_PAYLOAD[currentStep3ItemUuid];
            if (!itemObj) return;

            const failedVendorIds = getFailedVendorIdsStep3();
            const rawVendors = itemObj.vendors || [];
            const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

            if (!eligibleVendors.length) return;

            const maxScore = parseFloat(itemObj.max_score || 10);
            const tenderId = '{{ $tender->id }}';

            let savedCount = 0;
            let hasError = false;

            eligibleVendors.forEach(v => {
                const isMematuhi = (v.status_pematuhan === 'mematuhi' || v.status_pematuhan === 1 || v.status_pematuhan === '1');
                const scoreVal = isMematuhi ? maxScore : 0;

                if (typeof $ !== 'undefined') {
                    $.ajax({
                        url: '{{ route("penilaianKewangan.simpanPematuhan") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            tender: tenderId,
                            vendor_id: v.vendor_id,
                            checklist_item_uuid: currentStep3ItemUuid,
                            status_pematuhan: isMematuhi ? 1 : 0,
                            skor: scoreVal,
                            catatan: v.catatan || '',
                            step: 3
                        },
                        success: function(resp) {
                            savedCount++;
                            v.skor = scoreVal;
                            v.status_pematuhan = isMematuhi ? 'mematuhi' : 'tidak_mematuhi';
                            v.step3_evaluated = true;

                            if (savedCount === eligibleVendors.length) {
                                finishMuatNaikSave();
                            }
                        },
                        error: function(err) {
                            hasError = true;
                            savedCount++;
                            if (savedCount === eligibleVendors.length) {
                                finishMuatNaikSave();
                            }
                        }
                    });
                }
            });

            function finishMuatNaikSave() {
                if (muatNaikModal) {
                    muatNaikModal.hide();
                }
                updateMainTableStatusPenilaian(currentStep3ItemUuid);
                renderStep3MuatNaikVendorTableModal(currentStep3ItemUuid);

                if (!hasError && typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berjaya!',
                        text: 'Penilaian muat naik kewangan telah disimpan.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e293b'
                    });
                } else if (!hasError) {
                    alert('Penilaian muat naik kewangan telah disimpan.');
                }
            }
        });
    }

    // PDF Preview Helper Function
    window.openPdfPreview = function(url, title) {
        const modalPreviewEl = document.getElementById('modalPreview');
        if (!modalPreviewEl) {
            window.open(url, '_blank');
            return;
        }
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalPreviewEl);
        const titleEl = document.getElementById('modalPreviewTitle');
        const iframeEl = document.getElementById('previewIframe');
        const spinnerEl = document.getElementById('previewSpinner');
        const newTabBtn = document.getElementById('btnNewTabPreview');
        const fallbackEl = document.getElementById('previewFallback');
        const imageWrapper = document.getElementById('previewImageWrapper');

        if (titleEl) titleEl.textContent = title || 'Prebiu Dokumen';
        if (newTabBtn) newTabBtn.href = url;
        if (fallbackEl) fallbackEl.classList.add('d-none');
        if (imageWrapper) imageWrapper.classList.add('d-none');

        if (iframeEl && spinnerEl) {
            spinnerEl.classList.remove('d-none');
            iframeEl.classList.add('d-none');
            iframeEl.src = url;
            iframeEl.onload = function() {
                spinnerEl.classList.add('d-none');
                iframeEl.classList.remove('d-none');
            };
        }

        modalInstance.show();
    };

    bindStep3VendorDetailButtons();

    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penyata-bank-tab');
            if (tab) tab.click();
        });
    });

    // ----------------------------------------------------
    // STEP 3 RUMUSAN NAVIGATION & CONFIRMATION LOGIC
    // ----------------------------------------------------
    document.querySelectorAll('.step3-rumusan-pane .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penilaian-3-tab');
            if (tab) tab.click();
        });
    });

    document.querySelectorAll('.step3-rumusan-pane .btn-seterusnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const confirmCheckbox = document.getElementById('confirmLayakStep3');
            if (confirmCheckbox && !confirmCheckbox.checked) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Pengesahan Diperlukan',
                        text: 'Sila tandakan pengesahan bahawa petender yang melepasi layak untuk disyorkan.',
                        icon: 'warning',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#1e293b'
                    });
                } else {
                    alert('Sila tandakan pengesahan bahawa petender yang melepasi layak untuk disyorkan.');
                }
                return;
            }

            if (typeof $ !== 'undefined') {
                $.ajax({
                    url: '{{ route("penilaianKewangan.kemaskiniLangkah") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tender: '{{ $tender->id }}',
                        step: 3,
                        confirmed: true,
                        target_step: 4
                    },
                    success: function(resp) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Berjaya!',
                                text: 'Penilaian Spesifikasi Kewangan (Langkah 3) telah disahkan.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#1e293b'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert('Penilaian Spesifikasi Kewangan (Langkah 3) telah disahkan.');
                            window.location.reload();
                        }
                    },
                    error: function(err) {
                        const msg = err.responseJSON?.message || 'Gagal mengesahkan Penilaian Spesifikasi Kewangan.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Ralat',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#1e293b'
                            });
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });
});
</script>

