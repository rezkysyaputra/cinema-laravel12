<?php

namespace App\Observers;

use App\Models\Studio;
use App\Models\Seat;

class StudioObserver
{
    /**
     * Handle the Studio "created" event.
     */
    public function created(Studio $studio): void
    {
        // Create seats based on row and column configuration
        for ($row = 1; $row <= $studio->row; $row++) {
            for ($col = 1; $col <= $studio->column; $col++) {
                $seatCode = chr(64 + $row) . $col; // A1, A2, B1, B2, etc.
                Seat::create([
                    'studio_id' => $studio->id,
                    'code' => $seatCode,
                    'row' => $row,
                    'number' => $col
                ]);
            }
        }

        // Update studio capacity
        $studio->update([
            'capacity' => $studio->row * $studio->column
        ]);
    }

    /**
     * Handle the Studio "updated" event.
     */
    public function updated(Studio $studio): void
    {
        //
    }

    /**
     * Handle the Studio "deleted" event.
     */
    public function deleted(Studio $studio): void
    {
        //
    }

    /**
     * Handle the Studio "restored" event.
     */
    public function restored(Studio $studio): void
    {
        //
    }

    /**
     * Handle the Studio "force deleted" event.
     */
    public function forceDeleted(Studio $studio): void
    {
        //
    }
}
