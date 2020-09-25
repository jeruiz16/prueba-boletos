<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'date', 'capacity'];
    protected $primaryKey = 'event_id';

    public function tickets(){
        return $this->hasMany('App\Models\Ticket', 'event_id');
    }
}
