<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLaravelJqueryChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_jquery_chats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('message');
            $table->integer('user_from_id')->unsigned();
            $table->foreign('user_from_id')->references('id')->on('users');
            $table->integer('user_to_id')->unsigned();
            $table->foreign('user_to_id')->references('id')->on('users');
            $table->datetime('entregado_from_at')->nullable();
            $table->datetime('entregado_to_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('laravel_jquery_chats');
    }
}