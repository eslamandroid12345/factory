<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //جدول حقن الماكينات
        Schema::create('injections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_injected');
            $table->unsignedBigInteger('machine_id')->comment('عمليه الحقن دي تابعه لانهي ماكينه');
            $table->unsignedBigInteger('injection_process_id')->comment('عمليه الحقن دي تابعه لانهي ورديه');
            $table->unsignedBigInteger('admin_id')->comment('لازم تدخل الحركه دي تبع انهي مصنع');
            $table->unsignedBigInteger('user_id')->nullable()->comment('انهي مهندس او مدير ضاف الحركه دي');
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
            $table->foreign('injection_process_id')->references('id')->on('injection_processes')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('injections');
    }
}
