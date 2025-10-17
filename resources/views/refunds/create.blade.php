@extends('layouts.default')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-search input {
            display: none;
        }

        .loader2 {
            font-size: 10px;
            margin: 5em auto;
            width: 1em;
            height: 1em;
            border-radius: 50%;
            position: relative;
            text-indent: -9999em;
            -webkit-animation: load2 1.3s infinite linear;
            animation: load2 1.3s infinite linear;
        }

        @-webkit-keyframes load2 {

            0%,
            100% {
                box-shadow: 0em -3em 0em 0.2em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 0em #c32508;
            }

            12.5% {
                box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 0.2em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            25% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 0.2em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            37.5% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 0.2em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            50% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 0.2em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            62.5% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 0.2em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            75% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0.2em #c32508, -2em -2em 0 0em #c32508;
            }

            87.5% {
                box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 0.2em #c32508;
            }
        }

        @keyframes load2 {

            0%,
            100% {
                box-shadow: 0em -3em 0em 0.2em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 0em #c32508;
            }

            12.5% {
                box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 0.2em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            25% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 0.2em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            37.5% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 0.2em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            50% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 0.2em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            62.5% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 0.2em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 -0.5em #c32508;
            }

            75% {
                box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0.2em #c32508, -2em -2em 0 0em #c32508;
            }

            87.5% {
                box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 0.2em #c32508;
            }
        }
    </style>
@endsection
@section('content')
    <h2>Permohonan Pemulangan Semula</h2>
    <hr>
    <form action="{{ route('refunds.store') }}" enctype="multipart/form-data" class="form-horizontal" method="POST">
        @csrf
        <div class="form-group required">
            <label for="title" class="control-label col-lg-3 col-sm-3">Senarai Transaksi<sup>*</sup>
            </label>
            <div class="col-lg-9 col-sm-9">
                <select class="form-control" id="transaction_id" name="transaction_id"
                    onchange="transactionDetails(this)" required>
                </select>
            </div>
        </div>
        <hr>
        <div id="loader"></div>
        <table class="table table-bordered table-condensed" id="table-transaction" style="display: none">
            <tbody>
                <tr>
                    <td class="text-right" style="width: 20%">
                        <label for="company_name" class="control-label">
                            Nama Perniagaan / Syarikat
                        </label>
                    </td>
                    <td style="width: 80%">
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="company_name" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="payment_type" class="control-label">
                            Pembayaran Melalui
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="payment_type" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="transaction_date" class="control-label">
                            Tarikh Transaksi
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="transaction_date" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="amount" class="control-label">
                            Jumlah Bayaran (RM)
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="amount" name="amount" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="title" class="control-label">
                            Langganan/ Tajuk Sebut Harga/ Tender yang dibeli
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <textarea class="form-control" type="text" id="title" style="resize:vertical" readonly></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="agency" class="control-label">
                            Agensi
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="agency" value="" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="name" class="control-label">
                            Nama Pemohon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="name" name="name" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="ic" class="control-label">
                            No. Kad Pengenalan Pemohon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="ic" name="ic" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="no_tel" class="control-label">
                            No. Telefon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="tel" id="tel" name="tel" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="address" class="control-label">
                            Alamat Pemohon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <textarea class="form-control" type="text" id="address" name="address" style="resize:vertical" rows="3"
                                required></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="bank_acc" class="control-label">
                            No. Akaun Bank Pemohon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="bank_acc" name="bank_acc" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="bank_type" class="control-label">
                            Jenis Bank<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <select class="form-control" id="bank_id" name="bank_id" required>
                                <option value="">-- Sila Pilih Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="bank_address" class="control-label">
                            Alamat Bank Pemohon<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <textarea class="form-control" type="text" id="bank_address" name="bank_address" style="resize:vertical"
                                rows="3" required></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="2">
                        <label class="control-label">
                            Surat Permohonan<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                1) Sila muat naik surat permohonan yang telah <b>lengkap diisi</b>.
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="file" accept="application/pdf" name="application_letter" id="application_letter"
                                required>
                                <span class="help-block">
                                    Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                                </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="4">
                        <label class="control-label">
                            Salinan Penyata Bank<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                1) Resit bank yang mengandungi bukti penolakan bayaran langganan/pembelian <b>disahkan oleh pegawai bank</b>.
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="file" accept="application/pdf" name="bank_statement1" id="bank_statement1"
                                required>
                                <span class="help-block">
                                    Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                                </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                2) Penyata akaun bank pemohon (untuk dikreditkan pembayaran semula) yang <b>disahkan oleh
                                    pegawai bank</b>.
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="file" accept="application/pdf" name="bank_statement2" id="bank_statement2"
                                required>
                                <span class="help-block">
                                    Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                                </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="4">
                        <label class="control-label">
                            Lampiran Tambahan<sup>*</sup>
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                1) Nyatakan sebab-sebab pemulangan semula.
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <textarea class="form-control" type="text" id="remark" name="remark" style="resize:vertical" rows="3"
                                required></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                2) Sila lampirkan tangkapan skrin masalah yang dihadapi. (Tidak Wajib)
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="file" accept="application/pdf" name="screenshot_problem" id="screenshot_problem">
                            <span class="help-block">
                                Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="well">
            <a class="btn btn-default" href="{{ route('dashboard') }}">Kembali</a>
            <button type="submit" class="btn btn-primary pull-right">Hantar</button>
        </div>
    </form>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#transaction_id").select2({
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ route('get_transaction') }}",
                    type: "post",
                    dataType: 'json',
                    // delay: 1000,
                    data: function(params) {
                        return {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            page: params.page || 1
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response,
                            pagination: {
                                more: response.length >= 10
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, status, error) {
                        console.log(error + ": " + jqXHR.responseText);
                        return {
                            results: []
                        }; // Return dataset to load after error
                    }
                }

            });
        });

        function transactionDetails(data) {
            $.ajax({
                url: "{{ route('get_refund_details') }}",
                type: 'POST',
                data: {
                    id: data.value
                },
                beforeSend: function() {
                    $('#loader').addClass('loader2');
                    $('#table-transaction').hide();
                },
                success: function(response) {
                    $('#company_name').val(response.vendor);
                    $('#payment_type').val(response.method);
                    $('#transaction_date').val(response.transaction_date);
                    $('#amount').val(response.amount);
                    $('#title').val(response.title);
                    $('#agency').val(response.agency);
                },
                complete: function() {
                    $('#loader').removeClass('loader2');
                    $('#table-transaction').show();
                }
            });

        }
    </script>
@endsection
