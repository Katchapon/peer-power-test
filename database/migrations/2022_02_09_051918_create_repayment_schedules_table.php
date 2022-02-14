<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('payment_no');
            $table->dateTime('date');
            $table->decimal('payment_amount', 21, 6);
            $table->decimal('principal', 21, 6);
            $table->decimal('interest', 21, 6);
            $table->decimal('balance', 21, 6);
            $table->timestamps();
        });

        Schema::table('repayment_schedules', function (Blueprint $table) {
            $table->foreignId('loan_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repayment_schedules');
    }
};
