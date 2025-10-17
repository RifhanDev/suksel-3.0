@extends('layouts.default')
@section('content')

	<div class="row">
    	<div class="col-md-3">
       	<ul class="list-group hidden-print">
            @if($user)
                	<div id="MainMenu">
                  	<div class="list-group panel">
                  		<!-- VENDOR -->
                    		<a href="#syarikat" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">SYARIKAT<i class="fa fa-caret-down" ></i></a>
                      	<div class="collapse" id="syarikat">
                        	<a href="{{ asset('manuals/pendaftaran') }}" class="list-group-item">Daftar Syarikat</a>
                        	<a href="{{ asset('manuals/pengesahan_syarikat') }}" class="list-group-item">Pengesahan Syarikat</a>
                        	<a href="{{ asset('manuals/renew') }}" class="list-group-item">Perbaharui Langganan</a>
                        	<a href="{{ asset('manuals/menu_utama_syarikat') }}" class="list-group-item">Menu Utama</a>
                        	<a href="{{ asset('manuals/pembelian_tender') }}" class="list-group-item">Pembelian Tender</a>
                        	<a href="{{ asset('manuals/permintaan_kemaskini') }}" class="list-group-item">Permintaan Kemaskini MOF & CIDB</a>
                        <a href="{{ asset('manuals/membuat_pdf') }}" class="list-group-item">Cara membuat fail pdf</a>
                        <a href="{{ asset('manuals/kemaskini_emel') }}" class="list-group-item">Kemaskini Alamat emel atau No pendaftaran syarikat</a>
                        <a href="{{ asset('manuals/kemaskini_syarikat') }}" class="list-group-item">Kemaskini Maklumat Lain</a>
                      </div>
                  	<!-- REGISTRATION ASSESSOR -->
                  	@if($user->ability(['Admin'],['Vendor:approve']))
                    		<a href="#umum" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">PENGESAHAN<i class="fa fa-caret-down" ></i></a>
                   		<div class="collapse" id="umum">
                     		<a href="{{action('ManualsController@show', 'pengesahan_pendaftaran')}}" class="list-group-item">Pegesahan Pendaftaran</a>
                     		<a href="{{action('ManualsController@show', 'pengesahan_kemaskini')}}" class="list-group-item">Pegesahan Pemintaan Kemaskini</a>
                   		</div>
                  	@endif
                  <!-- AGENCY USER -->
                  @if($user->ability(['Admin'],['Tender:create']))
                    <a href="#tender" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">TENDER<i class="fa fa-caret-down" ></i></a>
                      <div class="collapse" id="tender">
                        <a href="{{action('ManualsController@show', 'tambah_tender')}}" class="list-group-item">TAMBAH TENDER</a>
                        <a href="{{action('ManualsController@show', 'siar')}}" class="list-group-item">SIAR/BATAL SIAR TENDER</a>
                        <a href="{{action('ManualsController@show', 'rekod')}}" class="list-group-item">MEREKOD SYARIKAT</a>
                        <a href="{{action('ManualsController@show', 'carta')}}" class="list-group-item">CARTA TENDER</a>
                      </div>
                  @endif
                  <!-- AGENCY ADMIN -->
                  @if($user->ability(['Admin','Agency Admin'],[]))
                    <a href="#aadmin" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">PENGURUSAN TENDER<i class="fa fa-caret-down" ></i></a>
                      <div class="collapse" id="aadmin">
                    @if($user->ability(['Admin'],[]))
                        <a href="{{action('ManualsController@show', 'senarai_hitam_admin')}}" class="list-group-item">TAMBAH SENARAI HITAM</a>
                        <a href="{{action('ManualsController@show', 'senarai_hitam_batal')}}" class="list-group-item">BATAL SENARAI HITAM</a>
                    @endif
                        <a href="{{action('ManualsController@show', 'senarai_hitam')}}" class="list-group-item">PAPAR SENARAI HITAM</a>
                        <a href="{{action('ManualsController@show', 'senarai_berita')}}" class="list-group-item">SENARAI BERITA</a>
                      </div>
                  @endif
                  <!-- AGENCY ADMIN -->
                  @if($user->ability(['Admin','Agency Admin'],[]))
                    <a href="#aadmin-akses" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">PENGURUSAN AKSES PENGGUNA<i class="fa fa-caret-down" ></i></a>
                      <div class="collapse" id="aadmin-akses">
                        <a href="{{action('ManualsController@show', 'mohon_id_agensi')}}" class="list-group-item">PERMOHONAN PENGGUNA AGENSI</a>
                        <a href="{{action('ManualsController@show', 'nilai_id_agensi')}}" class="list-group-item">PENILAIAN PENGGUNA AGENSI</a>
                      </div>
                    <a href="#aadmin-semak" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">SEMAK AKSES PENGGUNA<i class="fa fa-caret-down" ></i></a>
                      <div class="collapse" id="aadmin-semak">
                        <a href="{{action('ManualsController@show', 'semak_akaun')}}" class="list-group-item">SEMAK AKAUN</a>
                        <a href="{{action('ManualsController@show', 'status_semak')}}" class="list-group-item">PAPAR STATUS SEMAK AKAUN</a>
                      </div>
                  @endif
                  </div>
                </div><!-- habis div id="MainMenu" -->
            @else
               <div id="MainMenu">
                  <div class="list-group panel">
                  <a href="#public" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">UMUM<i class="fa fa-caret-down" ></i></a>
                     <div class="collapse" id="public">
                       	<a href="{{action('ManualsController@show', 'pendaftaran')}}" class="list-group-item">DAFTAR SYARIKAT</a>
                        <a href="{{action('ManualsController@show', 'forgot_pass')}}" class="list-group-item">LUPA KATA LALUAN</a>
                     </div>
                  </div>
               </div>
            @endif
        	</ul>
    	</div>
    	<a href="javascript:window.print()" class="btn btn-info btn-lg hidden-print"><span class="glyphicon glyphicon-print"></span> Cetak Manual Ini</a>
    	<br><br>
    	<div id="markdown-content" class="col-md-9 hidden">{{ $content }}</div>
	</div>

@endsection

@section('scripts')

	<script src="{{ asset('js/markdown.js') }}"></script>
 	<script>
     	$('#markdown-content').html(markdown.toHTML($('#markdown-content').html())).removeClass('hidden');
 	</script>
    	
@endsection