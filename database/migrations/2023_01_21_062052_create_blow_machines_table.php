<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlowMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //جدول نفخ الماكينات
        Schema::create('blow_machines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_bow');
            $table->unsignedBigInteger('machine_id')->comment('عمليه النفخ دي تابعه لانهي ماكينه');
            $table->unsignedBigInteger('blowing_process_id')->comment('عمليه النفخ دي تابعه لانهي ورديه نفخ');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('worker_name');
            $table->string('mold_name')->comment('اسم الاسطنبه');
            $table->integer('production_quantity')->comment('كميه الانتاج');
            $table->integer('scrap_quantity')->comment('كميه الهالك');
            $table->string('cycle_time')->comment('زمن الدوره');
            $table->string('stop_time')->comment('زمن التوقف');
            $table->string('stop_reason')->comment('سبب التوقف');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('machine_id')->references('id')->on('machines')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('blowing_process_id')->references('id')->on('blowing_processes')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blow_machines');
    }
}
