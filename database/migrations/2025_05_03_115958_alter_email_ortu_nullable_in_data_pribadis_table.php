<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmailOrtuNullableInDataPribadisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_pribadis', function (Blueprint $table) {
            $table->string('email_ortu')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_pribadis', function (Blueprint $table) {
            // Untuk rollback, kita set kembali ke not nullable
            // Pertama kita perlu mengupdate record yang NULL menjadi nilai default
            DB::statement("UPDATE data_pribadis SET email_ortu = '' WHERE email_ortu IS NULL");
            
            $table->string('email_ortu')->nullable(false)->change();
        });
    }
}