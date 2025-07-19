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
                <h4 class="mb-sm-0">Listado de Reservas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reservas</a></li>
                        <li class="breadcrumb-item active">Lista de Reservas</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reservas</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('cliente.reserva') }}" class="btn btn-primary waves-effect waves-light">Nueva
                        Reserva</a>
                    <br>
                    <br>
                    <table id="reservationsTable"
                        class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Consultor</th>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Estado del Pago</th>
                                <th>Estado del Reserva</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $reservation->consultant->nombres }}
                                        {{ $reservation->consultant->apellidos }}</td>
                                    <td>{{ $reservation->reservation_date }}</td>
                                    <td>{{ $reservation->start_time }}</td>
                                    <td>{{ $reservation->end_time }}</td>
                                    <td>{{ $reservation->payment_status }}</td>
                                    <td>
                                        @if ($reservation->reservation_status == 'cancelada')
                                            <span class="badge bg-danger">cancelada</span>
                                        @elseif ($reservation->reservation_status == 'confirmada')
                                            <span class="badge bg-success">confirmada</span>
                                        @else
                                            <span class="badge bg-warning">pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reservation->reservation_status == 'cancelada')
                                            <button class="btn btn-danger btn-sm" disabled>Cancelar</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm btn-cancel"
                                                data-id="{{ $reservation->id }}">Cancelar</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        <script>
            $(document).ready(function() {
                $('#reservationsTable').DataTable();
            });

            // Evento que se ejecuta cuando se hace clic en el botón de cancelar reserva
            $('.btn-cancel').on('click', function(e) {
                e.preventDefault(); // Prevenir la acción predeterminada del enlace

                var reservationId = $(this).data('id'); // Obtener el ID de la reserva desde el atributo 'data-id'

                // Mostrar el modal de SweetAlert para ingresar la razón de la cancelación
                Swal.fire({
                    title: "Cancelar Reserva", // Título del modal
                    icon: "warning", // Ícono de advertencia
                    text: "Por favor, ingresa la razón de la cancelación:", // Mensaje del modal
                    input: 'textarea', // Input de tipo textarea para ingresar la razón
                    inputPlaceholder: 'Escribe la razón de la cancelación aquí...', // Placeholder para el textarea
                    showCancelButton: true, // Mostrar botón de cancelar
                    confirmButtonText: "Cancelar Reserva", // Texto del botón de confirmación
                    cancelButtonText: 'Cerrar', // Texto del botón de cerrar
                    preConfirm: (
                    cancellationReason) => { // Función que se ejecuta antes de confirmar la cancelación
                        if (!cancellationReason) { // Validar si no se ingresó una razón
                            swal.showValidationMessage(
                            'Es necesario ingresar una razón'); // Mostrar mensaje de validación
                            return false;
                        } else {
                            // Si se ingresó una razón, proceder con la solicitud AJAX para cancelar la reserva
                            return new Promise((resolve) => {
                                $.ajax({
                                    url: "{{ route('reservations.cancel') }}", // URL de la ruta para cancelar la reserva
                                    method: "POST", // Método POST para enviar los datos
                                    data: {
                                        _token: "{{ csrf_token() }}", // Token CSRF para la seguridad de Laravel
                                        reservation_id: reservationId, // ID de la reserva a cancelar
                                        cancellation_reason: cancellationReason, // Razón de la cancelación ingresada
                                    },
                                    success: function(response) {
                                        // Si la solicitud es exitosa
                                        if (response.success) {
                                            Swal.fire({
                                                title: "Reserva cancelada", // Título del mensaje de éxito
                                                text: response
                                                .message, // Mensaje de éxito
                                                icon: "success" // Ícono de éxito
                                            }).then(() => {
                                                location
                                            .reload(); // Recargar la página para reflejar los cambios
                                            });
                                        } else {
                                            // Si hubo un error al cancelar la reserva
                                            Swal.fire({
                                                title: "Error", // Título del mensaje de error
                                                text: response
                                                .message, // Mensaje de error
                                                icon: "error" // Ícono de error
                                            });
                                        }
                                    }
                                });
                            });
                        }
                    }
                });
            });
        </script>

        @if (session('success'))
            <script>
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    },
                }).showToast();
            </script>
        @endif
    @endpush

</x-app-layout>
