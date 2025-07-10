<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $userIds = User::where('role_id', 3)->pluck('id')->toArray();
        $consultantIds = User::where('role_id', 2)->pluck('id')->toArray();
        $reservationDate = Carbon::now()->addDays(rand(1, 30));
        $startTime = Carbon::parse($reservationDate->startOfDay())->addSeconds(rand(32400, 61200));
        $endTime = (clone $startTime)->addHours(rand(1, 3));

        // Genera una fecha de reserva entre hoy y los próximos 30 días
        // $reservationDate = $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d');
        // // Genera una hora de inicio entre las 9:00 AM y las 3:00 PM
        // $startTime = $this->faker->numberBetween(9, 15);
        // // La hora de fin será una hora después de la hora de inicio
        // $endTime = $startTime + 1;

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'consultand_id' => $this->faker->randomElement($consultantIds),
            'reservation_date' => $reservationDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'reservation_status' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'payment_status' => $this->faker->randomElement(['pendiente','pagada','cancelada']),
            'total_amount' => $this->faker->numberBetween(1000, 10000),
        ];

        //         return [
        //     'user_id' => $this->faker->randomElement($userIds), // Selecciona un ID de usuario aleatorio
        //     'consultand_id' => $this->faker->randomElement($consultantIds), // Selecciona un ID de consultor aleatorio
        //     'reservation_date' => $reservationDate, // Establece la fecha de la reserva
        //     'start_time' => sprintf('%02d:00', $startTime), // Formato de hora de inicio (HH:00)
        //     'end_time' => sprintf('%02d:00', $endTime), // Formato de hora de fin (HH:00)
        //     'reservation_status' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']), // Genera un estado de reserva aleatorio
        //     'payment_status' => $this->faker->randomElement(['pendiente', 'pagado', 'fallido']), // Genera un estado de pago aleatorio
        //     'total_amount' => 50, // Monto total predeterminado (50 USD)
        // ];
    }
}
