<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained(
                table: 'contact', indexName: 'contact_id'
            );        
            $table->foreignId('address_id')->constrained(
                table: 'address', indexName: 'address_id'
            );     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_address');
    }
};
