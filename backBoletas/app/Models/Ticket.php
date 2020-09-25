<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['event_id'];
    protected $primaryKey = 'ticket_id';

    public function event(){
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function reservations(){
        return $this->hasMany('App\Models\Reservation', 'ticket_id');
    }
}
