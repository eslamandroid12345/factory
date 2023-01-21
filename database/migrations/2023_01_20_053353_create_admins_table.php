<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  اصحاب المصانع
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('developer_id')->comment('المطور اللي ضافه');
            $table->string('factory_name');
            $table->longText('image')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('factory_owner')->nullable()->comment('اسم صاحب المصنع');
            $table->string('factory_phone')->nullable();
            $table->string('commercial_registration')->nullable()->comment('رقم السجل التجاري');
            $table->timestamp('last_seen')->nullable()->comment('اخر عمليه تسجيل دخول');
            $table->integer('access_days')->comment('عدد ايام المسموحه له من فتح النسخه');
            $table->enum('status',['enabled', 'disabled'])->default('enabled')->comment('حاله المصنع');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('developer_id')->references('id')->on('developers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
