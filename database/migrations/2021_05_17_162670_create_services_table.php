<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('username')->comment('平台账号');
            $table->string('nick')->nullable()->comment('昵称');
            $table->dateTime('birth')->nullable()->comment('生日');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('mobile')->nullable()->comment('电话');
            $table->boolean('sex')->nullable()->comment('性别');
            $table->string('photo')->default()->comment('头像');
            $table->boolean('is_service')->default(true)->comment('是否为客服');
            $table->unsignedBigInteger('platform_id');
            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->nullable()->comment('在线状态');
            $table->unsignedInteger('mark_time')->nullable()->comment('提醒时间，单位：分钟');
            $table->boolean('is_auto_reply')->default(false)->comment('是否自动回复');
            $table->string('auto_reply_content')->nullable()->comment('自动回复内容');
            $table->unsignedTinyInteger('area')->nullable()->comment('区域');
            $table->unsignedInteger('note_id')->nullable()->comment('备忘录id');
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
        Schema::dropIfExists('services');
    }
}
