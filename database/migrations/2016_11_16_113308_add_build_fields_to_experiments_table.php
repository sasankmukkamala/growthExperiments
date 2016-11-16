<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildFieldsToExperimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiments', function (Blueprint $table) {
            $table->date('bl_startdate')->nullable()->after('pr_priority');
            $table->date('bl_enddate')->nullable()->after('bl_startdate');
            $table->string('bl_assignees')->nullable()->after('bl_enddate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiments', function (Blueprint $table) {
            $table->dropColumn('bl_startdate');
            $table->dropColumn('bl_enddate');
            $table->dropColumn('bl_assignees');
        });
    }
}
