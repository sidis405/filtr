<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('link_id');
            $table->integer('entity_id');
            $table->integer('count');
            $table->double('relevance', 16,14);
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
        Schema::drop('entity_link');
    }
}
