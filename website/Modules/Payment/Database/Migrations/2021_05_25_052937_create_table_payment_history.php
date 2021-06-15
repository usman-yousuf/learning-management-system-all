<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();
            $table->double('amount', 20, 6)->default(false);

            // refereence table names against which payment is made
            $table->string('ref_model_name')->comment('e.g courses');
            $table->bigInteger('ref_id');
            $table->string('additional_ref_model_name')->nullable()->comment('e.g specific handout to purchase');;
            $table->bigInteger('additional_ref_id')->nullable();

            // payment methods and relative trans ids and statuses
            $table->string('stripe_trans_id')->nullable();
            $table->string('stripe_trans_status')->nullable();
            $table->bigInteger('stripe_card_id')->nullable();
            $table->string('easypaisa_trans_id')->nullable();
            $table->string('easypaisa_trans_status')->nullable();
            $table->enum('payment_method', ['free', 'points', 'stripe', 'easypaisa'])->default('free');

            $table->integer('payee_id')->unsigned()->comment('usually Student');;
            $table->enum('status', ['pending', 'successfull', 'declined', 'failed', 'aborted'])->default('pending')->comment('payment status to note in application end');;

            $table->index('payee_id');
            $table->foreign('payee_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('payment_histories');
    }
}
