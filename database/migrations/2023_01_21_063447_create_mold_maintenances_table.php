<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //قسم صيانه الاستنباط
        Schema::create('mold_maintenances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('maintenance_process_id')->comment('انهي ورديه صيانه');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('maintenance_date');
            $table->string('mold_stop_reason')->comment('سبب عطل الاستنباط');
            $table->string('mold_repair_time')->comment('زمن اصلاح العطل');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('maintenance_process_id')->references('id')->on('maintenance_processes')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mold_maintenances');
    }
}
