<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('slug');
            $table->string('title');
            $table->text('introtext')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('tag', 255)->nullable();
            
            $table->string('template');
            $table->string('layout')->nullable();
            
            $table->boolean('in_menu')->default(0);
            $table->boolean('is_pin')->default(0);
            $table->unsignedTinyInteger('section_menu')->default(0);
            $table->string('order_childs_by')->nullable();
            $table->unsignedInteger('sort_order')->nullable();
            $table->unsignedInteger('status')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
