<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las reservas con sus relaciones de usuario y consultor
        $reservations = Reservation::with(['user', 'consultant'])->get();
        Log::info('Listado de reservas obtenido exitosamente.', [
            'reservations' => $reservations
        ]);
        return view('reservation.index', compact('reservations'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('role_id', 3)->whereNull('deleted_at')->get();
        $consultant = User::where('role_id', 2)->whereNull('deleted_at')->get();
        return view('reservation.create', compact('user', 'consultant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'consultant_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:17:00',
            'end_time' => 'required|date_format:H:i|after:start_time|before_or_equal:17:00',
            'reservation_status' => 'required|in:pendiente,confirmada,cancelada',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Log en caso de que la validación falle
        if ($validator->fails()) {
            Log::error('La validación para crear la reserva falló.', [
                'errors' => $validator->errors()
            ]);
            // Redirige de vuelta con los errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'consultant_id' => $request->consultant_id,
                'reservation_date' => $request->reservation_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reservation_status' => $request->reservation_status,
                'payment_status' => $request->payment_status,
                'total_amount' => $request->total_amount,
            ]);

            Log::info('Reserva creada exitosamente.', ['reservation_id' => $reservation->id]);

            return redirect()->route('reservation.index')->with('success', 'Reserva creada exitosamente.');

        } catch (\Exception $e) {
            // Log si ocurre cualquier otro error durante la creación
            Log::error('Ocurrió un error al crear la reservacion.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Opcional: para un rastreo completo del error
            ]);

            // Redirige con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al crear la reservacion.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
