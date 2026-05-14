@extends('layouts.v3.master')

@section('content')

<style>
    .cutoff-tender-header {
        background: var(--sg-bg);
        border: 1px solid var(--topbar-border, #e5e7eb);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .cutoff-tender-header .badge-status {
        background: #f59e0b;
        color: #fff;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .section-title-cutoff {
        font-size: 1rem;
        font-weight: 700;
        color: var(--sg-red-dark);
        margin-bottom: 0.75rem;
        padding-bottom: 0.35rem;
        border-bottom: 2px solid var(--topbar-border, #e5e7eb);
    }

    .cutoff-table thead {
        background: #1F3A8A !important;
        color: white !important;
    }

    .cutoff-table thead th {
        background: transparent !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        font-weight: 600;
        padding: 12px 10px;
        font-size: 0.875rem;
        text-align: center;
        vertical-align: middle;
    }

    .cutoff-table td {
        padding: 10px 12px;
        border: 1px solid var(--topbar-border, #e5e7eb);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .cutoff-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .cutoff-table tbody tr:hover {
        background: #f1f5f9;
    }

    .cutoff-table .text-freak {
        color: #dc2626;
        font-weight: 600;
    }

    .cutoff-actions .btn-laporan {
        background: #10b981;
        color: #fff;
        border: none;
    }

    .cutoff-actions .btn-laporan:hover {
        background: #059669;
        color: #fff;
    }

    .cutoff-actions .btn-simpan {
        background: #0d9488;
        color: #fff;
        border: none;
    }

    .cutoff-actions .btn-simpan:hover {
        background: #0f766e;
        color: #fff;
    }

    .cutoff-actions .btn-hantar {
        background: #7c3aed;
        color: #fff;
        border: none;
    }

    .cutoff-actions .btn-hantar:hover {
        background: #6d28d9;
        color: #fff;
    }

    .dist-chart-wrap {
        max-width: 100%;
        height: 280px;
        position: relative;
    }

    .cutoff-freq-table thead th {
        text-align: center;
        background: #1F3A8A !important;
        color: white !important;
        font-weight: 600;
        padding: 10px 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .cutoff-freq-table td {
        text-align: center;
        padding: 8px 12px;
    }

    .cutoff-freak-box {
        border: 1px solid var(--topbar-border, #e5e7eb) !important;
        background: #f8fafc !important;
        min-height: 80%;
    }

    /* Success modal (same UI as other modules) */
    .cutoff-modal-card {
        border-radius: 10px;
        border: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        padding: 18px 18px 14px;
        text-align: center;
        min-height: 200px;
    }

    .cutoff-modal-card .cutoff-modal-icon {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .cutoff-modal-card .cutoff-modal-text {
        text-align: center;
    }

    .cutoff-modal-card .cutoff-modal-btn-wrap {
        text-align: center;
    }

    .cutoff-modal-card .confetti {
        width: 44px;
        height: 44px;
        margin: 6px auto 8px;
    }

    .cutoff-modal-card .btn-modal {
        background: #3a4f8a;
        color: #fff;
        border: 0;
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 700;
        width: 80px;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="py-2 mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('cutOff.index') }}" class="text-secondary text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cutOff.index') }}" class="text-secondary text-decoration-none">Cut-Off</a></li>
                <li class="breadcrumb-item active" aria-current="page">PENENTUAN HARGA CUT OFF</li>
            </ol>
        </nav>

        <div class="cutoff-tender-header">
            <div class="row g-2 mb-0">
                <div class="col-md-6"><strong>No. Sebut Harga / Tender:</strong> {{ $tender_no }}</div>
                <div class="col-md-6"><strong>Tempoh Sah Laku Tawaran (Hari):</strong> 90</div>
                <div class="col-md-6"><strong>PTJ:</strong> BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</div>
                <div class="col-md-6"><strong>Tajuk Perolehan:</strong> Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</div>
                <div class="col-md-6"><strong>STATUS:</strong> Menunggu Pengesahan CutOff</div>
                <div class="col-md-6"><strong>Sah Laku Tawaran Tamat:</strong> 17/01/2022</div>
            </div>
        </div>

        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">PENENTUAN HARGA CUT OFF</h4>

        {{-- Section 1 --}}
        @php
        $bins = ['Less', '-90%', '-70%', '-50%', '-30%', '-10%', '10%', '30%', '50%', '70%', '90%', 'More'];
        $freq = [0, 0, 0, 0, 2, 5, 2, 0, 0, 0, 0, 0];
        @endphp
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="section-title-cutoff">Tender Prices Distribution Curve</div>
                <div class="dist-chart-wrap border rounded p-3 bg-white shadow-sm">
                    <canvas id="cutoffDistChart" data-labels="{{ implode(',', $bins) }}" data-freq="{{ implode(',', $freq) }}" width="400" height="260"></canvas>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="table-responsive mt-5">
                    <table class="table table-bordered table-sm mb-0 cutoff-freq-table cutoff-table">
                        <thead>
                            <tr>
                                <th>Bin</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bins as $i => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>{{ $freq[$i] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-lg-6">
                <div class="section-title-cutoff">Anggaran Jabatan (AJ)</div>
                <div class="table-responsive">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Item</th>
                                <th class="text-center">Nilai (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Anggaran Jabatan (AJ)</strong></td>
                                <td class="text-center">892,000.00</td>
                            </tr>
                            <tr>
                                <td><strong>PC & Prov. Sums (PCP)</strong></td>
                                <td class="text-center">-</td>
                            </tr>
                            <tr>
                                <td><strong>Builder's Work in AJ (Bwa)</strong></td>
                                <td class="text-center">892,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title-cutoff">Penentuan dan Pengasingan Harga "Freak"</div>
                <div class="cutoff-freak-box border rounded p-3 bg-light h-40">
                    <p class="mb-0 text-secondary text-default">
                        Sesuatu harga tender dianggap 'Freak' dan secara automatik tidak diambil kira sekiranya tahap 'significance'nya (Alfa) kurang daripada 0.01, iaitu Z-score nya melebihi +2.33 atau kurang dari -2.33.
                    </p>
                </div>
            </div>
        </div>

        {{-- Section 4 & 5 --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-title-cutoff">Statistical Attribute</div>
                <div class="table-responsive">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Attribute</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>No. of Tender Analysed (Nt)</strong></td>
                                <td class="text-center">11</td>
                            </tr>
                            <tr>
                                <td><strong>Mean of BW (mean)</strong></td>
                                <td class="text-center">826,077.78</td>
                            </tr>
                            <tr>
                                <td><strong>Overall Mean</strong></td>
                                <td class="text-center">826,077.78</td>
                            </tr>
                            <tr>
                                <td><strong>Standard Deviation (SD)</strong></td>
                                <td class="text-center">117,506.36</td>
                            </tr>
                            <tr>
                                <td><strong>Coefficient of Variation (CV)</strong></td>
                                <td class="text-center">0.1422</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title-cutoff">Penentuan Harga Cutoff</div>
                <div class="table-responsive h-100">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 55%;">Keterangan</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Bilangan Tender Melebihi 10 iaitu Nt &gt; 10</strong></td>
                                <td class="text-center">OK</td>
                            </tr>
                            <tr>
                                <td>Mean - X*Mean or Mean - X*SD (yang mana lebih tinggi)</td>
                                <td class="text-center">RM 685,544.55 and RM 708,572.41</td>
                            </tr>
                            <tr>
                                <td>Cut-off Bagi Builder's Works sahaja (tanpa PCP)</td>
                                <td class="text-center">RM 708,572.41</td>
                            </tr>
                            <tr>
                                <td>Cut-off Bagi Tender (termasuk PCP)</td>
                                <td class="text-center">RM 708,572.41</td>
                            </tr>
                            <tr>
                                <td><strong>Harap CUT-OFF (rounded down) YANG DITETAPKAN</strong></td>
                                <td class="text-center fw-bold text-dark">RM 708,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Section 6 --}}
        <!-- <div class="section-title-cutoff">Senarai Tender (Z-score &amp; Peratusan)</div> -->
        <div class="table-responsive mb-4">
            <table class="table cutoff-table mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Ruj. TEN.</th>
                        <th>TEND. PRICE (RM)</th>
                        <th>BW of Tender (RM)</th>
                        <th>Z-score</th>
                        <th>%BW-AJ</th>
                        <th>%BW-Mean</th>
                        <th class="text-center" style="width:10%;">
                            <div class="d-inline-flex align-items-center gap-1">
                                Pilih
                                <div class="form-check m-0">
                                    <input type="checkbox" class="form-check-input" id="selectAllPilih">
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $rows = [
                    ['ruj' => '1/16', 'price' => '892,000.00', 'bw' => '892,000.00', 'z' => '0.56', 'pct_aj' => '0.00%', 'pct_mean' => '7.58%', 'freak' => false],
                    ['ruj' => '2/16', 'price' => '850,000.00', 'bw' => 'FREAK', 'z' => '-', 'pct_aj' => '-', 'pct_mean' => '-', 'freak' => true],
                    ['ruj' => '3/16', 'price' => '820,000.00', 'bw' => '820,000.00', 'z' => '-0.05', 'pct_aj' => '-8.07%', 'pct_mean' => '-0.73%', 'freak' => false],
                    ['ruj' => '4/16', 'price' => '795,000.00', 'bw' => '795,000.00', 'z' => '-0.26', 'pct_aj' => '-10.87%', 'pct_mean' => '-3.76%', 'freak' => false],
                    ['ruj' => '5/16', 'price' => '810,000.00', 'bw' => '810,000.00', 'z' => '-0.14', 'pct_aj' => '-9.19%', 'pct_mean' => '-1.95%', 'freak' => false],
                    ['ruj' => '6/16', 'price' => '830,000.00', 'bw' => '830,000.00', 'z' => '0.03', 'pct_aj' => '-6.95%', 'pct_mean' => '0.47%', 'freak' => false],
                    ['ruj' => '7/16', 'price' => '825,000.00', 'bw' => '825,000.00', 'z' => '-0.01', 'pct_aj' => '-7.51%', 'pct_mean' => '-0.13%', 'freak' => false],
                    ['ruj' => '8/16', 'price' => '818,000.00', 'bw' => '818,000.00', 'z' => '-0.07', 'pct_aj' => '-8.30%', 'pct_mean' => '-0.98%', 'freak' => false],
                    ['ruj' => '9/16', 'price' => '840,000.00', 'bw' => '840,000.00', 'z' => '0.12', 'pct_aj' => '-5.83%', 'pct_mean' => '1.69%', 'freak' => false],
                    ['ruj' => '10/16', 'price' => '815,000.00', 'bw' => '815,000.00', 'z' => '-0.09', 'pct_aj' => '-8.63%', 'pct_mean' => '-1.34%', 'freak' => false],
                    ['ruj' => '11/16', 'price' => '808,000.00', 'bw' => '808,000.00', 'z' => '-0.15', 'pct_aj' => '-9.42%', 'pct_mean' => '-2.19%', 'freak' => false],
                    ['ruj' => '12/16', 'price' => '822,000.00', 'bw' => '822,000.00', 'z' => '-0.03', 'pct_aj' => '-7.85%', 'pct_mean' => '-0.49%', 'freak' => false],
                    ['ruj' => '13/16', 'price' => '835,000.00', 'bw' => '835,000.00', 'z' => '0.08', 'pct_aj' => '-6.39%', 'pct_mean' => '1.08%', 'freak' => false],
                    ['ruj' => '14/16', 'price' => '828,000.00', 'bw' => '828,000.00', 'z' => '0.02', 'pct_aj' => '-7.17%', 'pct_mean' => '0.23%', 'freak' => false],
                    ['ruj' => '15/16', 'price' => '812,000.00', 'bw' => '812,000.00', 'z' => '-0.12', 'pct_aj' => '-8.97%', 'pct_mean' => '-1.70%', 'freak' => false],
                    ['ruj' => '16/16', 'price' => '838,000.00', 'bw' => '838,000.00', 'z' => '0.10', 'pct_aj' => '-6.05%', 'pct_mean' => '1.44%', 'freak' => false],
                    ];
                    @endphp
                    @foreach($rows as $idx => $r)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ $r['ruj'] }}</td>
                        <td class="text-center">{{ $r['price'] }}</td>
                        <td class="text-center {{ $r['freak'] ? 'text-freak' : '' }}">{{ $r['bw'] }}</td>
                        <td class="text-center">{{ $r['z'] }}</td>
                        <td class="text-center">{{ $r['pct_aj'] }}</td>
                        <td class="text-center">{{ $r['pct_mean'] }}</td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input pilih-checkbox" name="pilih[]" value="{{ $idx + 1 }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Action buttons --}}
        <div class="d-flex flex-wrap gap-2 justify-content-end cutoff-actions">
            <a href="#" class="btn btn-laporan px-4">Laporan</a>
            <button type="button" class="btn btn-simpan px-4" id="btnSimpanCutoff">Simpan</button>
            <button type="button" class="btn btn-hantar px-4" id="btnHantarCutoff">Hantar</button>
        </div>

    </div>
</div>

{{-- Success modal --}}
<div class="modal fade" id="cutoffSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content cutoff-modal-card">
            <svg class="cutoff-modal-icon" width="83" height="83" viewBox="0 0 83 83" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_274_14077" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="84" height="84">
                    <rect width="83.0037" height="83.0037" fill="white" />
                </mask>
                <g mask="url(#mask0_274_14077)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M50.3739 58.7881C50.0767 58.7881 49.7813 58.6742 49.5555 58.4483C49.1347 58.0275 49.1057 57.3616 49.4706 56.906C49.4937 56.5277 49.1192 55.107 47.0847 52.1922C45.2374 49.5457 42.5542 46.4225 39.5313 43.4015C36.5085 40.3786 33.3871 37.6974 30.7407 35.8482C27.8259 33.8136 26.4071 33.4391 26.0268 33.4623C25.5732 33.8271 24.9053 33.7982 24.4845 33.3774C24.0328 32.9257 24.0328 32.1922 24.4845 31.7385C25.6524 30.5707 27.8471 31.1266 31.3931 33.4893C34.2963 35.4235 37.769 38.3615 41.1702 41.7627C44.5714 45.1639 47.5093 48.6365 49.4435 51.5397C51.8062 55.0857 52.3622 57.2805 51.1943 58.4483C50.9666 58.6761 50.6693 58.7881 50.3739 58.7881Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M62.4267 60.0778C61.7878 60.0778 61.2686 59.5586 61.2686 58.9196C61.2686 53.4318 58.0333 50.82 55.3193 49.5943C49.9086 47.1524 42.8919 48.3106 39.9771 50.8799C39.4965 51.3026 38.7649 51.2563 38.3422 50.7776C37.9194 50.2969 37.9658 49.5653 38.4445 49.1426C42.392 45.6622 50.5553 44.9017 56.271 47.4825C60.9867 49.6117 63.5849 53.673 63.5849 58.9196C63.5849 59.5586 63.0657 60.0778 62.4267 60.0778Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M32.9623 46.1348C32.7673 46.1348 32.5685 46.0846 32.387 45.9804C31.833 45.6619 31.6419 44.9534 31.9604 44.3994C34.7053 39.6219 34.3482 29.563 29.4433 22.7664C26.9551 19.3188 22.3803 15.5373 14.7092 17.099C14.0818 17.2264 13.4699 16.8229 13.3425 16.1956C13.2151 15.5682 13.6205 14.9563 14.2459 14.8289C21.1757 13.4178 27.2408 15.7555 31.3215 21.4094C36.3075 28.318 37.5197 39.3748 33.968 45.5538C33.7537 45.9263 33.3638 46.1348 32.9623 46.1348Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M50.374 58.7884C50.0767 58.7884 49.7814 58.6765 49.5555 58.4487C49.1039 57.997 49.1019 57.2636 49.5555 56.8119L49.5575 56.8099C50.0092 56.3583 50.7426 56.3583 51.1962 56.8099C51.6479 57.2616 51.6479 57.9951 51.1962 58.4487C50.9685 58.6746 50.6712 58.7884 50.374 58.7884Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M25.3007 33.7131C25.0035 33.7131 24.7081 33.6012 24.4823 33.3734C24.0306 32.9217 24.0287 32.1882 24.4823 31.7366L24.4842 31.7346C24.9359 31.283 25.6694 31.283 26.123 31.7346C26.5747 32.1863 26.5747 32.9198 26.123 33.3734C25.8933 33.6012 25.596 33.7131 25.3007 33.7131Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M51.1928 56.8118C50.772 56.391 50.1099 56.362 49.6562 56.723C49.6427 56.723 49.6292 56.7249 49.6157 56.7268C49.1968 56.7288 47.7741 56.3234 44.9365 54.3409C42.29 52.4936 39.1668 49.8104 36.1458 46.7875C33.1248 43.7646 30.4416 40.6432 28.5924 37.9967C26.6099 35.1553 26.2045 33.7365 26.2065 33.3157C26.2084 33.3022 26.2084 33.2906 26.2103 33.2771C26.5713 32.8234 26.5423 32.1594 26.1215 31.7405C25.6698 31.2888 24.9363 31.2888 24.4827 31.7405C24.1603 32.0629 23.975 32.4663 23.9171 32.9528L10.0765 69.6196C9.72327 70.5539 9.94333 71.575 10.6498 72.2815C11.1344 72.766 11.7636 73.0208 12.4142 73.0208C12.7134 73.0208 13.0164 72.9668 13.3118 72.8548L49.9689 59.022C50.4612 58.966 50.8665 58.7769 51.1928 58.4506C51.6445 57.997 51.6445 57.2635 51.1928 56.8118ZM12.4932 70.6863C12.4643 70.6978 12.3755 70.7307 12.2886 70.6419C12.1998 70.5531 12.2326 70.4662 12.2442 70.4373L25.0034 36.6263C25.3373 37.2459 25.7446 37.9177 26.2311 38.6492C28.1652 41.5524 31.1031 45.025 34.5043 48.4262C37.9055 51.8273 41.3781 54.7652 44.2812 56.6994C45.0109 57.1858 45.6826 57.595 46.3003 57.929L12.4932 70.6863Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M48.3086 39.1621C48.0345 39.1621 47.7585 39.0656 47.5365 38.8667C47.0598 38.4402 47.0192 37.7086 47.4458 37.2318C48.8259 35.6876 50.7677 36.0698 52.1845 36.3478C53.5878 36.6238 54.3001 36.6991 54.7517 36.1934C55.2034 35.6876 55.049 34.9908 54.6186 33.6262C54.1843 32.248 53.5878 30.3602 54.9679 28.8179C56.348 27.2757 58.2899 27.6559 59.7066 27.9358C61.1099 28.2119 61.8222 28.2871 62.2719 27.7814C62.6985 27.3046 63.4301 27.2641 63.9068 27.6907C64.3836 28.1173 64.4241 28.8488 63.9976 29.3256C62.6174 30.8679 60.6756 30.4876 59.2588 30.2077C57.8556 29.9317 57.1433 29.8564 56.6936 30.3621C56.2419 30.8679 56.3963 31.5647 56.8267 32.9293C57.261 34.3075 57.8575 36.1953 56.4774 37.7376C55.0973 39.2798 53.1554 38.8996 51.7387 38.6216C50.3354 38.3456 49.6231 38.2703 49.1734 38.776C48.9437 39.0308 48.6271 39.1621 48.3086 39.1621Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M43.2338 31.3121C42.6122 31.3121 42.0968 30.8179 42.0775 30.1905C42.0099 28.1212 43.6797 27.0576 44.8977 26.2797C46.1042 25.5095 46.6698 25.0732 46.6486 24.3957C46.6273 23.7181 46.0328 23.3205 44.78 22.6294C43.5137 21.9326 41.7802 20.979 41.7127 18.9096C41.6451 16.8403 43.3149 15.7767 44.5329 14.9988C45.7394 14.2286 46.305 13.7923 46.2837 13.1167C46.2625 12.4777 46.7644 11.943 47.4033 11.9218C48.0403 11.9025 48.577 12.4025 48.5982 13.0414C48.6658 15.1107 46.996 16.1743 45.778 16.9523C44.5715 17.7225 44.0059 18.1587 44.0272 18.8344C44.0484 19.5119 44.6429 19.9115 45.8957 20.6006C47.162 21.2975 48.8955 22.2511 48.963 24.3204C49.0306 26.3897 47.3609 27.4533 46.1428 28.2312C44.9364 29.0015 44.3708 29.4377 44.392 30.1153C44.4132 30.7542 43.9113 31.2889 43.2724 31.3101C43.2589 31.3101 43.2454 31.3121 43.2338 31.3121Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M34.3945 14.6647C34.1938 14.6647 33.9892 14.6125 33.8039 14.5025C33.2538 14.1763 33.0743 13.4641 33.4005 12.9159L34.8057 10.5533C35.1319 10.0032 35.8441 9.82364 36.3923 10.1498C36.9424 10.4761 37.1219 11.1883 36.7957 11.7365L35.3905 14.0991C35.1743 14.462 34.7902 14.6647 34.3945 14.6647Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M59.0104 71.1773C58.6186 71.1773 58.2364 70.9784 58.0183 70.6194C57.6863 70.0732 57.86 69.3609 58.4082 69.0289L61.3614 67.2376C61.9077 66.9056 62.62 67.0794 62.952 67.6275C63.284 68.1738 63.1102 68.8861 62.562 69.2181L59.6088 71.0093C59.4216 71.1232 59.215 71.1773 59.0104 71.1773Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M70.6194 57.6667C70.4186 57.6667 70.214 57.6146 70.0287 57.5046C69.4786 57.1784 69.2991 56.4661 69.6253 55.9179L71.0305 53.5553C71.3567 53.0052 72.0671 52.8257 72.6172 53.1519C73.1673 53.4781 73.3468 54.1904 73.0206 54.7386L71.6154 57.1012C71.3992 57.4641 71.0132 57.6667 70.6194 57.6667Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.9797 45.7701C10.6824 45.7701 10.3871 45.6563 10.1613 45.4304C9.70959 44.9787 9.70959 44.2453 10.1613 43.7917L12.105 41.8479C12.5567 41.3962 13.2902 41.3962 13.7438 41.8479C14.1955 42.2996 14.1955 43.0331 13.7438 43.4867L11.8 45.4304C11.5723 45.6563 11.2769 45.7701 10.9797 45.7701Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M65.2667 17.2725C64.9694 17.2725 64.6741 17.1586 64.4483 16.9328L62.5045 14.9909C62.0529 14.5393 62.0529 13.8058 62.5045 13.3522C62.9562 12.9005 63.6897 12.9005 64.1433 13.3522L66.087 15.2959C66.5387 15.7476 66.5387 16.4811 66.087 16.9347C65.8593 17.1605 65.5639 17.2725 65.2667 17.2725Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M41.2956 71.183C40.8304 71.183 40.3922 70.9012 40.2146 70.4418L39.2225 67.8784C38.9909 67.282 39.2881 66.6103 39.8846 66.3806C40.481 66.1489 41.1527 66.4462 41.3824 67.0426L42.3745 69.606C42.6062 70.2024 42.3089 70.8741 41.7125 71.1038C41.5754 71.1579 41.4345 71.183 41.2956 71.183Z" fill="#405189" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.4309 29.0437C18.1626 29.0437 17.8943 28.951 17.6762 28.7638L15.5935 26.9706C15.109 26.5537 15.053 25.8221 15.4719 25.3376C15.8888 24.8532 16.6204 24.7972 17.1049 25.216L19.1876 27.0092C19.6721 27.4262 19.7281 28.1577 19.3092 28.6422C19.0814 28.9086 18.7572 29.0437 18.4309 29.0437Z" fill="#0AB39C" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M67.5115 42.1179C67.0057 42.1179 66.5406 41.7839 66.3977 41.2724L65.6527 38.6261C65.4789 38.0103 65.838 37.3714 66.4537 37.1977C67.0694 37.024 67.7084 37.383 67.8821 37.9987L68.6271 40.6451C68.8009 41.2608 68.4418 41.8997 67.8261 42.0735C67.7219 42.1043 67.6157 42.1179 67.5115 42.1179Z" fill="#0AB39C" />
                </g>
            </svg>
            <div class="cutoff-modal-text fw-bold" style="font-size: 16px; margin-bottom: 14px;">
                Maklumat telah berjaya dihantar
            </div>
            <div class="cutoff-modal-btn-wrap">
                <button type="button" class="btn-modal" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function() {
        var el = document.getElementById('cutoffDistChart');
        if (!el) return;
        var labels = el.getAttribute('data-labels').split(',');
        var freqStr = el.getAttribute('data-freq').split(',');
        var freq = freqStr.map(function(n) {
            return parseInt(n, 10);
        });
        new Chart(el.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Frequency',
                    data: freq,
                    borderColor: 'rgb(31, 58, 138)',
                    backgroundColor: 'rgba(31, 58, 138, 0.12)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.25,
                    pointBackgroundColor: 'rgb(31, 58, 138)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 6,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Frequency'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Price Classification: % of Mean'
                        }
                    }
                }
            }
        });
    })();

    (function() {
        var selectAll = document.getElementById('selectAllPilih');
        if (!selectAll) return;
        var checkboxes = document.querySelectorAll('.pilih-checkbox');

        selectAll.addEventListener('change', function() {
            var checked = selectAll.checked;
            checkboxes.forEach(function(cb) {
                cb.checked = checked;
            });
        });
    })();

    function showCutoffSuccessModal() {
        var modalEl = document.getElementById('cutoffSuccessModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }
    (function() {
        var btnHantar = document.getElementById('btnHantarCutoff');
        if (btnHantar) btnHantar.addEventListener('click', showCutoffSuccessModal);
        var btnSimpan = document.getElementById('btnSimpanCutoff');
        if (btnSimpan) btnSimpan.addEventListener('click', showCutoffSuccessModal);
    })();
</script>

@endsection