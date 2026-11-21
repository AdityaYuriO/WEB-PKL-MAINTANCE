<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved_iduka', 'approved_pembimbing', 'rejected'])
                ->default('pending');
            $table->timestamp('approved_iduka_at')->nullable();
            $table->timestamp('approved_pembimbing_at')->nullable();
            $table->text('rejected_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnals', function (Blueprint $table) {
             $table->dropColumn(['status', 'approved_iduka_at', 'approved_pembimbing_at', 'rejected_reason']);
        });
    }
};
