<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {
    protected $table = 'contact';

    protected $fillable = [
        'name',
        'email',
    ];

    public function phones() {
        return $this->HasMany(ContactPhone::class);
    }

    public function address() {
        return $this->hasOne(ContactAddress::class);
    }
}
