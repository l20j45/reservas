<x-app-layout>
    @push('styles')
        <!--datatable css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
        <!--datatable responsive css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
    @endpush


<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Nueva Reserva</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Reserva</a></li>
                    <li class="breadcrumb-item active">Nueva Reserva</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Crear Nueva Reserva</h4>
            </div>
            <div class="card-body">
                <form class="row gy-1" id="reservationForm">
                    @csrf

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="user" class="form-label">{{ __('Usuario') }}</label>
                            <input id="user" type="text" class="form-control @error('reservation_date') is-invalid @enderror" value="{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}" readonly>
                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="consultant_id" class="form-label">{{ __('Consultor') }}</label>
                            <select class="form-select @error('consultant_id') is-invalid @enderror" id="consultant_id" name="consultant_id" required>
                                <option value="">Seleccionar Consultor</option>
                                @foreach ($consultants as $consultant )
                                    <option value="{{ $consultant->id }}">{{ $consultant->nombres }} {{ $consultant->apellidos }}</option>
                                @endforeach
                            </select>
                            @error('consultant_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="reservation_date" class="form-label">{{ __('Fecha de Reserva') }}</label>
                            <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}" required>
                            @error('reservation_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="start_time" class="form-label">{{ __('Hora de Inicio') }}</label>
                            <select class="form-select @error('start_time') is-invalid @enderror" id="start_time" name="start_time" required>
                                <option value="">Seleccionar una hora</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                            </select>
                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="end_time" class="form-label">{{ __('Hora Fin') }}</label>
                            <input type="text" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" readonly>
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="reservation_status" class="form-label">{{ __('Estado de la Reserva') }}</label>
                            <select class="form-select @error('reservation_status') is-invalid @enderror" id="reservation_status" name="reservation_status" required>
                                <option value="">Seleccionar un estado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                            </select>
                            @error('reservation_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="total_amount" class="form-label">{{ __('Total a pagar (USD)') }}</label>
                            <input type="text" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" readonly>
                            @error('total_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="payment_status" class="form-label">{{ __('Metodo de Pago') }}</label>
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script src="https://www.paypal.com/sdk/js?client-id=AYdoxFKCMImSEQcVEAt-zV2yD6XVZQ7Tagksg2x7x6YmlLbQ4ncpWDNPfMO_vx6rLNubFkfdvMaysLCD&currency=MXN"></script>

<script>
    // Obtiene la fecha actual en formato YYYY-MM-DD y la establece como mínimo en el campo de fecha de reserva
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('reservation_date').setAttribute('min', today);

    const pricePerHour = 300; // Define el precio por hora

    // Evento cuando el usuario selecciona la hora de inicio
    document.getElementById('start_time').addEventListener('change', function() {
        const startTime = this.value; // Obtiene el valor de la hora de inicio seleccionada

        if (startTime) {
            // Convierte la hora de inicio en un objeto Date (usando una fecha ficticia)
            const startDate = new Date(`1970-01-01T${startTime}:00`);
            // Añade una hora al objeto Date
            startDate.setHours(startDate.getHours() + 1);
            // Formatea la nueva hora como HH:MM
            const endTime = startDate.toTimeString().slice(0, 5);
            // Establece el valor del campo de hora de finalización
            document.getElementById('end_time').value = endTime;

            // Calcula el total por una hora (puedes ajustar si hay múltiplos de horas)
            const total = pricePerHour; // Para este caso, el total es el precio por una hora
            document.getElementById('total_amount').value = total.toFixed(2); // Establece el total formateado
        } else {
            // Si no se selecciona una hora, limpia los campos de hora de finalización y total
            document.getElementById('end_time').value = "";
            document.getElementById('total_amount').value = "";
        }
    });

    // Inicializa el botón de PayPal al cargar el DOM
    document.addEventListener('DOMContentLoaded', function() {
        paypal.Buttons({
            // Método que se ejecuta cuando se crea una orden de pago
            createOrder: function(data, actions) {
                var consultantId = document.getElementById('consultant_id').value;
                var reservationDate = document.getElementById('reservation_date').value;
                var startTime = document.getElementById('start_time').value;
                var totalAmount = document.getElementById('total_amount').value;

                // Validación para verificar que todos los campos obligatorios estén completos
                if (!consultantId || !reservationDate || !startTime || !totalAmount) {
                    Swal.fire({
                        icon: 'warning', // Muestra una advertencia si faltan campos
                        title: 'Campos Incompletos',
                        text: 'Por Favor, completa todos los campos obligatorios',
                    });
                    return false; // Detiene el proceso si hay campos incompletos
                }

                // Crea la orden de PayPal con el monto total
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: totalAmount // Monto total de la orden
                        }
                    }]
                });
            },
            // Método que se ejecuta cuando el pago ha sido aprobado
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Realiza una solicitud POST al servidor para completar la reserva
                    return fetch('/paypal', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Añade el token CSRF para la seguridad de Laravel
                        },
                        body: JSON.stringify({
                            orderID: data.orderID, // ID de la orden de PayPal
                            details: details, // Detalles del pago de PayPal
                            user_id: {{ auth()->user()->id }}, // ID del usuario autenticado
                            consultant_id: document.getElementById('consultant_id').value, // ID del consultor
                            reservation_date: document.getElementById('reservation_date').value, // Fecha de la reserva
                            start_time: document.getElementById('start_time').value, // Hora de inicio de la reserva
                            end_time: document.getElementById('end_time').value, // Hora de fin de la reserva
                            total_amount: document.getElementById('total_amount').value, // Monto total de la reserva
                        })
                    }).then(function(response) {
                        console.log(response);
                        if (response.ok) {
                            // Si la respuesta es exitosa, muestra un mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Pago Completado',
                                text: 'Pago Completado y reserva creada correctamente',
                            })
                            // .then(function() {
                            //     window.location.href = '/cliente/reservas'; // Redirige a la página de reservas del cliente
                            // });
                        } else {
                            // Si hay un error en el pago, muestra un mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al procesar el pago',
                            });
                        }
                    });
                });
            }
        }).render('#paypal-button-container'); // Renderiza el botón de PayPal en el contenedor
    });
</script>
    @endpush

</x-app-layout>
