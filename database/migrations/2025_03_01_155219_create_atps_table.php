<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('atps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cp_id')->constrained('cps')->onDelete('cascade');
            $table->text('atp');
            $table->string('kode_atp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atps');
    }
};
