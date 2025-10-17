@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 80rem;
        }
    </style>
@endsection
@section('content')

    <div id="left-pane">

        <h2>
            @if (isset($vendor))
                {{ $vendor->name }}
            @else
                Syarikat
            @endif
            
            @if ($request->type == 'district')
                : Permintaan Kemaskini Alamat SSM
            @else 
                : Permintaan Kemaskini CIDB / MOF
            @endif

            <span class="label label-default pull-right">{{ App\CodeRequest::$statuses[$request->status] ?? '' }}</span>
        </h2>
        <table class="table table-bordered">
            <tr>
                <th style="border-color: #000000 !important;">Nama Syarikat</th>
                <td style="border-color: #000000 !important;" @if ($request->status == 'pending') colspan="2" @endif>{{ $request->vendor->name }}</td>
            </tr>
            <tr>
                <th style="border-color: #000000 !important;">No. Syarikat</th>
                <td style="border-color: #000000 !important;" @if ($request->status == 'pending') colspan="2" @endif>{{ $request->vendor->registration }}</td>
            </tr>
            <tr>
                <th style="border-color: #000000 !important;" class="col-lg-2">Tarikh Permintaan</th>
                <td style="border-color: #000000 !important;" @if ($request->status == 'pending') colspan="2" @endif>
                    {{ Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th style="border-color: #000000 !important;" class="col-lg-2">Jenis Kemaskini</th>
                <td style="border-color: #000000 !important;" @if ($request->status == 'pending') colspan="2" @endif>{{ App\CodeRequest::$types[$request->type] ?? '' }}</td>
            </tr>

            @if ($request->type == 'district')
                <tr>
                    <th style="border-color: #000000 !important;" class="col-lg-2">Alamat</th>
                    @if ($request->status == 'pending')
                        <td style="border-color: #000000 !important;" class="col-lg-5">
                            {{ $request->vendor->address ?? "" }}
                        </td>
                    @endif
                    <td style="border-color: #000000 !important;" class="bg-blue-selangor">{{ $request->data['address'] ?? "" }}</td>
                </tr>
                <tr>
                    <th style="border-color: #000000 !important;">Daerah</th>
                    @if ($request->status == 'pending')
                        <td style="border-color: #000000 !important;" class="col-lg-5">
                            {{ App\Vendor::$districts[!is_null($request->vendor->district_id) ? $request->vendor->district_id : '0'] }}
                        </td>
                    @endif
                    <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                        {{ App\Vendor::$districts[$request->data['district_id']] ?? "" }}
                        @if(!empty($request->data['state_id']) && $request->data['state_id'] > 0)
                            &nbsp;({{ App\Models\RefState::find($request->data['state_id'])->description }})
                        @endif
                        {{-- @php var_dump($request->data); die; @endphp --}}
                    </td>
                </tr>
            @endif

            @if ($request->type == 'email')
                <tr>
                    <th style="border-color: #000000 !important;">Alamat Emel</th>
                    @if ($request->status == 'pending')
                        <td style="border-color: #000000 !important;" class="col-lg-5">{{ $request->vendor->user->email }}</td>
                    @endif
                    <td style="border-color: #000000 !important;" class="bg-blue-selangor">{{ isset($request->data['email']) ? $request->data['email'] : 'Empty' }}
                    </td>
                </tr>
            @endif

            @if ($request->type == 'mof')

                @if (isset($request->data['mof_ref_no']))
                    <tr>
                        <th style="border-color: #000000 !important;">No Rujukan Pendaftaran MOF</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">{{ $request->vendor->mof_ref_no }}</td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">{{ $request->data['mof_ref_no'] }}</td>
                    </tr>
                @endif

                @if (isset($request->data['mof_start_date']))
                    <tr>
                        <th style="border-color: #000000 !important;">Tarikh Mula Aktif</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                @if (!empty($request->vendor->mof_start_date))
                                    {{ Carbon\Carbon::parse($request->vendor->mof_start_date)->format('d/m/Y') }}
                                @else
                                    {!! boolean_icon(false) !!}
                                @endif
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            {{ Carbon\Carbon::parse($request->data['mof_start_date'])->format('d/m/Y') }}</td>
                    </tr>
                @endif

                @if (isset($request->data['mof_end_date']))
                    <tr>
                        <th style="border-color: #000000 !important;">Tarikh Tamat Aktif</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                @if (!empty($request->vendor->mof_end_date))
                                    {{ Carbon\Carbon::parse($request->vendor->mof_end_date)->format('d/m/Y') }}
                                @else
                                    {!! boolean_icon(false) !!}
                                @endif
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            {{ Carbon\Carbon::parse($request->data['mof_end_date'])->format('d/m/Y') }}</td>
                    </tr>
                @endif

                @if (isset($request->data['bumiputera_company']))
                    <tr>
                        <th style="border-color: #000000 !important;">Syarikat Bumiputera</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5"><?= boolean_icon($request->vendor->mof_bumi) ?></td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">{!! boolean_icon($request->data['bumiputera_company']) !!}</td>
                    </tr>
                @endif

                @if (isset($request->data['mof_bumi']))
                    <tr>
                        <th style="border-color: #000000 !important;">Syarikat Bumiputera </th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5"><?= boolean_icon($request->vendor->mof_bumi) ?></td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">{!! boolean_icon($request->data['mof_bumi'] || $request->data['mof_bumi']) !!}</td>
                    </tr>
                @endif

                @if (isset($request->data['mof_codes']))
                    <tr>
                        <th style="border-color: #000000 !important;">Kod Bidang MOF</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                <u>Jumlah: {{ count($request->vendor->mof_codes) }}</u><br>
                                <ul>
                                    @foreach ($request->vendor->mof_codes as $code)
                                        <li><?= $code->code->label2 ?></li>
                                    @endforeach
                                </ul>
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
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

            @if ($request->type == 'cidb')

                @if (isset($request->data['cidb_ref_no']))
                    <tr>
                        <th style="border-color: #000000 !important;">No. Sijil CIDB</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">{{ $request->vendor->cidb_ref_no }}</td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">{{ $request->data['cidb_ref_no'] }}</td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_start_date']))
                    <tr>
                        <th style="border-color: #000000 !important;">Tarikh Mula Aktif</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                @if (!empty($request->vendor->cidb_start_date))
                                    {{ Carbon\Carbon::parse($request->vendor->cidb_start_date)->format('d/m/Y') }}
                                @else
                                    {!! boolean_icon(false) !!}
                                @endif
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            {{ Carbon\Carbon::parse($request->data['cidb_start_date'])->format('d/m/Y') }}</td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_end_date']))
                    <tr>
                        <th style="border-color: #000000 !important;">Tarikh Tamat Aktif</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                @if (!empty($request->vendor->cidb_end_date))
                                    {{ Carbon\Carbon::parse($request->vendor->cidb_end_date)->format('d/m/Y') }}
                                @else
                                    {!! boolean_icon(false) !!}
                                @endif
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            @if (!empty($request->data['cidb_end_date']))
                                {{ Carbon\Carbon::parse($request->data['cidb_end_date'])->format('d/m/Y') }}
                            @else
                                {!! boolean_icon(false) !!}
                            @endif
                        </td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_bumi']))
                    <tr>
                        <th style="border-color: #000000 !important;">Syarikat Bumiputera</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">{!! boolean_icon($request->vendor->cidb_bumi) !!}</td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">{!! boolean_icon($request->data['cidb_bumi']) !!}</td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_group']))
                    <tr>
                        <th style="border-color: #000000 !important;">Gred &amp; Bidang Pengkhususan</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                @forelse($request->vendor->cidbGrades()->orderBy('id')->get() as $grade)
                                    <u><b>{!! $grade->code->label !!}</b></u><br>
                                    <small>Jumlah Bidang Pengkhususan: {{ count($grade->children) }}</small><br><br>
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
                                    <span class="glyphicon glyphicon-remove"></span>
                                @endforelse
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            <?php if(count($request->data['cidb_group']) > 0) : ?>
                            @foreach ($request->data['cidb_group'] as $data)
                                <?php if (empty($data['code_id']) || empty($data['codes'])) {
                                    continue;
                                } ?>
                                <?php $grade = App\Code::find($data['code_id']); ?>
                                <u><b>{{ $grade->label }}</b></u><br>
                                <small>Jumlah Bidang Pengkhususan: {{ count($data['codes']) }}</small><br><br>
                                <?php $b_codes = App\Code::whereIn('id', $data['codes'])
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

                                <?php $ce_codes = App\Code::whereIn('id', $data['codes'])
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

                                <?php $me_codes = App\Code::whereIn('id', $data['codes'])
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

                                <?php $p_codes = App\Code::whereIn('id', $data['codes'])
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
                            @endforeach
                            <?php else : ?>
                            <?php echo boolean_icon(false); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_grade_b_id']))
                    <tr>
                        <th style="border-color: #000000 !important;">Gred CIDB Kateogri B</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                <?php if($request->vendor->cidb_grade_b) : ?>
                                <?php echo $request->vendor->cidb_grade_b->label; ?>
                                <?php else : ?>
                                <?php echo boolean_icon(false); ?>
                                <?php endif; ?>
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            <?php if($request->data['cidb_grade_b_id']) : ?>
                            {{ App\Code::find($request->data['cidb_grade_b_id'])->label }}
                            <?php else : ?>
                            <?php echo boolean_icon(false); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_grade_ce_id']))
                    <tr>
                        <th style="border-color: #000000 !important;">Gred CIDB Kategori CE</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                <?php if($request->vendor->cidb_grade_ce) : ?>
                                <?php echo $request->vendor->cidb_grade_ce->label; ?>
                                <?php else : ?>
                                <?php echo boolean_icon(false); ?>
                                <?php endif; ?>
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            <?php if($request->data['cidb_grade_ce_id']) : ?>
                            {{ App\Code::find($request->data['cidb_grade_ce_id'])->label }}
                            <?php else : ?>
                            <?php echo boolean_icon(false); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_grade_me_id']))
                    <tr>
                        <th style="border-color: #000000 !important;">Gred CIDB Kategori ME</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                <?php if($request->vendor->cidb_grade_me) : ?>
                                <?php echo $request->vendor->cidb_grade_me->label; ?>
                                <?php else : ?>
                                <?php echo boolean_icon(false); ?>
                                <?php endif; ?>
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
                            <?php if($request->data['cidb_grade_me_id']) : ?>
                            {{ App\Code::find($request->data['cidb_grade_me_id'])->label }}
                            <?php else : ?>
                            <?php echo boolean_icon(false); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                @endif

                @if (isset($request->data['cidb_codes']))
                    <tr>
                        <th style="border-color: #000000 !important;">Kod Bidang CIDB</th>
                        @if ($request->status == 'pending')
                            <td style="border-color: #000000 !important;" class="col-lg-5">
                                <u>Jumlah: {{ count($request->vendor->cidb_codes) }}</u><br>
                                <ul>
                                    @foreach (App\Code::whereIn('id', $request->vendor->cidb_codes->pluck('code_id'))->orderBy('code')->get() as $code)
                                        <li>{!! $code->label2 !!}</li>
                                    @endforeach
                                </ul>
                            </td>
                        @endif
                        <td style="border-color: #000000 !important;" class="bg-blue-selangor">
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
                    <th style="border-color: #000000 !important;">{{ $file->label }}</th>
                    <td style="border-color: #000000 !important;" @if ($request->status == 'pending') colspan="2" @endif>
                        <button class="btn btn-warning btn-file-view"
                            data-url="{{ $file->url . '/' . $file->name }}">Lihat</button>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="well">
            @if ($request->canProcess())
                <a href="{{ asset('vendors/' . $request->vendor_id) }}" class="btn btn-success">Maklumat Lanjut</a>
                @if (isset($vendor))
                    {!! Former::open(
                        route('vendor.requests.approve', ['vendor' => $vendor->id, 'requests' => $request->id]),
                    )->class('form-inline') !!}
                @else
                    {!! Former::open(url('requests/' . $request->id . '/approve'))->class('form-inline') !!}
                @endif
                {!! Former::hidden('_method', 'PUT') !!}
                <button type="button" class="btn btn-primary confirm">Lulus</button>
                {!! Former::close() !!}

                <a href="#" class="btn btn-danger" id="btn-reject">Tolak</a>
            @endif

            @if (App\CodeRequest::canList())
                <a href="{{ route(isset($vendor) ? 'vendor.requests.index' : 'requests.index', isset($vendor) ? $vendor->id : null) }}"
                    class="btn btn-default pull-right">Senarai Permintaan Kemaskini</a>
            @endif
        </div>
    </div>


    <div id="right-pane" style="display: none;">
        <button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
        <div id="doc-view"></div>
    </div>

    @if ($request->canProcess())
        <div id="rejectForm" class="hidden">
            <form id="myForm" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2">Alasan Penolakan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="reason" name="reason" />
                    </div>
                </div>
                @if ($templates)
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <hr>
                        </div>
                        <div class="col-sm-2 text-center" style="margin: 10px 0">
                            atau</div>
                        <div class="col-sm-5">
                            <hr>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Templat Penolakan</label>
                        <div class="col-sm-10" {{-- style="height:300px;max-height:350px;overflow:auto;" --}}>
                            @foreach ($templates as $template)
                                <div class="col-sm-12">
                                    <label class="checkbox-inline" data-html="true" data-toggle="tooltip"
                                        data-placement="right" title="{{ $template->content }}">
                                        <input type="checkbox" id="cb{{ $template->id }}" name="template"
                                            value="{{ $template->id }}"> {{ $template->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </form>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/pdfobject.min.js') }}"></script>
    <script src="{{ asset('js/displayfile.js') }}"></script>
    <script type="text/javascript">
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    </script>
    <script>
        var form = $("#rejectForm").html();
        $("#btn-reject").click(function(e) {
            dialog = bootbox.confirm({
                message: form,
                buttons: {
                    'cancel': {
                        label: 'Batal',
                        className: 'btn-default'
                    },
                    'confirm': {
                        label: 'Tolak',
                        className: 'btn-primary'
                    }
                },
                callback: function(result) {
                    var reason = dialog[0].querySelector("[name=reason]").value;
                    var template = Array.from(dialog[0].querySelectorAll(
                        "input[type=checkbox][name=template]:checked"), e => e.value);

                    if (result && (reason != '' || template.length != 0)) {
                        $.post('{{ route('requests.reject', $request->id) }}', {
                                reason: reason,
                                template: template
                            })
                            .success(function() {
                                window.location.href =
                                    '{{ isset($vendor) ? route('vendor.requests.index', $vendor->id) : route('requests.index') }}';
                            })
                    }
                }
            });
        });
    </script>
@endsection
