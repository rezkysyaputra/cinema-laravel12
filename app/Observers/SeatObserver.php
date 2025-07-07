<?php

namespace App\Observers;

use App\Models\Seat;

class SeatObserver
{
    public function created(Seat $seat)
    {
        $seat->studio->update(['capacity' => $seat->studio->seats()->count()]);
    }

    public function deleted(Seat $seat)
    {
        $seat->studio->update(['capacity' => $seat->studio->seats()->count()]);
    }
}
