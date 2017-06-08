<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('文章自增id');
            $table->string('title', 50)->comment('标题');
            $table->longText('content')->comment('内容');

            $table->Integer('read')->default(0)->comment('阅读数量');
            $table->tinyInteger('group')->comment('所属组');
            $table->tinyInteger('status')->comment('状态');

            $table->timestamps();
            $table->index('group');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles', function (Blueprint $table) {
            //
        });
    }
}
