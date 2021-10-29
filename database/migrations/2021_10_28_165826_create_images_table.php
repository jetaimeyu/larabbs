<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('images')){
            Schema::create('images', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->index();
                $table->string('type')->index();
                $table->string('path');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('iamges')){
            Schema::dropIfExists('images');
        }
    }
}
