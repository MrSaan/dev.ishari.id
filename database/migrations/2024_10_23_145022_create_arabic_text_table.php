<?php

use App\Models\Muhud;
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
        Schema::create('arabic_text', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Muhud::class);
            $table->integer('position');
            $table->string('text');
            $table->string('translate_id')->nullable();
            $table->string('transliteration')->nullable();
            $table->enum('is_diwan', ['Y', 'N'])->default('N');
            $table->enum('is_diba', ['Y', 'N'])->default('N');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arabic_text');
    }
};
