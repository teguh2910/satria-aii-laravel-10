<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSjsR1 extends Migration
{
    private function hasDoaiiUniqueIndex(): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('sjs')");
            foreach ($indexes as $index) {
                if (($index->name ?? null) === 'sjs_doaii_unique') {
                    return true;
                }
            }

            return false;
        }

        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'sjs')
            ->where('index_name', 'sjs_doaii_unique')
            ->exists();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $indexExists = $this->hasDoaiiUniqueIndex();

        if ($indexExists) {
            Schema::table('sjs', function (Blueprint $table) {
                $table->dropUnique('sjs_doaii_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $indexExists = $this->hasDoaiiUniqueIndex();

        if (! $indexExists) {
            Schema::table('sjs', function (Blueprint $table) {
                $table->unique('doaii', 'sjs_doaii_unique');
            });
        }
    }
}
