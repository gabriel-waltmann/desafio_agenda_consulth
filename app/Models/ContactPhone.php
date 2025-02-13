<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPhone extends Model {
    use HasFactory;

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