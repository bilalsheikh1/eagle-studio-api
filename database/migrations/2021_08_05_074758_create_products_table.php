<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false)->comment("status = 0 draft, status = 1 approved, status = 2 discard, status = 3 pending, status = 4 sales ended");
            $table->string('title')->unique();
            $table->text('description');
            $table->text('features');
            $table->string('youtube_link')->nullable();
            $table->string('google_play_link')->nullable();
            $table->string('app_store_link')->nullable();
            $table->double('single_app_license')->default(0);
            $table->double('multi_app_license')->default(0);
            $table->double('reskinned_app_license')->default(0);
            $table->string('development_hours')->nullable();
            $table->foreignId('product_template_id');
            $table->foreignId('product_category_id');
            $table->foreignId('product_subcategory_id');
            $table->foreignId('framework_id')->nullable();
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
        Schema::dropIfExists('products')->disableForeignKeyConstraints();
    }
}
