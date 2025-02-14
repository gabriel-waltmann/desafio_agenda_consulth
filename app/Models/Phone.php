<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model {
    protected $table = 'phone';

    protected $fillable = [
        'number',
        'countryCode',
    ];

    public function phones() {
        return $this->hasMany(ContactPhone::class);
    }
}
