<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypals', function (Blueprint $table) {
            $table->id();
            $table->string("paypal_id")->unique();
            $table->string("intent");
            $table->string("country_code")->default("US");
            $table->string("payer_name");
            $table->string("payer_surname");
            $table->string("payer_email");
            $table->string("payer_id");
            $table->string("currency_code")->default("USD");
            $table->double("amount");
            $table->string("payee_email");
            $table->string("payee_merchant_id");
            $table->string("paypal_payment_status")->default("PENDING");
            $table->string("status");
            $table->foreignId("purchase_id")->constrained("purchases");
            $table->foreignId("order_id")->constrained("orders");
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
        Schema::dropIfExists('paypals');
    }
}
