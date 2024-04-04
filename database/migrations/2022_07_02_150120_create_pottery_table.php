<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pottery', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10);
            $table->string('area', 3);
            $table->string('addendum', 2)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('square', 10)->nullable();
            $table->string('stratum', 15)->nullable();
            $table->string('type', 30)->nullable();
            $table->string('cross_ref', 130)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('notes', 200)->nullable();
            $table->string('elevation', 20)->nullable();
            $table->unsignedMediumInteger('order_column')->default(0);

            $table->unique(['area', 'name']);
        });

        Schema::create('pottery_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('pottery_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('group_id')
                ->references('id')
                ->on('pottery_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('pottery-pottery_tags', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')->on('pottery')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('pottery_tags')->onUpdate('cascade');

            $table->primary(['item_id', 'tag_id']);
        });

        ///////

        Schema::create('pottery_orig', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10)->nullable();
            $table->string('type', 30)->nullable();
            $table->string('stratum', 15)->nullable();
            $table->string('area', 10)->nullable();
            $table->string('square', 10)->nullable();
            $table->string('elevation', 20)->nullable();
            $table->string('cross_ref', 130)->nullable();

            //$table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pottery');
    }
}
