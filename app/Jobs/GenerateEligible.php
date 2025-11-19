<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Tender;
use App\Vendor;
use App\VendorCode;
use App\TenderEligible;

class GenerateEligible implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tender_id, $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tender_id, $email)
    {
        $this->tender_id = $tender_id;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tender = Tender::find($this->tender_id);
        $this->generate($tender, $this->email);
        // $job->delete();
    }

    public function generate($tender, $email) {
        $vendor_ids = [];

        $mof_vendor_ids = [];

        if(count($tender->mof_codes) > 0) {
            $mof_vendor_ids = $tender->getCodes('mof');

            if($tender->only_bumiputera) {
                $mof_vendor_ids = Vendor::whereIn('id', $mof_vendor_ids)->where('mof_bumi', 1)->pluck('id');
            }
        }

        $cidb_vendor_ids = [];
        if(count($tender->cidb_grades) > 0 ) {
            $code_ids = $tender->codes()->where('code_type', 'cidb-g')->pluck('code_id');
            $ids = VendorCode::whereIn('code_id', $code_ids)->groupBy('vendor_id')->pluck('vendor_id');
            if(count($cidb_vendor_ids) == 0)
            {
                $cidb_vendor_ids = $ids;
            }
            else
            {
                if(!is_array($cidb_vendor_ids))
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                if(!is_array($ids))
                    $ids = $ids->toArray();

                $cidb_vendor_ids = array_intersect($cidb_vendor_ids, $ids);
            }

            if($tender->only_bumiputera) {
                $cidb_vendor_ids = Vendor::whereIn('id', $cidb_vendor_ids)->where('cidb_bumi', 1)->pluck('id');
            }
        }

        if(count($tender->cidb_codes) > 0) {
            $ids_cidb = $tender->getCodes('cidb');
            if(count($cidb_vendor_ids) == 0)
            {
                $cidb_vendor_ids = $ids_cidb;
            }
            else
            {
                if(!is_array($cidb_vendor_ids))
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                if(!is_array($ids_cidb))
                    $ids_cidb = $ids_cidb->toArray();

                $cidb_vendor_ids = array_intersect($cidb_vendor_ids, $ids_cidb);
            }
        }

        if($tender->mof_cidb_rule == 'and') {
            if(count($mof_vendor_ids) > 0 && count($cidb_vendor_ids) == 0 ) {
                $vendor_ids = $mof_vendor_ids;
            } else if(count($cidb_vendor_ids) > 0 && count($mof_vendor_ids) == 0 ) {
                $vendor_ids = $cidb_vendor_ids;
            } else {

                if(!is_array($mof_vendor_ids))
                    $mof_vendor_ids = $mof_vendor_ids->toArray();
                if(!is_array($cidb_vendor_ids))
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();

                $vendor_ids = array_intersect($mof_vendor_ids, $cidb_vendor_ids);
            }
        } else {

            if(!is_array($mof_vendor_ids))
                    $mof_vendor_ids = $mof_vendor_ids->toArray();
            if(!is_array($cidb_vendor_ids))
                $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                
            $vendor_ids = array_merge($mof_vendor_ids, $cidb_vendor_ids);
        }

        if($tender->only_selangor) {
            $vendor_ids = Vendor::whereIn('id', $vendor_ids)->whereNotNull('district_id')->pluck('id');
        }

        if(!empty($tender->district_id)) {
            $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where('district_id', $tender->district_id)->pluck('id');
        }

        $district_list_rules = json_decode($tender->district_list_rule ?? "[]");
        
        $vendor_ids_tmp = [];
        if (count($district_list_rules) > 0 )
        {
            $by_district = [];
            $by_state    = [];
            
            foreach ($district_list_rules as $rows) {
                $district_id = $rows->district_id ?? '-999';
                $state_id = $rows->state_id ?? '-999';


                if($district_id > 0 && $district_id != 0)
                {
                    $by_district[] = $district_id;
                }

                if($district_id == 0 && $state_id > 0)
                {
                    $by_state[] = $state_id;
                }

            }

            $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where( function($q) use ($by_state, $by_district){
                $q->whereIn('state_id', $by_state)
                ->orWhereIn('district_id', $by_district);
            })->pluck('id');
        }

        // dd($vendor_ids);

        if(count($vendor_ids) > 0) {
            foreach($vendor_ids as $id) {
                $vendor = Vendor::find($id);
                $tender = $tender;

                if(empty($vendor)) continue;

                $eligible = TenderEligible::where('vendor_id', $id)->where('tender_id', $tender->id)->first();

                // dd($eligible);
                if(!$eligible) {
                    $eligible = new TenderEligible([
                        'tender_id' => $tender->id,
                        'vendor_id' => $id,
                        'email' => $email
                    ]);                    
                    $eligible->save();
                }
            }
        }
    }
}
