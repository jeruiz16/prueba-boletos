<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_id', 'client_id'];
    protected $primaryKey = 'reservation_id';

    public function tikect(){
        return $this->belongsTo('App\Models\Ticket', 'ticket_id');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
}
