<?php

namespace App\Traits;

use App\Models\Event;
use App\Models\Reservation;

/**
 * 
 */
trait ReservationsTrait{
    public static function capacityEvent($idEvent){
        try {
            $capacity = Reservation::join('tickets', 'tickets.ticket_id' ,  '=', 'reservations.ticket_id')
                ->join('events', 'tickets.event_id', '=', 'events.event_id')
                ->where('events.event_id', $idEvent)
                ->count();
            
            return $capacity;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}


