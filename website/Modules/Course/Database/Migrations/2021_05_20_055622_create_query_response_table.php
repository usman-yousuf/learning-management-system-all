<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueryResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_responses', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('query_id')->unsigned();
            $table->integer('responder_id')->unsigned();

            $table->string('body');
            $table->bigInteger('tagged_query_response_id')->nullable()->comment('Query response this message is reply to');

            $table->index('query_id');
            $table->foreign('query_id')->references('id')->on('queries')->onUpdate('cascade')->onDelete('cascade');

            $table->index('responder_id');
            $table->foreign('responder_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('tagged_query_response_id');
            $table->foreign('tagged_query_response_id')->references('id')->on('query_response')->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('query_responses');
    }
}
