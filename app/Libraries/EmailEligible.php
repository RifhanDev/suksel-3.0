<?php

class EmailEligible
{
    public function fire($job, $data)
    {
    	/*$tender    = Tender::find($data['tender_id']);
        $vendor    = Vendor::find($data['vendor_id']);
        $eligible  = TenderEligible::whereVendorId($data['vendor_id'])->whereTenderId($data['tender_id'])->whereNull('sent_at')->first();

        if($tender &&
            $vendor &&
            $vendor->user &&
            !$vendor->user->isEmailBlacklist() &&
            $eligible &&
            $vendor->canParticipateInTenders())
        {
            Mail::send('tenders.emails.eligible', ['tender_id' => $tender->id, 'vendor_id' => $vendor->id], function($message) use($vendor, $tender) {
                $message->to(trim($vendor->user->email));
                $message->subject('Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name);   
            });

            $eligible->update([
                'sent_at' => \Carbon\Carbon::now()
            ]);
        }*/

        $job->delete();
    }
}
