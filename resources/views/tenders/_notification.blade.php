<div class="hidden-print">
	@if(Auth::check())
		@if(Auth::user()->hasRole('Vendor') && $tender->hasParticipate(Auth::user()->vendor_id))
			<div class="alert alert-info">{{ App\Tender::$types[$tender->type] }} telah dibeli.</div>
		@endif

		@if($tender->organization_unit_id != Config::get('app.global_cart_ou') && $tender->tenderer->activeGateway()->count() > 0)
			@if(Auth::user()->hasRole('Vendor') && $tender->hasParticipate(Auth::user()->vendor_id))
			@else
				<div class="alert alert-danger">{{ App\Tender::$types[$tender->type] }} ini tidak boleh dibeli bersama-sama dokumen dari agensi lain.</div>
			@endif
		@endif

		@if($tender->nearSubmission())
			<div class="alert alert-warning">{{ App\Tender::$types[$tender->type] }} ini akan ditutup dalam masa kurang 24 jam.</div>
		@endif

		@if($tender->nearDocumentStop())
			<div class="alert alert-warning">
			  	{{ App\Tender::$types[$tender->type] }} ini hanya boleh dibeli dalam masa kurang 24 jam lagi.<br>
			  	Pihak agensi tidak akan bertanggungjawab di atas kelewatan penghantaran dokumen dan sebarang permohonan pembayaran balik tidak akan dilayan.
			</div>
		@endif

		@if(!$tender->validDocumentDate())
			<div class="alert alert-danger">{{ App\Tender::$types[$tender->type] }} ini tidak boleh dibeli lagi.</div>

		@elseif(Auth::user()->hasRole('Vendor') && !Auth::user()->vendor->valid())
			<div class="alert alert-danger">Anda tidak mempunyai langganan sah.</div>

		@elseif(Auth::user()->hasRole('Vendor') && !$tender->canParticipate(Auth::user()->vendor_id))
		
			@if(Auth::user()->vendor->district_id == null && Auth::user()->vendor->state_id == null )
				<div class="alert alert-danger">Sila kemaskini alamat syarikat anda terlebih dahulu.</div>
			@endif

		  	@if($tender->isBlacklisted(Auth::user()->vendor_id))
		  		<div class="alert alert-danger">Syarikat Anda telah disenarai hitam.</div>
		  	@endif


			@if($tender->briefing_required && !$tender->attendBriefing(Auth::user()->vendor_id))
				<div class="alert alert-danger">Anda perlu menghadiri taklimat sebelum dibenarkan membeli dokumen tender / sebut harga ini.</div>
			@endif
		
			@if(!$tender->attendVisits(Auth::user()->vendor_id))
				<div class="alert alert-danger">Anda perlu menghadiri lawatan tapak sebelum dibenarkan membeli dokumen tender / sebut harga ini.</div>
			@endif
		
			@if(count($tender->mof_codes) > 0)
				@if(!Auth::user()->vendor->mofValid())
					<div class="alert alert-danger">Sijil MOF tamat tempoh.</div>
				@endif
			
				@if(!$tender->matchCodes(Auth::user()->vendor_id, 'mof'))
					<div class="alert alert-danger">Kod Bidang MOF tidak layak.</div>
				@endif
			@endif
		
			@if(count($tender->cidb_codes) > 0)
				@if(!Auth::user()->vendor->cidbValid())
					<div class="alert alert-danger">Sijil CIDB tamat tempoh.</div>
				@endif
			
				@if(!$tender->matchCidbGrade(Auth::user()->vendor_id))
					<div class="alert alert-danger">Gred CIDB tidak layak.</div>
				@endif
			
				@if(!$tender->matchCidbCodesInverse(Auth::user()->vendor_id))
					<div class="alert alert-danger">Bidang Pengkhususan CIDB tidak layak.</div>
				@endif
			@endif
		
		
			@if($tender->only_bumiputera)
				@if(count($tender->cidb_grades) > 0 && !Auth::user()->vendor->cidb_bumi)
					<div class="alert alert-danger">Hanya dibuka untuk syarikat bumiputera sahaja (CIDB).</div>
				@endif
			
				@if(count($tender->mof_codes) > 0 && !Auth::user()->vendor->mof_bumi)
					<div class="alert alert-danger">Hanya dibuka untuk syarikat bumiputera sahaja (MOF).</div>
				@endif
			@endif
		
			@if( ($tender->only_selangor == 1) && empty(Auth::user()->vendor->district_id))
				<div class="alert alert-danger">Hanya dibuka untuk syarikat dari Selangor sahaja.</div>
			@endif
		
			@if($tender->district_id != null && $tender->district_id > 0 && $tender->district_id != Auth::user()->vendor->district_id)
				<div class="alert alert-danger">Hanya dibuka untuk syarikat dari daerah {{ App\Vendor::$districts[$tender->district_id]}} sahaja.</div>
			@endif

			@php
				// $district_list_rule = json_decode($tender->district_list_rule) ?? false; //commented by zayid 9/6/2023

				//change by zayid 9/6/2023
				$district_list_rule = json_decode($tender->district_list_rule);

				if ($district_list_rule === []) {
    				$district_list_rule = false;
				}
				
				if ($district_list_rule == null) {
					$district_list_rule = false;
				}
				//ended here - zayid

				$tender_open_for_state_id 		= [];
				$tender_open_for_state_desc		= [];
				$tender_open_for_district_id 	= [];
				$tender_open_for_district_desc 	= [];

				if( $district_list_rule !== false)
				{
					$current_vendor_state_id 	= Auth::user()->vendor->state_id ?? "0";
					$current_vendor_district_id = Auth::user()->vendor->district_id ?? "0";

					foreach($district_list_rule as $row_rules)
					{
						if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
						{
							$tender_open_for_state_id[]		= $row_rules->state_id;
							$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
						}
						elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
						{
							$tender_open_for_district_id[]		= $row_rules->district_id;
							$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
						}
					}
				}

				// dd( $tender->getNegeriListExist() );
	
			@endphp

			@if ($district_list_rule !== false)
				@if(!in_array($current_vendor_state_id, $tender_open_for_state_id) && $current_vendor_district_id == 0 && $tender->getNegeriListExist())
					<div class="alert alert-danger">Hanya dibuka untuk syarikat dari negeri {{ strtolower( $tender->getNegeriList() ) }} sahaja.</div>
				@endif

				@if(!in_array($current_vendor_district_id, $tender_open_for_district_id) && $current_vendor_state_id == 0 && $tender->getDaerahListExist() )
					<div class="alert alert-danger">Hanya dibuka untuk syarikat dari daerah {{ strtolower( $tender->getDaerahList() ) }} sahaja.</div>
				@endif
			@endif
		

			@if($tender->only_advertise)
				<div class="alert alert-danger">{{ App\Tender::$types[$tender->type] }} ini hanya boleh dibeli secara manual. Sila rujuk Syarat Tender untuk maklumat lanjut.</div>
			@endif
		@endif
	
		@if(in_array($tender->id, session('cart_items') ?: []))
			<div class="alert alert-danger">{{ App\Tender::$types[$tender->type] }} ini sudah berada dalam senarai tempahan.</div>
		@endif
	
	@else
	
		@if(!$tender->publish_prices)
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					Sila daftar masuk untuk menyertai tender ini
			</div>
		@endif
	
	@endif
</div>