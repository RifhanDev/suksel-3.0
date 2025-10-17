@extends('layouts.default')
@section('content')

    @include('tenders._menu')

    <div class="tender-ref-number">{{ $tender->ref_number }}</div>
    <h2 class="tender-title">{{ $tender->name }}</h2>

    @include('tenders._notification')

    @if (Auth::user() && $tender->canShowTabs())
        <ul class="nav nav-tabs nav-justified">
            <li><a href="{{ asset('tenders/' . $tender->id) }}">Maklumat Tender / Sebut Harga</a></li>
            <li class="active"><a href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Maklumat Syarikat</a></li>
            @if (Auth::check() &&
                    $tender->canException() &&
                    auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                <li><a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}">Maklumat Kebenaran Khas <span
                            class="badge">{{ $tender->exceptions()->where('status', 0)->count() }}</span></a></li>
            @endif
        </ul>
    @endif

    <br>

    @if (Auth::user()->hasRole('Admin'))
        <ul class="nav nav-pills">
            <li{{ !isset($purchases) ? ' class="active"' : '' }}><a
                    href="{{ asset('tenders/' . $tender->id . '/eligibles') }}">Senarai Layak</a></li>
                <li{{ isset($purchases) ? ' class="active"' : '' }}><a
                        href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Pembelian Dokumen</a></li>
        </ul>
    @endif
    <br>
	
    {!! Former::open(url('tenders/' . $tender->id . '/vendors'))->class('form-inline') !!}
    @if (count($purchases) > 0)
        <?php $count = 1; ?>
        <table class="table table-bordered">
            <thead class="bg-blue-selangor">
                <tr>
                    <th>Bil.</th>
                    <th>Nama Syarikat</th>
                    @if (!$tender->only_advertise)
                        <th>Beli Dokumen</th>
                    @endif

                    @if ($tender->hasBriefing())
                        <th>Taklimat <input type="checkbox" class="checker" data-target="briefing"></th>
                    @endif

                    @if (count($tender->siteVisits()->get()) > 0)
                        <?php $index = 1; ?>
                        @foreach ($tender->siteVisits()->orderBy('id', 'asc')->get() as $visit)
                            <th>LT {{ $index }} <input type="checkbox" class="checker"
                                    data-target="visit-{{ $visit->id }}"></th>
                            <?php $index++; ?>
                        @endforeach
                    @endif

                    @if ($tender->canShowPrices())
                        <th>Label</th>
                        <th>Harga</th>
                        <th>Berjaya</th>
                        {{-- Check if there are winner for this tender yet. If yes, this column will appear --}}
                        @if ($count_winner > 0)
                            <th>Gred / Prestasi</th>
                        @endif
                    @endif
                    <th>Padam <input type="checkbox" class="checker" data-target="delete"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>
                            <strong>{{ $purchase->vendor->name }}</strong>
                            @if ($purchase->ref_number)
                                <br>No. Siri Dokumen: {{ $purchase->ref_number }}
                            @endif
                            <br>
                            <a href="{{ asset('tenders/' . $tender->id . '/vendor/' . $purchase->vendor_id) }}"
                                class="btn btn-primary btn-xs">Maklumat Syarikat</a>
                            @if ($purchase->exception)
                                <br><br><span class="glyphicon glyphicon-star"></span> <small>Kebenaran Khas</small>
                            @endif
                        </td>

                        @if (!$tender->only_advertise)
                            <td>
                                @if ($purchase->participate)
                                    <span class="glyphicon glyphicon-ok"></span>
                                @else
                                    <span class="glyphicon glyphicon-remove"></span>
                                @endif
                            </td>
                        @endif

                        @if ($tender->hasBriefing())
                            <td><input type="checkbox" class="briefing"
                                    name="briefing[{{ $purchase->id }}]"@if ($purchase->briefing) checked @endif">
                            </td>
                        @endif

                        @if (count(
                                $tender->siteVisits()->orderBy('id', 'asc')->get()) > 0)
                            @foreach ($tender->siteVisits()->get() as $visit)
                                <td><input type="checkbox" class="visit-{{ $visit->id }}"
                                        name="visits[{{ $visit->id }}][]" value="{{ $purchase->vendor_id }}"
                                        @if (App\TenderVisitor::hasVisit($visit->id, $purchase->vendor_id)) checked @endif"></td>
                            @endforeach
                        @endif

                        @if ($tender->canShowPrices())
                            <td><input type="text" name="label[{{ $purchase->id }}]" value="{{ $purchase->label }}"
                                    class="form-control"></td>
                            <td><input type="text" name="price[{{ $purchase->id }}]" value="{{ $purchase->price }}"
                                    class="form-control"></td>
                            <td align="center">
                                <input type="radio" name="winner" value="{{ $purchase->id }}"
                                    @if ($purchase->winner) checked @endif><br><br>
                                <input type="text" name="project_timeline"
                                    value="{{ $purchase->winner ? $purchase->project_timeline : '' }}"
                                    @if (!$purchase->winner) disabled="disabled" @endif placeholder="Tempoh Siap"
                                    class="form-control">
                            </td>
                        @endif
                        {{-- Check if there are winner for this tender yet. If yes, this column will appear --}}
                        @if ($count_winner > 0)
                            <td align="center">
                                {{-- Check if the Petender Performance that has been created match with the vendor listed here. If yes, the button will appear. --}}
                                @if ($purchase->winner == 1)
                                    <a href="{{ route('index.TenderVendor', $tender) }}" class="btn btn-success">
                                        Papar
                                    </a>
                                @endif
                            </td>
                        @endif
                        <td>
                            @if ($purchase->participate == 0)
                                <input type="checkbox" class="delete" name="delete[]" value="{{ $purchase->id }}">
                            @else
                                <span class="glyphicon glyphicon-ban-circle"></span>
                            @endif
                        </td>
                    </tr>
                    <?php $count++; ?>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">Tiada Syarikat sertai.</div>
    @endif
    <div class="row">
        <div class="col-lg-6">
            <input type="text" id="vendor_ids" name="vendor_ids" placeholder="Tambah syarikat">
            <small>Cari nama syarikat yang ingin ditambah dan tekan "Simpan Maklumat Syarikat"</small><br><br>
        </div>
        <div class="col-lg-6">
            <input type="submit" class="btn btn-primary pull-right confirm" value="Simpan Maklumat Syarikat">
        </div>
    </div>
    {!! Former::close() !!}

    <h4>Muat Naik Maklumat Syarikat</h4>
    {!! Former::open_for_files(url('tenders/' . $tender->id . '/vendors/bulkUpdate'))->class('form-inline') !!}
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <input type="file" name="file" class="form-control">
            <input type="submit" class="btn btn-warning pull-right confirm" value="Muat Naik"><br><br>
            <small>{{ link_to_route('tenders.template', 'Templat Dokumen (CSV)', $tender->id, ['class' => 'btn btn-xs btn-success']) }}</small>
        </div>
    </div>
    <br>
    {!! Former::close() !!}

    @if (Auth::user()->can('Tender:exception'))
        {!! Former::open(url('tenders/' . $tender->id . '/exception'))->class('form-inline') !!}
        <h4>Kebenaran Khas</h4>
        <div class="row">
            <div class="col-lg-6 col-xs-12">
                <input type="text" id="exception_id" name="exception_id" placeholder="Tambah Kebenaran Khas">
                <input type="submit" class="btn btn-warning pull-right confirm" value="Simpan">
                <small>Cari nama syarikat yang ingin diberikan Kebenaran Khas dan tekan "Simpan"</small>
            </div>
        </div>
        {!! Former::close() !!}
    @endif

@endsection

@section('scripts')
    <script src="{{ asset('js/tender-vue.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=winner]").change(function() {
                if ($(this).is(':checked')) {
                    $('input[name=project_timeline]').each(function(elem) {
                        $(elem).attr('disabled', 'disabled');
                    });
                    $(this).parents('td').find('input[name=project_timeline]').attr('disabled', false);
                }
            });
            $("input.checker").change(function() {
                target = $(this).data('target');
                var checked = this.checked;
                $('input.' + target).each(function() {
                    $(this).prop('checked', checked);
                });
            });
            $('input.checker').each(function() {
                target = $(this).data('target');
                countInput = $('input.' + target).length;
                countChecked = $('input.' + target + ':checked').length;
                if (countInput != 0 && countInput == countChecked) $(this).prop('checked', true);
            });
            $("#vendor_ids").selectize({
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
                            moment(item.expiry_date, 'YYYY-MM-DD').format('DD/MM/YY') +
                            '</strong></small>' +
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
            $("#exception_id").selectize({
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                maxItems: 1,
                create: false,
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                            '<strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
                            '<br><small>Alamat Emel: <strong>' + escape(item.email) +
                            '</strong> &bullet; Tarikh Tamat Langganan: <strong>' +
                            moment(item.expiry_date, 'YYYY-MM-DD').format('DD/MM/YY') +
                            '</strong></small>' +
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
        });
    </script>
@endsection
