<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToDoItemUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_do_item_user', function (Blueprint $table) {
            $table->foreignId('to_do_item_id')->constrained();//->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();//->cascadeOnDelete();

            $table->primary(['to_do_item_id','user_id']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('to_do_item_user', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['to_do_item_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('to_do_item_user');
    }
}
