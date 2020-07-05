<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('access_code')->unique()->nullable();
            $table->string('reference')->nullable();
            $table->decimal('amount', 9,3);
            $table->enum('status', ['processing', 'success', 'on hold', 'pending', 'completed', 'canceled', 'failed'])->default('pending');
            $table->string('message')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('currency_code')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
