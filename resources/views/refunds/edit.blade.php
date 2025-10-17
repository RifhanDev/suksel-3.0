@extends('layouts.default')
@section('styles')
@endsection
@section('content')
    <h2>Kemaskini Pemulangan Semula : {{ $refund->ref_no }}</h2>
    <hr>
    <form action="{{ route('refunds.update', $refund->id) }}" enctype="multipart/form-data" class="form-horizontal"
        method="POST">
        @csrf
        <table class="table table-bordered table-condensed" id="table-transaction">
            <tbody>
                <tr>
                    <td class="text-right" style="width: 20%">
                        <label for="company_name" class="control-label">
                            Nama Perniagaan / Syarikat
                        </label>
                    </td>
                    <td style="width: 80%">
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="company_name"
                                value="{{ $transaction->vendor->name }}" readonly>
                            <input class="form-control" type="hidden" id="transaction_id" name="transaction_id"
                                value="{{ $transaction->id }}" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <label for="payment_type" class="control-label">
                            No. Resit
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="text" id="receipt"
                                value="{{ $transaction->receipt != 'old' ? $transaction->receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference }}"
                                readonly>
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
                            <input class="form-control" type="text" id="payment_type" value="{{ $transaction->method }}"
                                readonly>
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
                            <input class="form-control" type="text" id="transaction_date"
                                value="{{ $transaction->transaction_date }}" readonly>
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
                            <input class="form-control" type="text" id="amount" name="amount"
                                value="{{ $transaction->amount }}" readonly>
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
                            <textarea class="form-control" type="text" id="title" style="resize:vertical" readonly>{{ $transaction->type == 'purchase' ? $transaction->purchases[0]->tender->name : 'Langganan' }}</textarea>
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
                            <input class="form-control" type="text" id="agency"
                                value="{{ $transaction->type == 'purchase' ? $transaction->agency->name : 'SUK Selangor' }}"
                                readonly>
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
                            <input class="form-control" type="text" id="name" name="name"
                                value="{{ $refund->name }}" required>
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
                            <input class="form-control" type="text" id="ic" name="ic"
                                value="{{ $refund->ic }}" required>
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
                            <input class="form-control" type="tel" id="tel" name="tel"
                                value="{{ $refund->tel }}" required>
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
                                required>{!! nl2br($refund->address) !!}</textarea>
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
                            <input class="form-control" type="text" id="bank_acc" name="bank_acc"
                                value="{{ $refund->bank_acc }}" required>
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
                                    <option value="{{ $bank->id }}" {{ $bank->id == $refund->bank_id ? 'selected' : '' }}>
                                        {{ $bank->display_name }}</option>
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
                                rows="3" required>{!! nl2br($refund->bank_address) !!}</textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="2">
                        <label class="control-label">
                            Surat Permohonan
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
                            <input class="form-control" type="file" accept="application/pdf"
                                name="application_letter" id="application_letter">
                            <span class="help-block">
                                Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="4">
                        <label class="control-label">
                            Salinan Penyata Bank
                        </label>
                    </td>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <label class="control-label">
                                1) Resit bank yang mengandungi bukti penolakan bayaran langganan/pembelian <b>disahkan oleh
                                    pegawai bank</b>.
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-lg-12 col-sm-12">
                            <input class="form-control" type="file" accept="application/pdf" name="bank_statement1"
                                id="bank_statement1">
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
                            <input class="form-control" type="file" accept="application/pdf" name="bank_statement2"
                                id="bank_statement2">
                            <span class="help-block">
                                Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" rowspan="4">
                        <label class="control-label">
                            Lampiran Tambahan
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
                                required>{!! nl2br($refund->remark) !!}</textarea>
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
                            <input class="form-control" type="file" accept="application/pdf"
                                name="screenshot_problem" id="screenshot_problem">
                            <span class="help-block">
                                Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="well">
            <a class="btn btn-default" href="{{ url()->previous() }}">Kembali</a>
            @if ($refund->status == 2)
                <button type="submit" class="btn btn-primary pull-right">Kemaskini</button>
            @endif
        </div>
    </form>
@endsection
@section('scripts')
@endsection
