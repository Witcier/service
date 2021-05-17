<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('用户组名');
            $table->string('rules', 4000)->comment('规则');
            $table->unsignedInteger('pid')->default(0)->comment('父级用户组id');
            $table->unsignedTinyInteger('type')->comment('分组类型 1普通 2主管 3开发');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('groups');
    }
}
