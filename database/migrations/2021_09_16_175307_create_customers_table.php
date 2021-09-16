<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('customer_photo')->nullable();
            $table->string('nationality')->nullable();
            $table->string('identification_number')->unique();
            $table->string('identification_photo')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('introductory_letter')->nullable();
            $table->string('referee_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('alternative_phone_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('created_by')->index();
            $table->uuid('lead_sorce')->index();

            $table->foreign('created_by')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
