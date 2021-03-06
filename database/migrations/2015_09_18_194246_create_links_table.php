<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->longText('code')->nullable();
            $table->longText('content')->nullable();
            $table->string('author_name')->nullable();
            $table->integer('time_to_read')->nullable();
            $table->integer('read_counter')->default(0);
            $table->string('user_id');
            $table->string('slug');
            $table->string('domain');
            $table->string('hash');
            $table->string('status')->default(0);
            $table->string('indexed_links')->default(0);
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
        Schema::drop('links');
    }
}
