<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\MedicalRecord;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DefaultUserSeeder::class
        ]);
    }
}
