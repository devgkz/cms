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
            $table->text('introtext');
            $table->mediumText('content');
            $table->string('tag', 255);
            
            $table->string('template');
            $table->string('layout');
            
            $table->boolean('in_menu');
            $table->boolean('is_pin');
            $table->unsignedTinyInteger('section_menu');
            $table->string('order_childs_by');
            $table->unsignedInteger('sort_order');
            $table->unsignedInteger('status');
            
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