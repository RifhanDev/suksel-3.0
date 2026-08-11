{{-- Step 2 (partial): Penyata Bulanan Bank — Kewangan & Rumusan --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-2" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-2" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content mt-4">
    <div class="tab-pane fade show active" id="kewangan-2" role="tabpanel" aria-labelledby="kewangan-2-tab">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary-subtle p-2 rounded-2 me-3">
                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Penyata Bulanan Bank</h5>
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
                <p class="mb-0 small">Klik butang <strong>Papar</strong> untuk melihat dokumen dan menjalankan semakan.</p>
            </div>
        </div>
        <div class="table-responsive mb-3 rounded-3 shadow-sm border bg-white">
            <table class="table table-hover align-middle mb-0 w-100">
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
                    @forelse($penyataBankItems ?? [] as $index => $item)
                        @php
                            $itemTitle = $item['title'] ?? $item['nama'] ?? 'Penyata Bulanan Bank';
                            $itemMekanisma = $item['tindakan'] ?? $item['mekanisma'] ?? 'Borang Atas Talian';
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

                            $reviewedCount = $eligibleVendors->filter(function($v) {
                                return $v['status_pematuhan'] !== null && $v['status_pematuhan'] !== '';
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
                                <button type="button" class="btn btn-sm btn-success btn-papar-cadangan-kewangan-step2 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#modalPenilaianCadanganKewanganStep2"
                                    data-dokumen="{{ $itemTitle }}"
                                    data-uuid="{{ $itemUuid }}">
                                    <i class="bi bi-eye"></i>
                                    <span>Papar</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tiada item Penyata Bulanan Bank dijumpai.</td>
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

    <div class="tab-pane fade" id="rumusan-2" role="tabpanel" aria-labelledby="rumusan-2-tab">
        <div class="container-fluid mt-3 px-0">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-clipboard-data text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan</h5>
                    <p class="text-secondary small mb-0">Rumusan keseluruhan bagi penilaian penyata bulanan bank.</p>
                </div>
            </div>

            <!-- SECTION 1: Pembekal Melepasi Penyata Bulanan Bank -->
            <div class="mb-2 mt-2">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-check-circle text-success me-2"></i>SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PENYATA BULANAN BANK
                </h6>
                <div class="small text-muted mt-1" id="step2TotalMelepasiText">0 pembekal melepasi</div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                </tr>
                            </thead>
                            <tbody id="step2RumusanMelepasiTableBody">
                                <tr>
                                    <td class="text-center text-muted py-4" colspan="2" style="font-size: 0.875rem;">
                                        <i class="bi bi-inbox me-1 fs-5"></i>Tiada pembekal melepasi lagi buat masa ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pengesahan Akhir Card -->
            <div class="card bg-light border-0 shadow-none mt-3 mb-4 rounded-3">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Pengesahan Akhir</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep2" name="confirm_layak_step2">
                        <label class="form-check-label small fw-medium" for="confirmLayakStep2">
                            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Pembekal Tidak Melepasi Penyata Bulanan Bank -->
            <div class="mb-2 mt-4">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-exclamation-circle text-danger me-2"></i>SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PENYATA BULANAN BANK
                </h6>
                <div class="small text-muted mt-1" id="step2TotalTidakMelepasiText">0 pembekal tidak melepasi</div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                </tr>
                            </thead>
                            <tbody id="step2RumusanTidakMelepasiTableBody">
                                <tr>
                                    <td class="text-center text-muted py-4" colspan="2" style="font-size: 0.875rem;">
                                        <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mb-3 mt-4">
                <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                    <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 1 (Step 2): Senarai Pembekal penyata bank — Papar baris membuka modal penilaian penyata --}}
<div class="modal fade" id="modalPenilaianCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-3">
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
                        <h6 id="modalCadanganKewanganTajukStep2" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</h6>
                    </div>
                    <div class="mx-3 align-self-stretch" style="width: 1px; background: #d1d5db;"></div>
                    <span class="text-secondary" style="font-size: 0.78rem;">Senarai dokumen yang perlu dikemukakan oleh petender.</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive rounded-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 15%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Bil</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 22%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Jumlah Skor</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 28%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Status Penilaian</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 35%; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="modalStep2VendorTableBody">
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Papar senarai pembekal...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mt-3" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    Pastikan semua senarai semak telah dinilai dan butang <strong>Menilai</strong> telah bertukar kepada <strong>Lihat</strong>.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal"><i class="bi bi-arrow-left-circle me-2"></i>Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2: Butiran penilaian penyata bulanan bank (selepas Papar pada senarai pembekal) --}}
<div class="modal fade" id="modalButiranCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelButiranCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 border-0">
                <div class="d-flex align-items-center rounded-3 mt-1">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: #dbeafe;">
                        <i class="bi bi-bank fs-4 text-primary"></i>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #6b7280;">Penilaian Kewangan</span>
                        <h6 id="modalLabelButiranCadanganKewanganStep2" class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Penilaian Penyata Bulanan Bank</h6>
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
                            <span id="modalStep2KodPembekal" class="fw-bold text-dark fs-6">1/2</span>
                        </div>
                        <div class="col-md-4 border-end border-slate-200">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Nama Pembekal</span>
                            <span id="modalStep2NamaPembekal" class="fw-bold text-dark fs-6 text-truncate d-block">-</span>
                        </div>
                        <div class="col-md-5">
                            <span class="d-block text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Tajuk / Dokumen</span>
                            <span id="modalButiranCadanganKewanganTajukStep2" class="fw-semibold text-primary small text-truncate d-block">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</span>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Dokumen Semak Silang --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-file-earmark-check text-primary me-2 fs-5"></i>
                        <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.03em;">Dokumen Semak Silang</h6>
                    </div>
                    <div class="table-responsive rounded-3 border bg-white">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2.5 px-3 fw-bold text-secondary text-uppercase" style="width: 70%; font-size: 0.7rem; letter-spacing: 0.05em;">Tajuk / Dokumen</th>
                                    <th class="py-2.5 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 30%; font-size: 0.7rem; letter-spacing: 0.05em;">Tindakan Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-3 py-2.5">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-pdf text-danger fs-6 me-1"></i>
                                            <span class="fw-medium text-dark">Kunci Kira-kira Tahunan <span class="text-muted small">(Salinan telah diaudit bagi syarikat ROC)</span></span>
                                        </div>
                                    </td>
                                    <td class="text-center px-3 py-2.5">
                                        <button type="button" class="btn btn-sm btn-outline-primary px-3 py-1 d-inline-flex align-items-center gap-1 font-monospace">
                                            <i class="bi bi-eye"></i>
                                            <span>Papar Dokumen</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2.5">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-pdf text-danger fs-6 me-1"></i>
                                            <span class="fw-medium text-dark">Pengesahan Institusi Kewangan <span class="text-muted small">(Tiga bulan penyata bank)</span></span>
                                        </div>
                                    </td>
                                    <td class="text-center px-3 py-2.5">
                                        <button type="button" class="btn btn-sm btn-outline-primary px-3 py-1 d-inline-flex align-items-center gap-1 font-monospace">
                                            <i class="bi bi-eye"></i>
                                            <span>Papar Dokumen</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section 2: Penyata Bank (3 Bulan) --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calculator text-primary me-2 fs-5"></i>
                            <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.03em;">Input Amaun Penyata Bank (3 Bulan Terakhir)</h6>
                        </div>
                    </div>

                    <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">
                        <i class="bi bi-info-circle-fill text-primary flex-shrink-0 me-1"></i>
                        <span>Sila masukkan amaun RM bagi setiap bulan. Jumlah, purata, dan skor akan dikira secara automatik.</span>
                    </div>

                    <div class="table-responsive rounded-3 border bg-white mb-3">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th class="py-2.5 px-3 fw-bold text-secondary text-uppercase" style="width: 35%; font-size: 0.7rem; letter-spacing: 0.05em;">Bulan Rekod</th>
                                    <th class="py-2.5 px-3 fw-bold text-secondary text-uppercase" style="width: 65%; font-size: 0.7rem; letter-spacing: 0.05em;">Amaun Imbangan (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="modalStep2BulanTableBody">
                                <tr>
                                    <td class="text-center font-monospace fw-semibold px-3 py-2.5 bg-light-subtle">Bulan 6</td>
                                    <td class="px-3 py-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted fw-bold">RM</span>
                                            <input type="text" class="form-control text-end font-monospace fw-semibold step2-bulan-input" id="step2-bulan-6" inputmode="decimal" value="500,000.00" aria-label="Amaun bulan 6">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-semibold px-3 py-2.5 bg-light-subtle">Bulan 7</td>
                                    <td class="px-3 py-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted fw-bold">RM</span>
                                            <input type="text" class="form-control text-end font-monospace fw-semibold step2-bulan-input" id="step2-bulan-7" inputmode="decimal" value="300,000.00" aria-label="Amaun bulan 7">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-semibold px-3 py-2.5 bg-light-subtle">Bulan 8</td>
                                    <td class="px-3 py-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted fw-bold">RM</span>
                                            <input type="text" class="form-control text-end font-monospace fw-semibold step2-bulan-input" id="step2-bulan-8" inputmode="decimal" value="200,000.00" aria-label="Amaun bulan 8">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Calculated Totals Banner Grid --}}
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                                <label class="form-label fw-bold text-success text-uppercase d-block mb-1" for="step2-jumlah-amaun" style="font-size: 0.68rem; letter-spacing: 0.05em;">Jumlah Amaun (RM)</label>
                                <input type="text" class="form-control form-control-sm text-end font-monospace fw-bold fs-6 text-success bg-white border-success border-opacity-25" id="step2-jumlah-amaun" readonly value="1,000,000.00" aria-live="polite">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 border" style="background: #eff6ff; border-color: #bfdbfe !important;">
                                <label class="form-label fw-bold text-primary text-uppercase d-block mb-1" for="step2-purata" style="font-size: 0.68rem; letter-spacing: 0.05em;">Purata Bulanan (RM)</label>
                                <input type="text" class="form-control form-control-sm text-end font-monospace fw-bold fs-6 text-primary bg-white border-primary border-opacity-25" id="step2-purata" readonly value="333,333.33" aria-live="polite">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 border" style="background: #fefce8; border-color: #fef08a !important;">
                                <label class="form-label fw-bold text-warning-emphasis text-uppercase d-block mb-1" for="step2-skor-automatik" style="font-size: 0.68rem; letter-spacing: 0.05em;">Skor Automatik</label>
                                <input type="text" class="form-control form-control-sm text-center font-monospace fw-bold fs-6 text-warning-emphasis bg-white border-warning border-opacity-25" id="step2-skor-automatik" readonly value="10" aria-live="polite">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Skala Rujukan Skor Purata Penyata Bank --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-bar-chart-steps text-primary me-2 fs-5"></i>
                        <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.03em;">Skala Rujukan Julat Skor Purata Penyata Bank</h6>
                    </div>
                    <div class="table-responsive rounded-3 border bg-white">
                        <table class="table table-sm table-striped align-middle mb-0 text-center" style="font-size: 0.82rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2 text-secondary text-uppercase fw-bold" style="font-size: 0.68rem;">Dari (RM)</th>
                                    <th class="py-2 text-secondary text-uppercase fw-bold" style="font-size: 0.68rem;">Hingga (RM)</th>
                                    <th class="py-2 text-secondary text-uppercase fw-bold" style="font-size: 0.68rem;">Skor Kelayakan</th>
                                </tr>
                            </thead>
                            <tbody id="modalStep2ScoringScaleTableBody">
                                <tr>
                                    <td class="font-monospace">0.00</td>
                                    <td class="font-monospace">10,064.99</td>
                                    <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1 rounded-pill">0</span></td>
                                </tr>
                                <tr>
                                    <td class="font-monospace">10,065.00</td>
                                    <td class="font-monospace">Ke Atas</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1 rounded-pill">10</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section 4: Keputusan Penilaian (Status Kelayakan & Catatan) --}}
                <div class="rounded-3 p-3 border" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-journal-check text-primary me-2 fs-5"></i>
                        <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.03em;">Keputusan Penilaian Penyata Bank</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark small" for="step2-status-kelayakan">
                                Status Kelayakan Penyata Bank <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm fw-semibold" id="step2-status-kelayakan" name="status_kelayakan_penyata">
                                <option value="" selected disabled>-- Sila Pilih Status --</option>
                                <option value="layak">Mematuhi / Layak</option>
                                <option value="tidak_layak">Tidak Mematuhi / Tidak Layak</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark small" for="step2-catatan-penyata">
                                Catatan Penilaian <span class="text-muted font-normal">(Wajib diisi jika Tidak Layak)</span>
                            </label>
                            <textarea class="form-control form-control-sm" id="step2-catatan-penyata" name="catatan_penyata" rows="3" placeholder="Sila masukkan catatan ulasan penilaian di sini..."></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-1"></i>Batal / Tutup
                </button>
                <button type="button" class="btn btn-sm btn-success px-4 fw-bold" id="btnStep2SimpanPenyataBank">
                    <i class="bi bi-save me-1"></i>Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#penyata-bank .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#pematuhan-tab');
            if (tab) tab.click();
        });
    });

    document.querySelectorAll('#penyata-bank .btn-seterusnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penilaian-tab');
            if (tab) tab.click();
        });
    });

    function parseMoney(str) {
        return parseFloat(String(str || '').replace(/,/g, '')) || 0;
    }

    function formatMoney(num) {
        return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /** Ikut jadual skor paparan: 0–10,064.99 → 0; ≥10,065 (julat atas contoh 10,065–10,205 → 10); purata lebih besar daripada julat contoh dikira layak skor penuh paparan */
    function step2SkorFromPurata(purata) {
        if (purata <= 10064.99) return 0;
        return 10;
    }

    function recalcStep2PenyataBank() {
        const ids = ['step2-bulan-6', 'step2-bulan-7', 'step2-bulan-8'];
        let sum = 0;
        ids.forEach(function(id) {
            const el = document.getElementById(id);
            if (el) sum += parseMoney(el.value);
        });
        const avg = sum / 3;
        const elJumlah = document.getElementById('step2-jumlah-amaun');
        const elPurata = document.getElementById('step2-purata');
        const elSkor = document.getElementById('step2-skor-automatik');
        if (elJumlah) elJumlah.value = formatMoney(sum);
        if (elPurata) elPurata.value = formatMoney(avg);
        if (elSkor) elSkor.value = String(step2SkorFromPurata(avg));
    }

    document.querySelectorAll('.step2-bulan-input').forEach(function(inp) {
        inp.addEventListener('change', recalcStep2PenyataBank);
        inp.addEventListener('blur', recalcStep2PenyataBank);
    });

    document.querySelectorAll('.btn-papar-cadangan-kewangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tajuk = (btn.getAttribute('data-dokumen') || '').trim();
            const tajukFirstModal = document.getElementById('modalCadanganKewanganTajukStep2');
            if (tajukFirstModal && tajuk) tajukFirstModal.textContent = tajuk;
            const tajukDetail = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            if (tajukDetail && tajuk) tajukDetail.textContent = tajuk;
        });
    });

    document.querySelectorAll('.btn-papar-penyata-bank-detail-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const kod = (btn.getAttribute('data-kod-pembekal') || '').trim();
            const elKod = document.getElementById('modalStep2KodPembekal');
            if (elKod && kod) elKod.textContent = kod;

            const tajukSenarai = document.getElementById('modalCadanganKewanganTajukStep2');
            const tajukDetail = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            const t = (tajukSenarai && tajukSenarai.textContent) ? tajukSenarai.textContent.trim() : '';
            if (tajukDetail && t) tajukDetail.textContent = t;

            recalcStep2PenyataBank();
        });
    });

    const btnStep2SimpanPenyata = document.getElementById('btnStep2SimpanPenyataBank');
    const elModalButiranStep2 = document.getElementById('modalButiranCadanganKewanganStep2');
    const elModalCadanganStep2 = document.getElementById('modalPenilaianCadanganKewanganStep2');
    if (btnStep2SimpanPenyata && elModalButiranStep2 && elModalCadanganStep2 && typeof bootstrap !== 'undefined') {
        btnStep2SimpanPenyata.addEventListener('click', function() {
            const modalButiran = bootstrap.Modal.getInstance(elModalButiranStep2)
                || new bootstrap.Modal(elModalButiranStep2);
            const modalCadangan = bootstrap.Modal.getInstance(elModalCadanganStep2)
                || new bootstrap.Modal(elModalCadanganStep2);
            elModalButiranStep2.addEventListener('hidden.bs.modal', function reopenSenarai() {
                modalCadangan.show();
            }, { once: true });
            modalButiran.hide();
        });
    }
});
</script>
