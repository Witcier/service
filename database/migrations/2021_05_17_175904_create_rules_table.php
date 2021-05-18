<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('名称');
            $table->string('name')->comment('定义');
            $table->unsignedTinyInteger('level')->comment('级别。1模块,2控制器,3操作');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级id');
            $table->foreign('parent_id')->references('id')->on('rules')->onDelete('cascade');
            $table->boolean('status')->default(true)->comment('状态');
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
        Schema::dropIfExists('rules');
    }
}
