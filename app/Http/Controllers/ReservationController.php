<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\correoReservas;
use App\Notifications\PedidoEnviado;


use Illuminate\Support\Facades\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Twilio\Rest\Client;


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
                'user_id' => $request->input('user_id'),
                'consultand_id' => $request->input('consultand_id'),
                'reservation_date' => $request->input('reservation_date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'reservation_status' => $request->input('reservation_status'),
                'payment_status' => $request->input('payment_status'),
                'total_amount' => $request->input('total_amount'),
            ]);

            Log::info('Reserva creada exitosamente.', ['reservation_id' => $reservation->input('user_id')]);

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


    public function getAllReservations()
    {
        $reservations = Reservation::all();
        $events = [];
        foreach ($reservations as $reservation) {
            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva de ' . $reservation->user->nombres . ' ' . $reservation->user->apellidos . ' con ' . $reservation->consultant->nombres . ' ' . $reservation->consultant->apellidos,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor,
            ];
        }

        log::info('Obteniendo todas las reservas para el calendario.');

        // Retorna los eventos en formato JSON
        header('Content-Type: application/json');

        return response()->json($events);
    }



    public function getReservationsAsesor()
    {

        $consultantId = Auth::user()->id;

        $reservations = Reservation::where('consultand_id', $consultantId)->get();

        $events = [];
        foreach ($reservations as $reservation) {
            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva con ' . $reservation->user->nombres . ' ' . $reservation->user->apellidos,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor,
            ];
        }

        log::info('Obteniendo las reservaciones del asesor para el calendario.');
        // log::info('Consultant ID: ', ['reservas' => $reservations] );

        return response()->json($events);
    }


    public function getReservationsCliente()
    {

        $userId = Auth::user()->id;

        $reservations = Reservation::where('user_id', $userId)->get();

        $events = [];
        foreach ($reservations as $reservation) {
            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva con ' . $reservation->consultant->nombres . ' ' . $reservation->consultant->apellidos,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor,
            ];
        }

        log::info('Obteniendo las reservaciones del cliente para el calendario.');
        log::info('Rservaciones del cliente: ', ['reservas' => $reservations]);

        return response()->json($events);
    }

    public function completePayment(Request $request)
    {

        try {

            $request->validate([
                'orderID' => 'required',
                'details' => 'required',
                'user_id' => 'required|exists:users,id',
                'consultant_id' => 'required|exists:users,id',
                'reservation_date' => 'required|date',
                'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:15:00',
                'end_time' => 'required|date_format:H:i|before_or_equal:15:00',
                'total_amount' => 'required|numeric|min:0',
            ]);

            Log::info('Procesando el pago de la reserva.', [
                'orderID' => $request->orderID,
                'user_id' => $request->user_id,
                'consultant_id' => $request->consultant_id,
                'reservation_date' => $request->reservation_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_amount' => $request->total_amount,
            ]);

            $details = $request->details;
            $payment_status = $details['status'];

            if ($payment_status === 'COMPLETED') {
                Log::info('Voy a generar mi suscripcion.', );
                $reservation = Reservation::create([
                    'user_id' => $request->user_id,
                    'consultand_id' => $request->consultant_id,
                    'reservation_date' => $request->reservation_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'reservation_status' => 'confirmada',
                    'payment_status' => 'pagada',
                    'total_amount' => $request->total_amount,
                ]);

                $transaction_id = $details['id'] ?? null;
                $payer_id = $details['payer']['payer_id'] ?? null;
                $payer_email = $details['payer']['email_address'] ?? null;
                $amount = $details['purchase_units'][0]['amount']['value'] ?? null;

                ReservationDetail::create([
                    'reservation_id' => $reservation->id,
                    'transaction_id' => $transaction_id,
                    'payer_id' => $payer_id,
                    'payer_email' => $payer_email,
                    'payment_status' => $payment_status,
                    'amount' => $amount,
                    'response_json' => json_encode($details),
                ]);

                $this->sendConfirmationEmail($reservation);

                $user = User::find($request->user_id);
                $userPhone = $user->telefono;
                if ($userPhone) {
                    $this->sendWhastsAppMessage($userPhone, $this->generateWhatsAppMessage($reservation, $user));
                }
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            // Log si ocurre cualquier otro error durante la creación
            Log::error('Ocurrió un error al crear la reserva.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Opcional: para un rastreo completo del error
            ]);

            // Redirige con un mensaje de error
            return response()->json(['error' => 'Pago no completado'], 400);
        }

    }

    public function sendConfirmationEmail($reservation)
    {
        $user = User::find($reservation->user_id);
        $consultant = User::find($reservation->consultant_id);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'andercode@anderson-bastidas.com';
            $mail->Password = 'Laravelv1@';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('andercode@anderson-bastidas.com', 'AnderCode Reservas');
            $mail->addAddress($user->email);

            $mail->CharSet = 'UTF-8';

            $mail->Subject = 'Confirmacion de Reserva - AnderCode';

            $html = View::make('emails.reserva', [
                'userName' => $user->nombres . ' ' . $user->apellidos,
                'consultantName' => $consultant->nombres . ' ' . $consultant->apellidos,
                'reservationDate' => $reservation->reservation_date,
                'startTime' => $reservation->start_time,
                'endTime' => $reservation->end_time,
                'totalAmount' => $reservation->total_amount,
            ])->render();

            $mail->isHTML(true);
            $mail->Body = $html;

            $mail->send();

            return back()->with('success', 'Correo enviado correctamente.');

        } catch (Exception $e) {
            Log::error('Error al enviar el correo: ' . $mail->ErrorInfo);
            return back()->with('error', 'Error al enviar el correo :' . $mail->ErrorInfo);
        }
    }


    public function phpMailerTest()
    {

        $reservation = Reservation::find(1); // Cambia el ID según sea necesario
        Log::info('Enviando correo de confirmación de reserva.', [
            'reservation_id' => $reservation,
        ]);
        $user = User::find($reservation->user_id);
        $consultant = User::find($reservation->consultand_id);


        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = false;
            // $mail->Username = 'andercode@anderson-bastidas.com';
            // $mail->Password = 'Laravelv1@';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($user->email);

            $mail->CharSet = 'UTF-8';

            $mail->Subject = 'Confirmacion de Reserva - AnderCode';

            $html = View::make('emails.reserva', [
                'userName' => $user->nombres . ' ' . $user->apellidos,
                'consultantName' => $consultant->nombres . ' ' . $consultant->apellidos,
                'reservationDate' => $reservation->reservation_date,
                'startTime' => $reservation->start_time,
                'endTime' => $reservation->end_time,
                'totalAmount' => $reservation->total_amount,
            ])->render();

            $mail->isHTML(true);
            $mail->Body = $html;

            $mail->send();

            return '¡Correo enviado exitosamente con PHPMailer!';

        } catch (Exception $e) {
            Log::error('Error al enviar el correo: ' . $mail->ErrorInfo);
            return back()->with('error', 'Error al enviar el correo :' . $mail->ErrorInfo);
        }
    }

    public function laravelMailTest()
    {
        // Busca un usuario para enviarle el correo (o créalo)
        $user = User::find(2);

        if ($user) {
            // Envía el correo
            Mail::to($user->email)->send(new correoReservas($user));
            return "¡Correo de prueba enviado!";
        }

        return "No se encontró el usuario.";
    }


    protected function sendWhastsAppMessage($to, $message)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $twilio->messages->create(
            "whatsapp:+{$to}",
            [
                'from' => env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]
        );
    }


    public function whatsTest()
    {
        Log::info('Enviando mensaje de prueba por WhatsApp.');
        $to = '5213318231058'; // Reemplaza con el número de teléfono real
        $message = 'Este es un mensaje de prueba desde Twilio WhatsApp.';
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "whatsapp:+{$to}",
            [
                'from' => "whatsapp:" . env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]
        );

        print ($message->sid);
    }

    public function notificacionTest()
    {
        Log::info('Enviando mensaje de prueba por WhatsApp.');
        $to = '5213318231058'; // Reemplaza con el número de teléfono real
        $message = 'Este es un mensaje de prueba desde Twilio WhatsApp.';
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "whatsapp:+{$to}",
            [
                'from' => "whatsapp:" . env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]
        );

        print ($message->sid);
    }

    protected function generateWhatsAppMessage($reservation, $user)
    {
        return "Hola {$user->nombres}" . " " . "{$user->apellidos}, tu reserva ha sido confirmada.\n" .
            "Fecha: {$reservation->reservation_date}\n" .
            "Hora de Inicio: {$reservation->start_time}\n" .
            "Hora de Fin: {$reservation->end_time}\n" .
            "Costo Total: {$reservation->total_amount}\n" .
            "Gracias por elegir nuestros servicios.\n" .
            "AnderCode.\n";
    }

    public function createCliente()
    {
        $consultants = User::where('role_id', 2)->whereNull('deleted_at')->get();
        return view('cliente.reserva', compact('consultants'));
    }

    public function indexcliente()
    {
        $userId = Auth::user()->id; // Obtener el ID del usuario autenticado
        $reservations = Reservation::where('user_id', $userId)->get(); // Obtener solo las reservas del usuario
        return view('cliente.index', compact('reservations'));
    }


    public function approve($userId)
    {

        $user = User::findOrFail($userId);

        // 2. Enviamos la notificación.
        $user->notify(new PedidoEnviado($user));

        // 3. Redirigimos con un mensaje de éxito.
        return 'Aqui andamos parienton';
    }

        public function showPayments(){
        $payments = ReservationDetail::with(['reservation.user','reservation.consultant'])->get();
        Log::info('Mostrando todos los pagos.', [
            'payments' => $payments
        ]);
        return view('reservation.pagos',compact('payments'));
    }

    // Método para mostrar los pagos del cliente autenticado
    public function showClientPayments(){
        $userId = Auth::id();

        $payments = ReservationDetail::whereHas('reservation',function($query) use ($userId){
            $query->where('user_id',$userId);
        })->get();
        return view('cliente.pagos',compact('payments'));
    }

    public function getAllReservationsLanding(){
        $reservations = Reservation::all();
        $events = [];
        foreach($reservations as $reservation){
            $color = '#28a745';
            $bordercolor = '#28a745';

            if($reservation->reservation_status === 'pendiente'){
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            }elseif($reservation->reservation_status === 'cancelada'){
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => $reservation->consultant->nombres .' '. $reservation->consultant->apellidos,
                'start' => $reservation->reservation_date.'T'.$reservation->start_time,
                'end' => $reservation->reservation_date.'T'.$reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor,
            ];
        }

        return response()->json($events);
    }

}
