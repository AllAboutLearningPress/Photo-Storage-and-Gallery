f\<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSharePhotosTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('share_photos', function (Blueprint $table) {
                $table->id();
                $table->string('share_key')->index();
                $table->foreignId('photo_id')->constrained('photos');
                $table->boolean('view_info')->default(false);
                $table->boolean('download')->default(false);

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
            Schema::dropIfExists('share_photos');
        }
    }
