<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model {
    protected $table = 'contact_phone';

    protected $fillable = [
        'contact_id',
        'phone_id',
    ];

    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    public function phone() {
        return $this->belongsTo(Phone::class);
    }
}