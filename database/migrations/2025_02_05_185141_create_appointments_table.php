<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('appointments')) {
            Schema::dropIfExists('appointments');
        }

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('psychologist_id')->constrained('users');
            $table->dateTime('date_time');
            $table->text('notes')->nullable();
            $table->enum('status', ['pendente', 'concluida', 'cancelada'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}