<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\PetenderPerformance;

class PetenderPerformanceObserver
{
    /**
     * Handle the PetenderPerformance "creating" event
     */
    public function creating(PetenderPerformance $petenderPerformance)
    {
        $petenderPerformance -> uuid = Str::uuid();
    }

    /**
     * Handle the PetenderPerformance "created" event.
     *
     * @param  \App\Models\PetenderPerformance  $petenderPerformance
     * @return void
     */
    public function created(PetenderPerformance $petenderPerformance)
    {
        //
    }

    /**
     * Handle the PetenderPerformance "updated" event.
     *
     * @param  \App\Models\PetenderPerformance  $petenderPerformance
     * @return void
     */
    public function updated(PetenderPerformance $petenderPerformance)
    {
        //
    }

    /**
     * Handle the PetenderPerformance "deleted" event.
     *
     * @param  \App\Models\PetenderPerformance  $petenderPerformance
     * @return void
     */
    public function deleted(PetenderPerformance $petenderPerformance)
    {
        //
    }

    /**
     * Handle the PetenderPerformance "restored" event.
     *
     * @param  \App\Models\PetenderPerformance  $petenderPerformance
     * @return void
     */
    public function restored(PetenderPerformance $petenderPerformance)
    {
        //
    }

    /**
     * Handle the PetenderPerformance "force deleted" event.
     *
     * @param  \App\Models\PetenderPerformance  $petenderPerformance
     * @return void
     */
    public function forceDeleted(PetenderPerformance $petenderPerformance)
    {
        //
    }
}
