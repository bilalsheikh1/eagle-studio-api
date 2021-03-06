<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBecomeSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('become_sellers', function (Blueprint $table) {
            $table->id();
            $table->string('developer_type');
            $table->string('development_experience');
            $table->string('paypal_email');
            $table->string('company_name');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_zip_postal_code');
            $table->string('VAT_number')->nullable(true);
            $table->smallInteger('active')->default('0')->comment('active = 1 means become seller request approved active =0 is pending and active = 2 means request discard');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('become_sellers');
    }
}
