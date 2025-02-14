<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model{
    protected $table = 'address';

    protected $fillable = [
        'country',
        'state',
        'city',
        'neighborhood',
        'address',
        'zipCode'
    ];

    public function address() {
        return $this->hasMany(ContactAddress::class);
    }
}