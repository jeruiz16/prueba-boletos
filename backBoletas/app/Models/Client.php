<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['firstname', 'lastname', 'type_document', 'document', 'celphone'];
    protected $primaryKey = 'client_id';

    public function reservations(){
        return $this->hasMany('App\Models\Reservation', 'client_id');
    }
}
