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
        Schema::create('muhud', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->unique(); // Column 'position' type integer
            $table->string('name'); // Column 'name' type varchar (string)
            $table->string('name_arabic')->nullable(); // Column 'name_arabic' type varchar (string)
            $table->string('translate_id')->nullable(); // Column 'translate_id' type varchar (string)
            $table->string('transliteration')->nullable(); // Column 'transliteration' type varchar (string)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muhud');
    }
};
