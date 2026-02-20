@extends('layouts.v3.master')

@section('styles')
    <style>
        .page-title-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .title-pipe {
            font-size: 1.5rem;
            color: #cbd5e1;
            font-weight: 300;
            margin: 0 15px;
        }

        .vendor-highlight-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--sg-red);
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(196, 30, 58, 0.1);
        }

        .pdfobject-container {
            height: 80rem;
        }

        .bg-blue-selangor {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <div id="left-pane" class="col-12">

            <div class="mb-4 d-flex align-items-center flex-wrap justify-content-between">
                <div class="d-flex align-items-center">
                    <h2 class="page-title-text m-0">
                        @if (isset($vendor))
                            {{ $vendor->name }}
                        @else
                            Syarikat
                        @endif
                    </h2>
                    <span class="title-pipe">|</span>
                    <span class="vendor-highlight-text">
                        @if ($request->type == 'district')
                            Permintaan Kemaskini Alamat SSM
                        @else
                            Permintaan Kemaskini CIDB / MOF
                        @endif
                    </span>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">{{ App\CodeRequest::$statuses[$request->status] ?? '' }}</span>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold" style="width: 30%;">
                                        Perkara</th>
                                    @if ($request->status == 'pending')
                                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Data Semasa</th>
                                    @endif
                                    <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold text-primary">Data Baru
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Nama Syarikat
                                    </th>
                                    <td class="text-dark fw-medium ps-4 py-3"
                                        @if ($request->status == 'pending') colspan="2" @endif>
                                        {{ $request->vendor->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">No. Syarikat
                                    </th>
                                    <td class="text-dark fw-medium ps-4 py-3"
                                        @if ($request->status == 'pending') colspan="2" @endif>
                                        {{ $request->vendor->registration }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Tarikh
                                        Permintaan</th>
                                    <td class="text-dark fw-medium ps-4 py-3"
                                        @if ($request->status == 'pending') colspan="2" @endif>
                                        {{ Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Jenis
                                        Kemaskini</th>
                                    <td class="text-dark fw-medium ps-4 py-3"
                                        @if ($request->status == 'pending') colspan="2" @endif>
                                        {{ App\CodeRequest::$types[$request->type] ?? '' }}</td>
                                </tr>

                                @if ($request->type == 'district')
                                    <tr>
                                        <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Alamat
                                        </th>
                                        @if ($request->status == 'pending')
                                            <td class="text-dark fw-medium ps-4 py-3">
                                                {{ $request->vendor->address ?? '' }}
                                            </td>
                                        @endif
                                        <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                            {{ $request->data['address'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Daerah
                                        </th>
                                        @if ($request->status == 'pending')
                                            <td class="text-dark fw-medium ps-4 py-3">
                                                {{ App\Vendor::$districts[!is_null($request->vendor->district_id) ? $request->vendor->district_id : '0'] }}
                                            </td>
                                        @endif
                                        <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                            {{ App\Vendor::$districts[$request->data['district_id']] ?? '' }}
                                            @if (!empty($request->data['state_id']) && $request->data['state_id'] > 0)
                                                &nbsp;({{ App\Models\RefState::find($request->data['state_id'])->description }})
                                            @endif
                                            {{-- @php var_dump($request->data); die; @endphp --}}
                                        </td>
                                    </tr>
                                @endif

                                @if ($request->type == 'email')
                                    <tr>
                                        <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Alamat
                                            Emel</th>
                                        @if ($request->status == 'pending')
                                            <td class="text-dark fw-medium ps-4 py-3">{{ $request->vendor->user->email }}
                                            </td>
                                        @endif
                                        <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                            {{ isset($request->data['email']) ? $request->data['email'] : 'Empty' }}
                                        </td>
                                    </tr>
                                @endif

                                @if ($request->type == 'mof')

                                    @if (isset($request->data['mof_ref_no']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">No
                                                Rujukan
                                                Pendaftaran
                                                MOF</th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    {{ $request->vendor->mof_ref_no }}</td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                {{ $request->data['mof_ref_no'] }}</td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['mof_start_date']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Tarikh Mula Aktif
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if (!empty($request->vendor->mof_start_date))
                                                        {{ Carbon\Carbon::parse($request->vendor->mof_start_date)->format('d/m/Y') }}
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18">
                                                            </line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18">
                                                            </line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                {{ Carbon\Carbon::parse($request->data['mof_start_date'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['mof_end_date']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Tarikh Tamat Aktif
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if (!empty($request->vendor->mof_end_date))
                                                        {{ Carbon\Carbon::parse($request->vendor->mof_end_date)->format('d/m/Y') }}
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18">
                                                            </line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18">
                                                            </line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                {{ Carbon\Carbon::parse($request->data['mof_end_date'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['bumiputera_company']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Syarikat
                                                Bumiputera
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if ($request->vendor->mof_bumi)
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-success">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18">
                                                            </line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18">
                                                            </line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                @if ($request->data['bumiputera_company'])
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-success">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['mof_bumi']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Syarikat
                                                Bumiputera
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if ($request->vendor->mof_bumi)
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-success">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18"></line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18"></line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                @if ($request->data['mof_bumi'])
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-success">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['mof_codes']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Kod
                                                Bidang MOF
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    <u>Jumlah: {{ count($request->vendor->mof_codes) }}</u><br>
                                                    <ul>
                                                        @foreach ($request->vendor->mof_codes as $code)
                                                            <li><?= $code->code->label2 ?></li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <u>Jumlah: {{ count($request->mof_codes) }}</u><br>
                                                <ul>
                                                    @foreach ($request->mof_codes as $code)
                                                        <li><?= $code->label2 ?></li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif
                                @endif

                                {{-- @endif --}}

                                @if ($request->type == 'cidb')

                                    @if (isset($request->data['cidb_ref_no']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">No.
                                                Sijil CIDB
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    {{ $request->vendor->cidb_ref_no }}</td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                {{ $request->data['cidb_ref_no'] }}</td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_start_date']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Tarikh Mula Aktif
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if (!empty($request->vendor->cidb_start_date))
                                                        {{ Carbon\Carbon::parse($request->vendor->cidb_start_date)->format('d/m/Y') }}
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18"></line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18"></line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                {{ Carbon\Carbon::parse($request->data['cidb_start_date'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_end_date']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Tarikh Tamat
                                                Aktif
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if (!empty($request->vendor->cidb_end_date))
                                                        {{ Carbon\Carbon::parse($request->vendor->cidb_end_date)->format('d/m/Y') }}
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18"></line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18"></line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                @if (!empty($request->data['cidb_end_date']))
                                                    {{ Carbon\Carbon::parse($request->data['cidb_end_date'])->format('d/m/Y') }}
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_bumi']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                                Syarikat
                                                Bumiputera
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @if ($request->vendor->cidb_bumi)
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-success">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18"></line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18"></line>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                @if ($request->data['cidb_bumi'])
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-success">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_group']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Gred
                                                &amp; Bidang
                                                Pengkhususan</th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    @forelse($request->vendor->cidbGrades()->orderBy('id')->get() as $grade)
                                                        <u><b>{!! $grade->code->label !!}</b></u><br>
                                                        <small>Jumlah Bidang Pengkhususan:
                                                            {{ count($grade->children) }}</small><br><br>
                                                        <?php $b_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))
                                                            ->where('code', 'LIKE', 'B%')
                                                            ->orderBy('code')
                                                            ->get(); ?>
                                                        @if (count($b_codes) > 0)
                                                            <u><b>B</b></u>
                                                            <ul>
                                                                @foreach ($b_codes as $code)
                                                                    <li>{!! $code->label2 !!}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                        <?php $ce_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))
                                                            ->where('code', 'LIKE', 'CE%')
                                                            ->orderBy('code')
                                                            ->get(); ?>
                                                        @if (count($ce_codes) > 0)
                                                            <u><b>CE</b></u>
                                                            <ul>
                                                                @foreach ($ce_codes as $code)
                                                                    <li>{!! $code->label2 !!}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                        <?php $me_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))
                                                            ->where('code', 'REGEXP', '^[ME]')
                                                            ->orderBy('code')
                                                            ->get(); ?>
                                                        @if (count($me_codes) > 0)
                                                            <u><b>ME</b></u>
                                                            <ul>
                                                                @foreach ($me_codes as $code)
                                                                    <li>{!! $code->label2 !!}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                        <?php $p_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))
                                                            ->where('code', 'LIKE', 'P%')
                                                            ->orderBy('code')
                                                            ->get(); ?>
                                                        @if (count($p_codes) > 0)
                                                            <u><b>P</b></u>
                                                            <ul>
                                                                @foreach ($p_codes as $code)
                                                                    <li>{!! $code->label2 !!}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                    @empty
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="text-danger">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18">
                                                            </line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18">
                                                            </line>
                                                        </svg>
                                                    @endforelse
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <?php if(count($request->data['cidb_group']) > 0) : ?>
                                                @foreach ($request->data['cidb_group'] as $data)
                                                    <?php if (empty($data['code_id']) || empty($data['codes'])) {
                                                        continue;
                                                    } ?>
                                                    <?php $grade = App\Code::find($data['code_id']); ?>
                                                    <u><b>{{ $grade->label }}</b></u><br>
                                                    <small>Jumlah Bidang Pengkhususan:
                                                        {{ count($data['codes']) }}</small><br><br>
                                                    <?php $b_codes = App\Code::whereIn('id', $data['codes'])->where('code', 'LIKE', 'B%')->orderBy('code')->get(); ?>
                                                    @if (count($b_codes) > 0)
                                                        <u><b>B</b></u>
                                                        <ul>
                                                            @foreach ($b_codes as $code)
                                                                <li>{!! $code->label2 !!}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    <?php $ce_codes = App\Code::whereIn('id', $data['codes'])->where('code', 'LIKE', 'CE%')->orderBy('code')->get(); ?>
                                                    @if (count($ce_codes) > 0)
                                                        <u><b>CE</b></u>
                                                        <ul>
                                                            @foreach ($ce_codes as $code)
                                                                <li>{!! $code->label2 !!}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    <?php $me_codes = App\Code::whereIn('id', $data['codes'])->where('code', 'REGEXP', '^[ME]')->orderBy('code')->get(); ?>
                                                    @if (count($me_codes) > 0)
                                                        <u><b>ME</b></u>
                                                        <ul>
                                                            @foreach ($me_codes as $code)
                                                                <li>{!! $code->label2 !!}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    <?php $p_codes = App\Code::whereIn('id', $data['codes'])->where('code', 'LIKE', 'P%')->orderBy('code')->get(); ?>
                                                    @if (count($p_codes) > 0)
                                                        <u><b>P</b></u>
                                                        <ul>
                                                            @foreach ($p_codes as $code)
                                                                <li>{!! $code->label2 !!}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endforeach
                                                <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-danger">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_grade_b_id']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Gred
                                                CIDB
                                                Kateogri B
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    <?php if($request->vendor->cidb_grade_b) : ?>
                                                    <?php echo $request->vendor->cidb_grade_b->label; ?>
                                                    <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                    <?php endif; ?>
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <?php if($request->data['cidb_grade_b_id']) : ?>
                                                {{ App\Code::find($request->data['cidb_grade_b_id'])->label }}
                                                <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-danger">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_grade_ce_id']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Gred
                                                CIDB
                                                Kategori CE
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    <?php if($request->vendor->cidb_grade_ce) : ?>
                                                    <?php echo $request->vendor->cidb_grade_ce->label; ?>
                                                    <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                    <?php endif; ?>
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <?php if($request->data['cidb_grade_ce_id']) : ?>
                                                {{ App\Code::find($request->data['cidb_grade_ce_id'])->label }}
                                                <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-danger">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_grade_me_id']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Gred
                                                CIDB
                                                Kategori ME
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    <?php if($request->vendor->cidb_grade_me) : ?>
                                                    <?php echo $request->vendor->cidb_grade_me->label; ?>
                                                    <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-danger">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                    <?php endif; ?>
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <?php if($request->data['cidb_grade_me_id']) : ?>
                                                {{ App\Code::find($request->data['cidb_grade_me_id'])->label }}
                                                <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-danger">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($request->data['cidb_codes']))
                                        <tr>
                                            <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">Kod
                                                Bidang CIDB
                                            </th>
                                            @if ($request->status == 'pending')
                                                <td class="text-dark fw-medium ps-4 py-3">
                                                    <u>Jumlah: {{ count($request->vendor->cidb_codes) }}</u><br>
                                                    <ul>
                                                        @foreach (App\Code::whereIn('id', $request->vendor->cidb_codes->pluck('code_id'))->orderBy('code')->get() as $code)
                                                            <li>{!! $code->label2 !!}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            @endif
                                            <td class="bg-blue-selangor text-dark fw-bold ps-4 py-3">
                                                <u>Jumlah: {{ count($request->cidb_codes) }}</u><br>
                                                <ul>
                                                    @foreach ($request->cidb_codes as $code)
                                                        <li>{!! $code->label2 !!}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif

                                @endif

                                @foreach ($request->files()->orderBy('label', 'desc')->get() as $file)
                                    <tr>
                                        <th class="bg-light text-secondary small text-uppercase fw-bold ps-4 py-3">
                                            {{ $file->label }}
                                        </th>
                                        <td class="ps-4 py-3" @if ($request->status == 'pending') colspan="2" @endif>
                                            <button
                                                class="btn btn-warning btn-sm btn-file-view d-flex align-items-center gap-2 shadow-sm"
                                                data-url="{{ $file->url . '/' . $file->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                    </path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13">
                                                    </line>
                                                    <line x1="16" y1="17" x2="8" y2="17">
                                                    </line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                                Lihat Dokumen
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-white mt-4 shadow-sm">
                    @if (App\CodeRequest::canList())
                        <a href="{{ route(isset($vendor) ? 'vendor.requests.index' : 'requests.index', isset($vendor) ? $vendor->id : null) }}"
                            class="btn btn-danger text-white fw-medium d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <span class="fw-bold">Senarai Permintaan</span>
                        </a>
                        {{-- <a href="{{ route(isset($vendor) ? 'vendor.requests.index' : 'requests.index', isset($vendor) ? $vendor->id : null) }}"
                            class="btn btn-outline-secondary d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Senarai Permintaan
                        </a> --}}
                    @endif

                    <div class="d-flex gap-2">
                        @if ($request->canProcess())
                            <a href="{{ asset('vendors/' . $request->vendor_id) }}"
                                class="btn btn-info text-white d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                Maklumat Lanjut
                            </a>
                            @if (isset($vendor))
                                {!! Former::open(route('vendor.requests.approve', ['vendor' => $vendor->id, 'requests' => $request->id]))->class('d-inline-block')->id('approveForm') !!}
                            @else
                                {!! Former::open(url('requests/' . $request->id . '/approve'))->class('d-inline-block')->id('approveForm') !!}
                            @endif
                            {!! Former::hidden('_method', 'PUT') !!}
                            <button type="button" class="btn btn-success d-flex align-items-center gap-2"
                                id="btn-approve">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Lulus
                            </button>
                            {!! Former::close() !!}

                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2"
                                id="btn-reject">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Tolak
                            </button>
                        @endif
                    </div>
                </div>
            </div> <!-- End col-12 -->
        </div> <!-- End Row -->


    @endsection

    @push('modals')
        <!-- APPROVE CONFIRMATION MODAL -->
        <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Pengesahan Kelulusan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light text-center">
                        <p class="mb-0 fs-5 text-dark">Adakah anda pasti untuk meluluskan permintaan ini?</p>
                    </div>
                    <div class="modal-footer bg-white border-top-0 justify-content-center">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success px-4" id="confirmApproveBtn">
                            Ya, Luluskan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF VIEWER MODAL -->
        <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content h-100 border-0 shadow-lg rounded-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Paparan Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 bg-light">
                        <iframe id="pdfIframe" src="" width="100%" height="100%"
                            style="border:none; min-height: 85vh;"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- REJECT MODAL -->
        @if ($request->canProcess())
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15">
                                    </line>
                                    <line x1="9" y1="9" x2="15" y2="15">
                                    </line>
                                </svg>
                                Tolak Permintaan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 bg-light">
                            <div id="rejectErrorContainer"></div>
                            <form id="rejectForm">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Alasan
                                        Penolakan</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3"
                                        placeholder="Sila nyatakan sebab penolakan..."></textarea>
                                </div>

                                @if ($templates)
                                    <div class="d-flex align-items-center my-3">
                                        <hr class="flex-grow-1 text-muted">
                                        <span class="px-3 text-muted small fw-bold text-uppercase bg-white rounded">Atau
                                            Pilih
                                            Templat</span>
                                        <hr class="flex-grow-1 text-muted">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Templat
                                            Penolakan</label>
                                        <div class="border rounded bg-white p-3 shadow-sm"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($templates as $template)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="cb{{ $template->id }}" name="template"
                                                        value="{{ $template->id }}">
                                                    <label class="form-check-label text-dark" for="cb{{ $template->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="right"
                                                        title="{{ $template->content }}">
                                                        {{ $template->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div class="modal-footer bg-white border-top-0">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2"
                                id="confirmRejectBtn">
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endpush

    @section('scripts')
        <script type="text/javascript">
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            document.addEventListener("DOMContentLoaded", function() {
                // Document Viewer Modal Logic
                document.body.addEventListener('click', function(e) {
                    var target = e.target.closest('.btn-file-view');

                    if (target) {
                        e.preventDefault();

                        var url = target.getAttribute('data-url');

                        if (url) {
                            // Open Modal
                            var iframe = document.getElementById('pdfIframe');
                            var modalEl = document.getElementById('pdfViewerModal');

                            if (iframe && modalEl) {
                                iframe.src = url;
                                var myModal = new bootstrap.Modal(modalEl);
                                myModal.show();
                            }
                        }
                    }
                });

                // Approve Modal Logic
                var approveBtn = document.getElementById('btn-approve');
                if (approveBtn) {
                    approveBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        var myModal = new bootstrap.Modal(document.getElementById('approveModal'));
                        myModal.show();
                    });
                }

                var confirmApproveBtn = document.getElementById('confirmApproveBtn');
                if (confirmApproveBtn) {
                    confirmApproveBtn.addEventListener('click', function(e) {
                        // Disable button and show loading state
                        var $btn = $(this);
                        var originalText = $btn.html();
                        $btn.prop('disabled', true).html('Sedang Memproses...');

                        // Submit the form
                        $("#approveForm").submit();
                    });
                }

                var pdfModal = document.getElementById('pdfViewerModal');
                if (pdfModal) {
                    pdfModal.addEventListener('hidden.bs.modal', function() {
                        document.getElementById('pdfIframe').src = '';
                    });
                }

                // Reject Modal Logic
                var rejectBtn = document.getElementById('btn-reject');
                if (rejectBtn) {
                    rejectBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Clear previous errors/inputs
                        $("#rejectErrorContainer").empty();
                        $("#rejectForm")[0].reset();

                        // Show Modal
                        var myModal = new bootstrap.Modal(document.getElementById('rejectModal'));
                        myModal.show();
                    });
                }

                $('#confirmRejectBtn').click(function() {
                    var reason = $("#reason").val();
                    var template = [];

                    $("input[name='template']:checked").each(function() {
                        template.push($(this).val());
                    });

                    if (reason != '' || template.length != 0) {
                        var $btn = $(this);
                        var originalText = $btn.html();
                        $btn.prop('disabled', true).html('Menolak...');

                        $.post('{{ route('requests.reject', $request->id) }}', {
                                reason: reason,
                                template: template
                            })
                            .done(function() {
                                window.location.href =
                                    '{{ isset($vendor) ? route('vendor.requests.index', $vendor->id) : route('requests.index') }}';
                            })
                            .fail(function() {
                                $("#rejectErrorContainer").html(`
                                <div class="alert alert-danger small mb-3">
                                    Ralat berlaku semasa memproses. Sila cuba lagi.
                                </div>
                            `);
                                $btn.prop('disabled', false).html(originalText);
                            });
                    } else {
                        $("#rejectErrorContainer").html(`
                        <div class="alert alert-danger d-flex align-items-center rounded-2 p-2 mb-3" role="alert">
                            <div class="small fw-bold">
                                Sila nyatakan sebab penolakan atau pilih templat.
                            </div>
                        </div>
                    `);
                    }
                });
            });
        </script>
    @endsection
