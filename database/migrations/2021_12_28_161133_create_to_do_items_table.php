<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToDoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_do_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autor_id')->references('id')->on('id')->cascadeOnDelete();
            $table->text('task');
            $table->timestamp('term')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('done')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('to_do_items');
    }
}
