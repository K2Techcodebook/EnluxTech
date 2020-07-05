<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->string('transactionId')->nullable();
            $table->string('requestId')->nullable();
            $table->string('type')->nullable();
            $table->string('product_name')->nullable();
            $table->string('response_description')->nullable();
            $table->string('amout')->nullable();
            $table->string('phone')->nullable();
            $table->string('quantity')->nullable();
            $table->string('transaction_date')->nullable();
            $table->timestamps();
        });

       Schema::table('transactions', function (Blueprint $table) {
         $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
