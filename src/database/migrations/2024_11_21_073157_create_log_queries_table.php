<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phongtran\Logger\app\Models\LogQuery;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $logTable = new LogQuery();
        $connection = $logTable->getConnectionName();
        $table = $logTable->getTableName();
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id');
                $table->longText('query')->nullable();
                $table->string('execution_time')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        $logTable = new LogQuery();
        $connection = $logTable->getConnectionName();
        $table = $logTable->getTableName();

        Schema::connection($connection)->dropIfExists($table);
    }
};
