<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;


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
        // Obtener los usuarios con rol de cliente (rol_id = 3)
        $users = User::where('role_id', 3)->whereNull('deleted_at')->get();
        // Obtener los consultores (rol_id = 2)
        $consultants = User::where('role_id', 2)->whereNull('deleted_at')->get();
        return view('reservation.create', compact('users', 'consultants'));
    }

    // Método para mostrar la vista de creación de una reserva desde el lado del cliente


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'consultand_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:17:00',
            'end_time' => 'required|date_format:H:i|after:start_time|before_or_equal:17:00',
            'reservation_status' => 'required|in:pendiente,confirmada,cancelada',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'total_amount' => 'required|numeric|min:0',
        ]);
        Log::info('Listado de reservas obtenido exitosamente.', [
            'datos' => $request
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
                'consultand_id' => $request->consultand_id,
                'reservation_date' => $request->reservation_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reservation_status' => $request->reservation_status,
                'payment_status' => $request->payment_status,
                'total_amount' => $request->total_amount,
            ]);

            Log::info('Reserva creada exitosamente.', ['reservation_id' => $reservation->id]);

            return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente.');

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
        $reservation = Reservation::findOrFail($id);
        $reservation->start_time = Carbon::parse($reservation->start_time)->format('H:i');
        $reservation->end_time = Carbon::parse($reservation->end_time)->format('H:i');
        $users = User::where('role_id', 3)->whereNull('deleted_at')->get();
        $consultants = User::where('role_id', 2)->whereNull('deleted_at')->get();
        // Log::info('Formulario de edición de reserva cargado.', [
        //     'reservation' => $reservation,
        // ]);
        // Log::info('Usuarios  obtenidos para la edición de reserva.', [
        //     'users' => $users,
        // ]);
        // Log::info('Cargando vista de edición de reserva.', [
        //     'consultants' => $consultants
        // ]);
        return view('reservation.edit', compact('reservation', 'users', 'consultants'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'consultand_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:17:00',
            'end_time' => 'required|date_format:H:i|after:start_time|before_or_equal:17:00',
            'reservation_status' => 'required|in:pendiente,confirmada,cancelada',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'total_amount' => 'required|numeric|min:0',
        ]);


        // Log en caso de que la validación falle
        if ($validator->fails()) {
            Log::error('La validación para crear usuario falló.', [
                'errors' => $validator->errors()
            ]);
            // Redirige de vuelta con los errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            $reserva = Reservation::findOrFail($id);
            if (!$reserva) {
                return redirect()->route('reservations.index')->with('error', 'Reserva no encontrada.');
            }

            $reserva->update($request->all());

            Log::info('Reserva actualizada exitosamente.', ['reserva_id' => $reserva->id]);

            return redirect()->route('reservations.index')->with('success', 'Reserva actualizada exitosamente.');

        } catch (\Exception $e) {
            // Log si ocurre cualquier otro error durante la creación
            Log::error('Ocurrió un error al crear la reserva.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Opcional: para un rastreo completo del error
            ]);

            // Redirige con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al actualizar el usuario.');
        }

    }

    public function cancel(Request $request)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservation,id',
            'cancelation_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('La validación para crear usuario falló.', [
                'errors' => $validator->errors()
            ]);
            // Redirige de vuelta con los errores
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $reservation = Reservation::findOrFail($request->reservation_id);

            if (!$reservation) {
                return redirect()->route('reservations.index')->with('error', 'Reserva no encontrada.');
            }
            $reservation->reservation_status = 'cancelada'; // Cambia el estado a 'cancelada'
            $reservation->cancelation_reason = $request->cancellation_reason;
            $reservation->save();

            return response()->json([
                'success' => true,
                'message' => 'La reserva ha sido cancelada exitosamente',
            ]);
        } catch (\Exception $e) {
            // Log si ocurre cualquier otro error durante la creación
            Log::error('Ocurrió un error al crear la reserva.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Opcional: para un rastreo completo del error
            ]);

            // Redirige con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al actualizar el usuario.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
