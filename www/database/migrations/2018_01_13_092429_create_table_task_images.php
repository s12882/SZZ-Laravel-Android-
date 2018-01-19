<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTaskImages extends Migration {

    public function up() {
        Schema::create('task_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('original_file_name');
            $table->string('type');
            $table->integer('uploaded_by')->unsigned()->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->integer('task_id')->unsigned()->nullable();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('task_images');
        Schema::enableForeignKeyConstraints();
    }

}
