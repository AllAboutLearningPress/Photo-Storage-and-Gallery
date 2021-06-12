<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->bigInteger('size')->nullable()->comment('size in bytes');
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->string('file_type')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('parent_id')->nullable()->constrained('photos');
            $table->string('file_name')->nullable();
            $table->string('location')->nullable();
            $table->string('license')->nullable();
            $table->boolean('should_process')->default(false);
            $table->dateTime('time_taken')->nullable();
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
        Schema::dropIfExists('photos');
    }
}
