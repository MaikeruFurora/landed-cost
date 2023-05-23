<?php

namespace App\Observers;

use App\Models\Detail;

class DetailObserver
{
    /**
     * Handle the Detail "created" event.
     *
     * @param  \App\Models\Detail  $detail
     * @return void
     */
    public function created(Detail $detail)
    {
        //
    }

    /**
     * Handle the Detail "updated" event.
     *
     * @param  \App\Models\Detail  $detail
     * @return void
     */
    public function updated(Detail $detail)
    {
        //
    }

    /**
     * Handle the Detail "deleted" event.
     *
     * @param  \App\Models\Detail  $detail
     * @return void
     */
    public function deleted(Detail $detail)
    {
        //
    }

    /**
     * Handle the Detail "restored" event.
     *
     * @param  \App\Models\Detail  $detail
     * @return void
     */
    public function restored(Detail $detail)
    {
        //
    }

    /**
     * Handle the Detail "force deleted" event.
     *
     * @param  \App\Models\Detail  $detail
     * @return void
     */
    public function forceDeleted(Detail $detail)
    {
        //
    }
}
