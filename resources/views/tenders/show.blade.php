@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tender.form.css') }}" rel="stylesheet">
@endsection

@section('content')

    @include('tenders._menu')

    <div class="tender-ref-number">{{ $tender->ref_number }}</div>
    <h2 class="tender-title">{{ $tender->name }}</h2>

    @include('tenders._notification')

    @if ($tender->canShowTabs())
        <ul class="nav nav-tabs nav-justified hidden-print">
            <li class="active"><a href="{{ asset('tenders/' . $tender->id) }}">Maklumat
                    {{ App\Tender::$types[$tender->type] }}</a></li>
            <li><a href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Maklumat Syarikat</a></li>
            @if (Auth::check() &&
                    $tender->canException() &&
                    auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                <li><a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}">Maklumat Kebenaran Khas <span
                            class="badge">{{ $tender->exceptions()->where('status', 0)->count() }}</span></a></li>
            @endif
        </ul>
    @endif

    <div class="row stacked-form">
        <div class="col-lg-2 hidden-print">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="@if (!Session::get('ErrorRequest') and !isset($active_prestasi_tab)) active @endif">
                    <a href="#tf-main" aria-controls="home" role="tab" data-toggle="tab">Maklumat
                        {{ App\Tender::$types[$tender->type] }}</a>
                </li>
                <li role="presentation">
                    <a href="#tf-syarat" aria-controls="home" role="tab" data-toggle="tab">Syarat
                        {{ App\Tender::$types[$tender->type] }}</a>
                </li>
                @if (count($tender->siteVisits) > 0)
                    <li role="presentation"><a href="#tf-lawatan" aria-controls="messages" role="tab"
                            data-toggle="tab">Lawatan Tapak</a></li>
                @endif
                @if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
                    <li role="presentation"
                        class="{{ Auth::check() && Auth::user()->vendor && $tender->codeErrors(Auth::user()->vendor_id) ? 'bg-danger' : '' }}">
                        <a href="#tf-kod" aria-controls="settings" role="tab" data-toggle="tab"
                            class="{{ Auth::check() && Auth::user()->vendor && $tender->codeErrors(Auth::user()->vendor_id) ? 'text-danger' : '' }}">Kod-Kod
                            Bidang</a>
                    </li>
                @endif
                @if (count($tender->table_files) > 0)
                    <li role="presentation">
                        <a href="#tf-doc1" aria-controls="settings" role="tab" data-toggle="tab">
                            Dokumen Meja Terkawal
                            <span class="badge">{{ $tender->files()->where('public', 1)->count() }}</span>
                        </a>
                    </li>
                @endif
                @if (Auth::check() && $tender->canShowFiles(Auth::user()->vendor_id))
                    <li role="presentation">
                        <a href="#tf-doc2" aria-controls="settings" role="tab" data-toggle="tab">
                            Dokumen {{ App\Tender::$types[$tender->type] }}
                            <span class="badge">{{ $tender->files()->where('public', 0)->count() }}</span>
                        </a>
                    </li>
                @endif
                @if (Auth::check() && $tender->canUpdate() && $tender->invitation)
                    <li role="presentation">
                        <a href="#tf-invites" aria-controls="settings" role="tab" data-toggle="tab">Senarai Jemputan</a>
                    </li>
                @endif
                @if (Auth::check() && $tender->canUpdate())
                    <li role="presentation">
                        <a href="#tf-history" aria-controls="settings" role="tab" data-toggle="tab">Sejarah
                            Pengubahan</a>
                    </li>
                @endif
                <li role="presentation">
                    <a href="#tf-news" aria-controls="home" role="tab" data-toggle="tab">Makluman / Ralat <span
                            class="badge">{{ $tender->news()->count() }}</span></a>
                </li>
                @if (auth()->check())
                    @if (
                        !$tender->matchCidbCodesInverse(Auth::user()->vendor_id) &&
                            $tender->matchCidbGrade(auth()->user()->vendor_id) &&
                            $tender->attendVisits(auth()->user()->vendor_id) &&
                            $tender->attendBriefing(auth()->user()->vendor_id))
                        <li role="presentation">
                            <a href="#tf-exception" aria-controls="home" role="tab" data-toggle="tab">Kebenaran Khas
                            </a>
                        </li>
                    @endif
                @endif
                <li role="presentation">
                    <a href="#tf-officer" aria-controls="home" role="tab" data-toggle="tab">Pegawai Bertanggungjawab</a>
                </li>
                {{-- Tab - Penilaian Prestasi Syarikat --}}
                @if ($tender_winner)
                    <li role="penilaian-prestasi" class="@if (Session::get('ErrorRequest') or isset($active_prestasi_tab)) active @endif">
                        <a href="#tf-penilaian-prestasi" aria-controls="home" role="tab" data-toggle="tab">Penilaian
                            Prestasi Syarikat</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="tab-content col-lg-10">

            <div role="tabpanel" class="tab-pane" id="tf-officer">
                @if ($tender->hasOfficer())
                    <table class="table table-bordered">
                        <thead class="bg-blue-selangor">
                            <tr>
                                <th colspan="2" class="text-center">Pegawai Bertanggungjawab 1</th>
                                <th colspan="2" class="text-center">Pegawai Bertanggungjawab 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $tender->creator->name }}</td>
                                <th>Nama</th>
                                <td>{{ $tender->officer->name }}</td>
                            </tr>
                            <tr>
                                <th>E-mel</th>
                                <td>{{ $tender->creator->email }}</td>
                                <th>E-mel</th>
                                <td>{{ $tender->officer->email }}</td>
                            </tr>
                            <tr>
                                <th>No. Tel</th>
                                <td>{{ $tender->creator->tel }}</td>
                                <th>No. Tel</th>
                                <td>{{ $tender->officer->tel }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $tender->creator->department }}</td>
                                <th>Jabatan</th>
                                <td>{{ $tender->officer->department }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="table table-bordered">
                        <thead class="bg-blue-selangor">
                            <tr>
                                <th colspan="2" class="text-center">Pegawai Bertanggungjawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $tender->creator->name }}</td>
                            </tr>
                            <tr>
                                <th>E-mel</th>
                                <td>{{ $tender->creator->email }}</td>
                            </tr>
                            <tr>
                                <th>No. Tel</th>
                                <td>{{ $tender->creator->tel }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $tender->creator->department }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
            @if (auth()->check())
                <div role="tabpanel" class="tab-pane" id="tf-exception">
                    @if ($tender->canException())
                        <table class="table table-bordered">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th class="col-xs-1">Tarikh Permohonan</th>
                                    <th>Tajuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($exception)
                                    <tr>
                                        <td>{{ $exception->updated_at ? Carbon\Carbon::parse($exception->updated_at)->format('d/m/Y') : Carbon\Carbon::parse($exception->created_at)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $exception->files[0]->label ?? '' }}</td>
                                        <td>
                                            @if ($exception->status == 2)
                                                <b>{{ $exception->getStatus() }}</b> <br> Alasan :- <br>
                                                @if ($exception->rejection_reason)
                                                    Catatan : {{ $exception->rejection_reason }}
                                                @endif
                                                @if ($exception->rejection_template_id)
                                                    <br>
                                                    <ol>
                                                        @foreach (json_decode($exception->rejection_template_id, true) as $reject_id)
                                                            @foreach ($templates as $template)
                                                                @if ($template['id'] == $reject_id)
                                                                    <li style="text-decoration: underline;">
                                                                        {{ $template['title'] }}
                                                                    </li>
                                                                    {!! $template['content'] !!}
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                @endif
                                            @else
                                                <b>{{ $exception->getStatus() }}</b>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Tiada Surat Kebenaran Khas</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @if (!$exception || $exception->status == 2)
                            <div class="container-fluid pt-2 pr-2">
                                <hr>
                                <div>
                                    &emsp;
                                </div>
                                <div class="pt-2">
                                    <h1 class="tender-title">Kebenaran Khas</h1>
                                    <form action="{{ route('tender.store.exception') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group required">
                                            <label for="exception_letter" class="control-label col-lg-3 col-sm-3">Surat
                                                Kebenaran
                                                Khas<sup>*</sup></label>
                                            <div class="col-lg-9 col-sm-9">
                                                <input type="file" name="exception_letter" id="exception_letter"
                                                    required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="tender_id" id="tender_id"
                                            value="{{ $tender->id }}">

                                        <div class="pull-right">
                                            <input type="submit" value="Hantar" class="btn btn-primary confirm">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">Kebenaran Khas tidak dibenarkan bagi tender/sebut harga ini.</div>
                    @endif
                </div>
            @endif
            <div role="tabpanel" class="tab-pane" id="tf-news">

                @php
                    $list_ralat_news =
                        $tender
                            ->news()
                            ->wherePublish(1)
                            ->orderBy('published_at', 'asc')
                            ->get() ?? [];
                @endphp

                @if (count($list_ralat_news) > 0)
                    <table class="table table-bordered">
                        <thead class="bg-blue-selangor">
                            <tr>
                                <th class="col-xs-1">Tarikh</th>
                                <th>Tajuk</th>
                                <th class="col-xs-1">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get() as $news)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($news->published_at)->format('d/m/Y') }}</td>
                                    <td>{{ $news->title }}</td>
                                    <td>{{ link_to_route('news.show', 'Selanjutnya', $news->id, ['class' => 'btn btn-xs btn-primary']) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Tiada makluman / ralat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    @if (Auth::check() && $tender->canUpdate())
                        <div class="container-fluid pt-2 pr-2">
                            <div>
                                &emsp;
                            </div>
                            <div class="pt-2">
                                <h1 class="tender-title">Berita Baru</h1>
                                {!! Former::open(url('news')) !!}
                                {!! Former::text('title')->label('Tajuk')->required() !!}

                                <div class="form-group required">
                                    <label for="notification" class="control-label col-lg-3 col-sm-3">Kandungan
                                        <sup>*</sup></label>
                                    <div class="col-lg-9 col-sm-9">
                                        <textarea class="form-control" rows="4" required="true" id="notification" name="notification">{!! isset($news) ? $news->notification : '' !!}</textarea>
                                        <div id="notification-editor" class="summernote">{!! isset($news) ? $news->notification : '' !!}</div>
                                    </div>
                                </div>

                                @if (Auth::user()->hasRole('Admin'))
                                    {!! Former::select('organization_unit_id')->label('Agensi')->options(App\OrganizationUnit::all()->pluck('name', 'id'))->required() !!}
                                @endif

                                <input type="hidden" name="tender_id" id="tender_id" value="{{ $tender->id }}">
                                <input type="hidden" name="fromTenderRequest" id="fromTenderRequest" value="999">

                                <div class="pull-right">
                                    <input type="submit" value="Hantar" class="btn btn-primary confirm">
                                </div>
                                {!! Former::close() !!}
                            </div>

                        </div>
                    @else
                        <table class="table table-bordered">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th class="col-xs-1">Tarikh</th>
                                    <th>Tajuk</th>
                                    <th class="col-xs-1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">Tiada makluman / ralat</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                @endif


                {{-- <table class="table table-bordered">
				<thead class="bg-blue-selangor">
					<tr>
						<th class="col-xs-1">Tarikh</th>
						<th>Tajuk</th>
						<th class="col-xs-1">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get() as $news)
						<tr>
							<td>{{ Carbon\Carbon::parse($news->published_at)->format('d/m/Y')}}</td>
							<td>{{ $news->title }}</td>
							<td>{{ link_to_route('news.show', 'Selanjutnya', $news->id, ['class' => 'btn btn-xs btn-primary']) }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="3">Tiada makluman / ralat</td>
						</tr>
					@endforelse
				</tbody>
			</table> --}}
            </div>
            <div role="tabpanel" class="tab-pane @if (!Session::get('ErrorRequest') and !isset($active_prestasi_tab)) ) active @endif" id="tf-main">
                <h3 class="visible-print">Maklumat {{ App\Tender::$types[$tender->type] }}</h3>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th class="col-xs-3">Petender</th>
                        <td>{{ $tender->tenderer->name }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">No. {{ App\Tender::$types[$tender->type] }}</th>
                        <td>{{ $tender->ref_number }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">Tarikh Iklan</th>
                        <td>{{ \Carbon\Carbon::parse($tender->advertise_start_date)->format('j M Y') }} -
                            {{ \Carbon\Carbon::parse($tender->advertise_stop_date)->format('j M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">Tarikh Jual</th>
                        <td>{{ \Carbon\Carbon::parse($tender->document_start_date)->format('j M Y') }} -
                            {{ \Carbon\Carbon::parse($tender->document_stop_date)->format('j M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">Tarikh Tutup</th>
                        <td>{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">Masa Tutup</th>
                        <td>12:00 PM</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">Tempat Hantar</th>
                        <td>
                            {!! nl2br($tender->submission_location_address) !!}
                        </td>
                    </tr>
                    @if ($tender->hasBriefing())
                        <tr>
                            <th class="col-xs-3">Tarikh &amp; Masa Taklimat</th>
                            <td>{{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="col-xs-3">Alamat Taklimat</th>
                            <td>
                                {!! nl2br($tender->briefing_address) !!}
                                @if ($tender->briefing_required)
                                    <br><br><small><span class="glyphicon glyphicon-ok"></span> Kehadiran taklimat adalah
                                        diwajibkan</small>
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th class="col-xs-3">Kebenaran Khas</th>
                        <td>
                            @if ($tender->allow_exception)
                                <span class="glyphicon glyphicon-ok"></span>
                            @else
                                <span class="glyphicon glyphicon-remove"></span>
                            @endif
                        </td>
                    </tr>
                    @if ($tender->only_bumiputera)
                        <tr>
                            <th class="col-xs-3">Syarikat Bumiputera Sahaja</th>
                            <td><span class="glyphicon glyphicon-ok"></span></td>
                        </tr>
                    @endif
                    
                    @if ($tender->only_selangor == 2)
                        <tr>
                            <th class="col-xs-3">Syarikat Negeri</th>
                            <td>
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;{{ strtoupper( $tender->getNegeriList() ) }} SAHAJA
                            </td>
                        </tr>
                    @elseif ($tender->only_selangor == 3)
                        <tr>
                            <th class="col-xs-3">Syarikat Negeri</th>
                            <td>
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;SELURUH MALAYSIA
                            </td>
                        </tr>
                    @endif

                    @if ($tender->district_id != null && $tender->district_id > 0)
                        <tr>
                            <th class="col-xs-3">Syarikat Dibawah Daerah Sahaja</th>
                            <td>
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;{{ strtoupper( App\Vendor::$districts[$tender->district_id] ) }} SAHAJA
                            </td>
                        </tr>
                    @elseif($tender->district_id == null && $tender->getDaerahListExist() === true && $tender->only_selangor != 3)
                        <tr>
                            <th class="col-xs-3">Syarikat Dibawah Daerah Sahaja</th>
                            <td>
                                <span
                                    class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;{{ strtoupper($tender->getDaerahList()) }} SAHAJA
                            </td>
                        </tr>
                    @elseif($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1)
                        <tr>
                            <th class="col-xs-3">Syarikat Dibawah Daerah Sahaja</th>
                            <td>
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;SELURUH SELANGOR
                            </td>
                        </tr>
                    @endif
                    
                    <tr>
                        <th class="col-xs-3">Harga Dokumen</th>
                        <td>RM {{ number_format($tender->price, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="tf-syarat">
                <h3 class="visible-print">Syarat {{ App\Tender::$types[$tender->type] }}</h3>
                {{-- {!! nl2br( $tender->tender_rules, '<b><strong><i><em><u><p><ul><ol><li>' ) !!} --}}
                {!! $tender->tender_rules !!}
            </div>

            @if (count($tender->siteVisits) > 0)
                <?php $index = 1; ?>
                <div role="tabpanel" class="tab-pane" id="tf-lawatan">
                    <h3 class="visible-print">Lawatan Tapak</h3>
                    <table class="table table-hover table-compact">
                        <thead class="bg-blue-selangor">
                            <tr>
                                <th>Bil.</th>
                                <th>Tempat Berkumpul</th>
                                <th>Alamat Lawatan Tapak</th>
                                <th>Tarikh &amp; Waktu</th>
                                <th>Wajib Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tender->siteVisits->sortBy('id') as $visit)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{!! nl2br($visit->meetpoint) !!}</td>
                                    <td>{!! nl2br($visit->address) !!}</td>
                                    <td>{{ Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}</td>
                                    <td>
                                        @if ($visit->required)
                                            <span class="glyphicon glyphicon-ok"></span>
                                        @else
                                            <span class="glyphicon glyphicon-remove"></span>
                                        @endif
                                    </td>
                                </tr>
                                <?php $index++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
                <div role="tabpanel" class="tab-pane" id="tf-kod">
                    <h3 class="visible-print">Kod-Kod Bidang</h3>
                    @if (count($tender->mof_codes) > 0)
                        <table class="table table-bordered table-condensed">
                            <?php $max_count = count($tender->mof_code_groups); ?>
                            <tr>
                                <th class="col-xs-3">Kod Bidang MOF</th>
                                <td>
                                    @foreach ($tender->mof_code_groups as $order => $data)
                                        @foreach ($data['codes'] as $id => $label)
                                            @if ($order < $max_count)
                                                <br>
                                            @endif
                                        @endforeach

                                        {!! implode(
                                            '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
                                            tender_vendor_codes($data['codes'], Auth::user()),
                                        ) !!}
                                        @if ($order != $max_count)
                                            <br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        @if (count($tender->cidb_grades) > 0)
                            <br><span
                                class="label label-success">{{ $tender->mof_cidb_rule == 'or' ? 'ATAU' : 'DAN' }}</span><br><br>
                        @endif
                    @endif

                    @if (count($tender->cidb_grades) > 0)
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th class="col-xs-3">Gred CIDB</th>
                                <td>
                                    <ul>
                                        @foreach ($tender->cidb_grades as $code)
                                            <li>{!! tender_cidb_grade($code->code, Auth::user()) !!}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                            @if (count($tender->cidb_codes) > 0)
                                <?php $max_count = count($tender->cidb_code_groups); ?>
                                <tr>
                                    <th class="col-xs-3">Bidang Pengkhususan CIDB</th>
                                    <td>
                                        @foreach ($tender->cidb_code_groups as $order => $data)
                                            {!! implode(
                                                '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
                                                tender_vendor_codes($data['codes'], Auth::user()),
                                            ) !!}
                                            @if ($order != $max_count)
                                                <br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        </table>
                    @endif
                </div>
            @endif

            <div role="tabpanel" class="tab-pane" id="tf-doc1">
                <h3 class="visible-print">Dokumen Meja Terkawal</h3>
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead class="bg-blue-selangor">
                        <tr>
                            <th>Nama</th>
                            <th>Saiz</th>
                            <th>Jenis</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tender->tableFiles as $upload)
                            <tr>
                                <td>{{ $upload->label }}</td>
                                <td>{{ $upload->size }}</td>
                                <td>{{ $upload->type }}</td>
                                <td>
                                    <a href="{{ $upload->url }}" class="btn btn-primary btn-xs" download>Muat Turun</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (Auth::check() && $tender->canShowFiles(Auth::user()->vendor_id))
                <div role="tabpanel" class="tab-pane" id="tf-doc2">
                    <h3 class="visible-print">Dokumen {{ App\Tender::$types[$tender->type] }}</h3>
                    @if (count($tender->tender_files) > 0)
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th>Nama</th>
                                    <th>Saiz</th>
                                    <th>Jenis</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tender->tenderFiles as $upload)
                                    <tr>
                                        <td>{{ $upload->label }}</td>
                                        <td>{{ $upload->size }}</td>
                                        <td>{{ $upload->type }}</td>
                                        <td>
                                            <a href="{{ $upload->url }}" class="btn btn-primary btn-xs" download>Muat
                                                Turun</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">Tiada fail untuk dimuat turun, sila rujuk syarat tender atau
                            berhubung dengan agensi yang berkenaan.</div>
                    @endif
                </div>
            @endif

            @if ($tender->invitation && $tender->canUpdate())
                <div role="tabpanel" class="tab-pane" id="tf-invites">
                    <h3 class="visible-print">Senarai Jemputan</h3>
                    {!! Former::open(action('TendersController@updateInvites', $tender->id))->class('form-inline') !!}
                    @if (count($invites) > 0)
                        <table class="table table-bordered">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th>Nama Syarikat</th>
                                    <th class="col-lg-1">Padam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invites as $invite)
                                    <tr>
                                        <td>
                                            <strong>{{ $invite->vendor->name }}</strong>
                                            <br><a
                                                href="{{ route('tenders.vendor', ['tender_id' => $tender->id, 'id' => $invite->vendor_id]) }}"
                                                class="btn btn-primary btn-xs">Maklumat Syarikat</a>
                                        </td>
                                        <td>
                                            @if ($tender->hasParticipate($invite->vendor_id))
                                                <span class="glyphicon glyphicon-ban-circle"></span>
                                            @else
                                                <input type="checkbox" name="deleted_invites[]"
                                                    value="{{ $invite->vendor_id }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">Tiada Syarikat dijemput.</div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" id="invite_ids" name="invite_ids" placeholder="Tambah syarikat">
                            <small>Cari nama syarikat yang ingin dijemput dan tekan "Simpan Maklumat Jemputan"</small>
                        </div>
                        <div class="col-lg-6">
                            <input type="submit" class="btn btn-primary pull-right confirm"
                                value="Simpan Maklumat Jemputan">
                        </div>
                    </div>
                    {!! Former::close() !!}
                </div>
            @endif

            @if (Auth::check() && $tender->canUpdate())
                <div role="tabpanel" class="tab-pane hidden-print" id="tf-history">
                    @if (count($histories) > 0)
                        <table class="table table-bordered">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Keterangan</th>
                                    <th>Pengguna</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $history)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $history->label }}</td>
                                        <td>
                                            @if ($history->user)
                                                {{ $history->user->name }}
                                            @else
                                                {{ boolean_icon($history->user) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">Tiada maklumat sejarah pengubahan.</div>
                    @endif
                </div>
            @endif

            @if (isset($tender_winner->vendor))
                {{-- START:Content - Tab - Penilaian Prestasi Syarikat --}}
                <div role="tabpanel" class="tab-pane @if (Session::get('ErrorRequest') or isset($active_prestasi_tab)) active @endif"
                    id="tf-penilaian-prestasi">

                    {{-- Error Box --}}
                    @if (count($errors) > 0)
                        <div class="border rounded bg-danger text-white px-1 pt-1 mb-1 mt-1">
                            <strong>Amaran!</strong><br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- START:Accordion - Borang Penilaian Syarikat --}}
                    @include('tenders.petender-performance.form')

                    {{-- Table - Senarai Prestasi Syarikat based on Tender --}}
                    @include('tenders.petender-performance.table')

                </div>
                {{-- END:Content - Tab - Penilaian Prestasi Syarikat --}}
            @endif

        </div>
    </div>

    @if ($tender->canPurchase())
        <div class="btn-group pull-right">
            {{ link_to_route('tenders.buy', 'Tambah Kepada Senarai Tempahan', [$tender->id], ['class' => 'btn btn-primary']) }}
        </div>
    @endif

@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    {{-- <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script> --}}
    <script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript">
        $("#invite_ids").selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div>' +
                        '<strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
                        '<br><small>Alamat Emel: <strong>' + escape(item.email) +
                        '</strong> &bullet; Tarikh Tamat Langganan: <strong>' +
                        moment(item.expiry_date, 'YYYY-MM-DD').format('DD/MM/YY') + '</strong></small>' +
                        '</div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/vendors/select?q=' + query,
                    type: 'GET',
                    success: function(res) {
                        callback(res);
                    },
                    error: function() {
                        callback();
                    }
                })
            }
        });

        CKEDITOR.replace('notification', {
            toolbarGroups: [{
                    name: 'document',
                    groups: ['mode', 'document', 'doctools']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                },
                {
                    name: 'editing',
                    groups: ['find', 'selection', 'spellchecker', 'editing']
                },
                {
                    name: 'forms',
                    groups: ['forms']
                },
                {
                    name: 'insert',
                    groups: ['insert']
                },
                '/',
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
                },
                {
                    name: 'links',
                    groups: ['links']
                },
                '/',
                {
                    name: 'styles',
                    groups: ['styles']
                },
                {
                    name: 'colors',
                    groups: ['colors']
                },
                {
                    name: 'tools',
                    groups: ['tools']
                },
                {
                    name: 'others',
                    groups: ['others']
                },
                {
                    name: 'about',
                    groups: ['about']
                }
            ],
            removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField'
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        let sum = 0;
        const scales = {
            scale_1: 0,
            scale_2: 0,
            scale_3: 0,
            scale_4: 0,
            scale_5: 0,
            scale_6: 0
        }

        const sumEle = window.document.getElementById('sum');
        const calcEle = window.document.getElementById('calc');
        const scaleInputNoOne = window.document.getElementsByName('scale_1');
        const scaleInputNoTwo = window.document.getElementsByName('scale_2');
        const scaleInputNoThree = window.document.getElementsByName('scale_3');
        const scaleInputNoFour = window.document.getElementsByName('scale_4');
        const scaleInputNoFive = window.document.getElementsByName('scale_5');
        const scaleInputNoSix = window.document.getElementsByName('scale_6');

        const updateMarks = (key, preScale, nextScale) => {
            const updateSum = sum - preScale + Number(nextScale)
            sumEle.value = updateSum
            sumEle.innerHTML = updateSum
            scales[key] = nextScale;
            sum = updateSum
            calcEle.value = Number(updateSum / 30 * 100).toFixed(2)
            // calcEle.innerHTML = Number(updateSum / 30 * 100).toFixed(2)
        }

        scaleInputNoOne.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_1', scales.scale_1, e.target.value)
            })
        });
        scaleInputNoTwo.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_2', scales.scale_2, e.target.value)
            })
        });
        scaleInputNoThree.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_3', scales.scale_3, e.target.value)
            })
        });
        scaleInputNoFour.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_4', scales.scale_4, e.target.value)
            })
        });
        scaleInputNoFive.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_5', scales.scale_5, e.target.value)
            })
        });
        scaleInputNoSix.forEach(element => {
            element.addEventListener('click', (e) => {
                updateMarks('scale_6', scales.scale_6, e.target.value)
            })
        });
    </script>
    <script>
        $(function() {
            $('#criteria_1, #value2').keyup(function() {
                var value1 = parseFloat($('#value1').val()) || 0;
                var value2 = parseFloat($('#value2').val()) || 0;
                $('#sum').val(value1 + value2);
            });
        });
    </script>
    <script>
        const jenisSlectEle = window.document.getElementById("jenis-select")
        const jenisInputEle = window.document.getElementById("jenis-input")

        jenisSlectEle.addEventListener('change', (e) => {
            if (e.target.value === 'Lain - lain') {
                jenisInputEle.classList.replace("hidden", "display")
            } else {
                jenisInputEle.classList.replace("display", "hidden")
                jenisInputEle.value = "";
            }
        })
    </script>
@endsection
