<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateAndAddNewColumnsInSprintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::table('sprints', function (Blueprint $table) {
            DB::statement('ALTER TABLE sprints 
            CHANGE COLUMN `from` `from` TIME NOT NULL;');
            DB::statement('ALTER TABLE sprints 
            CHANGE COLUMN `to` `to` TIME NOT NULL;');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
