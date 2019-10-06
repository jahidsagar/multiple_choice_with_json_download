<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToQuestionTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_tag', function (Blueprint $table) {
            
            //$table->timestamps();
            $table->timestamp('created_at')->default('2019-01-15 12:18:39');
            
            $table->timestamp('updated_at')->default('2019-01-15 12:18:39');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_tag', function (Blueprint $table) {
            
            $table->dropTimestamps();
            //$table->dropColumn('created_at');
        });
    }
}
