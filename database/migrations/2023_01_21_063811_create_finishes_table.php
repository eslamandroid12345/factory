<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //قسم التشطيبات
        Schema::create('finishes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('finishing_process_id')->comment('انهي ورديه تشطيب');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('finish_date');
            $table->string('worker_name');
            $table->integer('production_quantity');
            $table->string('job_description');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('finishing_process_id')->references('id')->on('finishing_processes')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finishes');
    }
}
