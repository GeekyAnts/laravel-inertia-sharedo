<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewUsersSharedDialogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_users_shared_dialog', function (Blueprint $table) {
            $table->id();
            $table->string('has_ever_logged_in');
            $table->unsignedBigInteger('user_id');
            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users');
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
        Schema::dropIfExists('new_users_shared_dialog');
    }
}
