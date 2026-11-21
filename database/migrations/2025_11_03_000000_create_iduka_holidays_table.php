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
        Schema::create('iduka_holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iduka_id')->index();
            $table->date('date');
            $table->string('name')->nullable();
            $table->boolean('recurring')->default(false)->comment('If true the holiday recurs annually by month-day');
            $table->timestamps();

            // foreign key if idukas table exists
            if (Schema::hasTable('idukas')) {
                $table->foreign('iduka_id')->references('id')->on('idukas')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iduka_holidays');
    }
};
