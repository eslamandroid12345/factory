<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('image')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('admin_id')->comment('صاحب المصنع اللي ضافه');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status',['enabled', 'disabled'])->default('enabled');
            $table->string('phone')->nullable();
            $table->enum('type',[
               'storekeeper',
               'raw_store_keeper',
                'item_store_keeper',
                'maintenance_manager',
                'maintenance_engineer',
                'production_manager',
                'production_engineer'

            ])->comment("[
            'storekeeper' => 'امين مخازن',
            'raw_store_keeper' => 'امين مخازن خاصه',
            'item_store_keeper' => ' امين مخازن اصناف',
            'maintenance_manager' => 'مدير الصيانه',
            ");
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
