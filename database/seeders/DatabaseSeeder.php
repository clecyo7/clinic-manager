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
    public function run(): void
    {
        // Criar um psicólogo para testes
        $psychologist = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Criar alguns pacientes
        $patients = Patient::factory(5)->create([
            'psychologist_id' => $psychologist->id
        ]);

        foreach ($patients as $patient) {
            // Criar múltiplas consultas para cada paciente
            for ($i = 0; $i < 3; $i++) {
                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'psychologist_id' => $psychologist->id,
                    'date_time' => now()->addDays($i + 1),
                    'status' => 'pendente'
                ]);

                // Criar pagamento para cada consulta
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => rand(150, 300),
                    'payment_method' => fake()->randomElement(['dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'transferencia']),
                    'status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
                    'payment_date' => now()
                ]);

                // Criar prontuário para cada consulta
                MedicalRecord::create([
                    'patient_id' => $patient->id,
                    'appointment_id' => $appointment->id,
                    'diagnosis' => fake()->paragraph(),
                    'treatment_plan' => fake()->paragraph(),
                    'observations' => fake()->paragraph()
                ]);
            }
        }
    }
}
