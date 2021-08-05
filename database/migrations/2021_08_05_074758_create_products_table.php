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
            $table->string('name')->unique();
            $table->boolean('status')->default(false);
            $table->string('title');
            $table->text('description');
            $table->text('features');
            $table->string('youtube_link')->default('');
            $table->string('google_play_link')->default('');
            $table->string('app_store_link')->default('');
            $table->string('single_app_license');
            $table->string('multi_app_license');
            $table->string('development_hours')->default('');
            $table->unsignedBigInteger('product_template_id');
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_subcategory_id');
            $table->unsignedBigInteger('framework_id')->nullable();
            $table->foreign('product_template_id')->references('id')->on('product_templates');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->foreign('product_subcategory_id')->references('id')->on('product_subcategories');
            $table->foreign('framework_id')->references('id')->on('frameworks');
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
        Schema::dropIfExists('products');
    }
}
