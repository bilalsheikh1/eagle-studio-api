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
            $table->string("title");
            $table->string("description");
            $table->string("features");
            $table->string("featured_image");
            $table->string("thumbnail_image");
            $table->string("youtube_link")->nullable();
            $table->string("google_play_link")->nullable();
            $table->string("app_store_link")->nullable();
            $table->string("file_src");
            $table->string("price");
            $table->unsignedBigInteger("product_category_id");
            $table->unsignedBigInteger("product_platform_id");
            $table->unsignedBigInteger("product_type_id");
            $table->unsignedBigInteger("framework_id");
            $table->unsignedBigInteger("image_id");
            $table->unsignedBigInteger("created_by");
            $table->unsignedBigInteger("operating_system_id");
            $table->foreign("product_category_id")->references("id")->on("product_categories");
            $table->foreign("product_platform_id")->references("id")->on("product_platforms");
            $table->foreign("product_type_id")->references("id")->on("product_types");
            $table->foreign("framework_id")->references("id")->on("frameworks");
            $table->foreign("created_by")->references("id")->on("web_users");
            $table->foreign("image_by")->references("id")->on("images_videos");
            $table->foreign("operating_system_id")->references("id")->on("operating_systems");
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
