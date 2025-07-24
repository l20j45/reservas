<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Reservation extends Model
{
    use Notifiable;
    use HasFactory;
    protected $table = 'reservation';
    protected $fillable = [
        'user_id',
        'consultand_id',
        'reservation_date',
        'start_time',
        'end_time',
        'reservation_status',
        'total_amount',
        'payment_status',
        'cancelation_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class );
    }

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultand_id');
    }

        public function routeNotificationForTwilio()
    {
        return '+5213318231058';
    }

        public function reservationDetail()
    {
        // Una reserva tiene un detalle asociado
        return $this->hasOne(ReservationDetail::class);
    }


}
