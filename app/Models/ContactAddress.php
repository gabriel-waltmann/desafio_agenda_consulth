<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactAddress extends Model {
    protected $table = 'contact_address';

    protected $fillable = [
        'contact_id',
        'address_id',
    ];

    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }
}

