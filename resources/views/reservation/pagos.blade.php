<x-app-layout>
    @push('styles')
        <!--datatable css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
        <!--datatable responsive css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    @endpush

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Listado de Pagos</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pagos</a></li>
                        <li class="breadcrumb-item active">Lista de Pagos</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pagos</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('reservations.create') }}" class="btn btn-primary waves-effect waves-light">Nueva
                        Reserva</a>
                    <br>
                    <br>
                    <table id="paymentsTable"
                        class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id Reserva</th>
                                <th>Cliente</th>
                                <th>Consultor</th>
                                <th>Fecha de Reserva</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Transaction ID</th>
                                <th>Payer ID</th>
                                <th>Payer Email</th>
                                <th>Monto Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)

                                <tr>
                                    <td>{{ $payment->reservation->id }}</td>
                                    <td>{{ $payment->reservation->user->nombres }}
                                        {{ $payment->reservation->user->apellidos }}</td>
                                    <td>{{ $payment->reservation->consultant->nombres }}
                                        {{ $payment->reservation->consultant->apellidos }}</td>
                                    <td>{{ $payment->reservation->reservation_date }}</td>
                                    <td>{{ $payment->reservation->start_time }}</td>
                                    <td>{{ $payment->reservation->end_time }}</td>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->payer_id }}</td>
                                    <td>{{ $payment->payer_email }}</td>
                                    <td>{{ $payment->reservation->total_amount }}</td>
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


        <script>
            $(document).ready(function() {
                $('#paymentsTable').DataTable();
            });
        </script>
    @endpush

</x-app-layout>
