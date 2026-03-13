<div class="d-print-none tender-alerts">
    @if (Auth::check())
        @if (Auth::user()->hasRole('Vendor') && $tender->hasParticipate(Auth::user()->vendor_id))
            <div class="alert alert-info"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                    height="18" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                        <path d="M11 12h1v4h1" />
                    </g>
                </svg>
                {{ App\Tender::$types[$tender->type] }} telah
                dibeli.</div>
        @endif

        @if (
            $tender->organization_unit_id != Config::get('app.global_cart_ou') &&
                $tender->tenderer->activeGateway()->count() > 0)
            @if (Auth::user()->hasRole('Vendor') && $tender->hasParticipate(Auth::user()->vendor_id))
            @else
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                        height="18" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                            <path d="M11 12h1v4h1" />
                        </g>
                    </svg>
                    {{ App\Tender::$types[$tender->type] }}
                    ini tidak boleh dibeli bersama-sama dokumen dari agensi lain.</div>
            @endif
        @endif

        @if ($tender->nearSubmission())
            <div class="alert alert-warning"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                    height="18" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0M12 7v5l3 3" />
                </svg>
                {{ App\Tender::$types[$tender->type] }} ini akan
                ditutup dalam masa kurang 24 jam.</div>
        @endif

        @if ($tender->nearDocumentStop())
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18"
                    viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0M12 7v5l3 3" />
                </svg>

                {{ App\Tender::$types[$tender->type] }} ini hanya boleh dibeli dalam masa kurang 24 jam lagi.<br>
                Pihak agensi tidak akan bertanggungjawab di atas kelewatan penghantaran dokumen dan sebarang permohonan
                pembayaran balik tidak akan dilayan.
            </div>
        @endif

        @if (!$tender->validDocumentDate())
            <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                    height="18" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
                </svg>
                {{ App\Tender::$types[$tender->type] }} ini tidak
                boleh dibeli lagi.</div>
        @elseif(Auth::user()->hasRole('Vendor') && !Auth::user()->vendor->valid())
            <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                    height="18" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
                </svg>
                Anda tidak mempunyai langganan sah.</div>
        @elseif(Auth::user()->hasRole('Vendor') && !$tender->canParticipate(Auth::user()->vendor_id))
            @if (Auth::user()->vendor->district_id == null && Auth::user()->vendor->state_id == null)
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                        height="18" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.442 9.432a3 3 0 0 0 4.113 4.134m1.445 -2.566a3 3 0 0 0 -3 -3M17.152 17.162l-3.738 3.738a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.476 -10.794m2.18 -1.82a8.003 8.003 0 0 1 10.91 10.912M3 3l18 18" />
                    </svg>
                    Sila kemaskini alamat syarikat anda
                    terlebih dahulu.</div>
            @endif

            @if ($tender->isBlacklisted(Auth::user()->vendor_id))
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                        height="18" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
                    </svg>
                    Syarikat Anda telah disenarai hitam.</div>
            @endif


            @if ($tender->briefing_required && !$tender->attendBriefing(Auth::user()->vendor_id))
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                        height="18" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                            <path d="M11 12h1v4h1" />
                        </g>
                    </svg>
                    Anda perlu menghadiri taklimat sebelum
                    dibenarkan membeli dokumen tender / sebut harga ini.</div>
            @endif

            @if (!$tender->attendVisits(Auth::user()->vendor_id))
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                        width="18" height="18" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                            <path d="M11 12h1v4h1" />
                        </g>
                    </svg>
                    Anda perlu menghadiri lawatan tapak
                    sebelum dibenarkan membeli dokumen tender / sebut harga ini.</div>
            @endif

            @if (count($tender->mof_codes) > 0)
                @if (!Auth::user()->vendor->mofValid())
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M12.876 12.881a3 3 0 0 0 4.243 4.243m.588 -3.42a3.012 3.012 0 0 0 -1.437 -1.423M13 17.5v4.5l2 -1.5l2 1.5v-4.5M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2m4 0h10a2 2 0 0 1 2 2v10M6 9h3m4 0h5M6 12h3M6 15h2M3 3l18 18" />
                        </svg>
                        Sijil MOF tamat tempoh.</div>
                @endif

                @if (!$tender->matchCodes(Auth::user()->vendor_id, 'mof'))
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M7 8l-4 4l4 4M17 8l4 4l-2.5 2.5M14 4l-1.201 4.805m-.802 3.207l-2 7.988M3 3l18 18" />
                        </svg>
                        Kod Bidang MOF tidak layak.</div>
                @endif
            @endif

            @if (count($tender->cidb_codes) > 0)
                @if (!Auth::user()->vendor->cidbValid())
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M12.876 12.881a3 3 0 0 0 4.243 4.243m.588 -3.42a3.012 3.012 0 0 0 -1.437 -1.423M13 17.5v4.5l2 -1.5l2 1.5v-4.5M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2m4 0h10a2 2 0 0 1 2 2v10M6 9h3m4 0h5M6 12h3M6 15h2M3 3l18 18" />
                        </svg>
                        Sijil CIDB tamat tempoh.</div>
                @endif

                @if (!$tender->matchCidbGrade(Auth::user()->vendor_id))
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M7 8l-4 4l4 4M17 8l4 4l-2.5 2.5M14 4l-1.201 4.805m-.802 3.207l-2 7.988M3 3l18 18" />
                        </svg>
                        Gred CIDB tidak layak.</div>
                @endif

                @if (!$tender->matchCidbCodesInverse(Auth::user()->vendor_id))
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M7 8l-4 4l4 4M17 8l4 4l-2.5 2.5M14 4l-1.201 4.805m-.802 3.207l-2 7.988M3 3l18 18" />
                        </svg>
                        Bidang Pengkhususan CIDB tidak layak.
                    </div>
                @endif
            @endif


            @if ($tender->only_bumiputera)
                @if (count($tender->cidb_grades) > 0 && !Auth::user()->vendor->cidb_bumi)
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
                        </svg>
                        Hanya dibuka untuk syarikat bumiputera
                        sahaja (CIDB).</div>
                @endif

                @if (count($tender->mof_codes) > 0 && !Auth::user()->vendor->mof_bumi)
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
                        </svg>
                        Hanya dibuka untuk syarikat bumiputera
                        sahaja (MOF).</div>
                @endif
            @endif

            @if ($tender->only_selangor == 1 && empty(Auth::user()->vendor->district_id))
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                        width="18" height="18" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.442 9.432a3 3 0 0 0 4.113 4.134m1.445 -2.566a3 3 0 0 0 -3 -3M17.152 17.162l-3.738 3.738a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.476 -10.794m2.18 -1.82a8.003 8.003 0 0 1 10.91 10.912M3 3l18 18" />
                    </svg>
                    Hanya dibuka untuk syarikat dari
                    Selangor sahaja.</div>
            @endif

            @if (
                $tender->district_id != null &&
                    $tender->district_id > 0 &&
                    $tender->district_id != Auth::user()->vendor->district_id)
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                        width="18" height="18" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.442 9.432a3 3 0 0 0 4.113 4.134m1.445 -2.566a3 3 0 0 0 -3 -3M17.152 17.162l-3.738 3.738a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.476 -10.794m2.18 -1.82a8.003 8.003 0 0 1 10.91 10.912M3 3l18 18" />
                    </svg>
                    Hanya dibuka untuk syarikat dari daerah
                    {{ App\Vendor::$districts[$tender->district_id] }} sahaja.</div>
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

                $tender_open_for_state_id = [];
                $tender_open_for_state_desc = [];
                $tender_open_for_district_id = [];
                $tender_open_for_district_desc = [];

                if ($district_list_rule !== false) {
                    $current_vendor_state_id = Auth::user()->vendor->state_id ?? '0';
                    $current_vendor_district_id = Auth::user()->vendor->district_id ?? '0';

                    foreach ($district_list_rule as $row_rules) {
                        if ($row_rules->district_id == 0 && $row_rules->state_id != 0) {
                            $tender_open_for_state_id[] = $row_rules->state_id;
                            $tender_open_for_state_desc[] =
                                \App\Models\RefState::find($row_rules->state_id)->description ?? '';
                        } elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0) {
                            $tender_open_for_district_id[] = $row_rules->district_id;
                            $tender_open_for_district_desc[] =
                                \App\Models\Vendor::$districts[$row_rules->district_id] ?? '';
                        }
                    }
                }

                // dd( $tender->getNegeriListExist() );

            @endphp

            @if ($district_list_rule !== false)
                @if (
                    !in_array($current_vendor_state_id, $tender_open_for_state_id) &&
                        $current_vendor_district_id == 0 &&
                        $tender->getNegeriListExist())
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M9.442 9.432a3 3 0 0 0 4.113 4.134m1.445 -2.566a3 3 0 0 0 -3 -3M17.152 17.162l-3.738 3.738a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.476 -10.794m2.18 -1.82a8.003 8.003 0 0 1 10.91 10.912M3 3l18 18" />
                        </svg>
                        Hanya dibuka untuk syarikat dari
                        negeri {{ strtolower($tender->getNegeriList()) }} sahaja.</div>
                @endif

                @if (
                    !in_array($current_vendor_district_id, $tender_open_for_district_id) &&
                        $current_vendor_state_id == 0 &&
                        $tender->getDaerahListExist())
                    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                            width="18" height="18" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M9.442 9.432a3 3 0 0 0 4.113 4.134m1.445 -2.566a3 3 0 0 0 -3 -3M17.152 17.162l-3.738 3.738a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 0 1 -.476 -10.794m2.18 -1.82a8.003 8.003 0 0 1 10.91 10.912M3 3l18 18" />
                        </svg>
                        Hanya dibuka untuk syarikat dari
                        daerah {{ strtolower($tender->getDaerahList()) }} sahaja.</div>
                @endif
            @endif


            @if ($tender->only_advertise)
                <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="me-1"
                        width="18" height="18" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                            <path d="M11 12h1v4h1" />
                        </g>
                    </svg>
                    {{ App\Tender::$types[$tender->type] }}
                    ini hanya boleh dibeli secara manual. Sila rujuk Syarat Tender untuk maklumat lanjut.</div>
            @endif
        @endif

        @if (in_array($tender->id, session('cart_items') ?: []))
            <div class="alert alert-warning"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18"
                    height="18" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M15 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M17 17h-11v-14h-2M6 5l14 1l-1 7h-13" />
                </svg>
                {{ App\Tender::$types[$tender->type] }}
                ini sudah berada dalam senarai tempahan.</div>
        @endif
    @else
        @if (!$tender->publish_prices)
            <div class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18"
                    viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2M21 12h-13l3 -3M11 15l-3 -3" />
                </svg>
                Sila daftar masuk untuk menyertai tender ini
            </div>
        @endif

    @endif
</div>
