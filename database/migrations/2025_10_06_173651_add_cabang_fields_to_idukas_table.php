<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCabangFieldsToIdukasTable extends Migration
{
    public function up()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->tinyInteger('is_pusat')
                ->default(1)
                ->comment('1: Lokasi Pusat, 0: Lokasi Cabang')
                ->after('radius');

            $table->integer('id_pusat')
                ->nullable()
                ->comment('ID dari lokasi pusat (jika ini cabang)')
                ->after('is_pusat');

            $table->index('id_pusat', 'idx_id_pusat');
        });
    }

    public function down()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->dropIndex('idx_id_pusat');
            $table->dropColumn(['is_pusat', 'id_pusat']);
        });
    }
}

