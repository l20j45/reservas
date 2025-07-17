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
                <h4 class="mb-sm-0">Calendario de Reservas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Administrador</a></li>
                        <li class="breadcrumb-item active">Calendario</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Ver Reservas</h4>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
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
            document.addEventListener('DOMContentLoaded', function() {
                // Obtiene el elemento del DOM donde se mostrará el calendario
                var calendarE1 = document.getElementById('calendar');

                // Inicializa el calendario con FullCalendar
                var calendar = new FullCalendar.Calendar(calendarE1, {
                    height: 800,
                    initialView: 'dayGridMonth', // Vista inicial del calendario (vista por mes)
                    locale: 'es', // Establece el idioma en español
                    headerToolbar: { // Configura las opciones de navegación en el calendario
                        left: 'prev,next today', // Botones prev, next y today a la izquierda
                        center: 'title', // Título del calendario (mes o día actual) en el centro
                        right: 'dayGridMonth,timeGridWeek,timeGridDay', // Vistas disponibles: mes, semana, día a la derecha
                    },
                    buttonText: { // Traduce los textos de los botones
                        today: 'Hoy', // Texto para el botón "Hoy"
                        month: 'Mes', // Texto para la vista mensual
                        week: 'Semana', // Texto para la vista semanal
                        day: 'Día', // Texto para la vista diaria
                    },
                    // Carga los eventos desde la ruta especificada (datos dinámicos desde el backend)
                    events: '{{ route('administrador.fullcalendar') }}',
                    // Función que se ejecuta después de que un evento ha sido montado en el calendario
                    eventDidMount: function(info) {
                        // Cambia el color de fondo si el evento tiene un color definido
                        if (info.event.backgroundColor) {
                            info.el.style.backgroundColor = info.event.backgroundColor;
                        }

                        // Cambia el color del borde si el evento tiene un color de borde definido
                        if (info.event.borderColor) {
                            info.el.style.borderColor = info.event.borderColor;
                        }
                    }
                });

                // Renderiza el calendario en la página
                calendar.render();
            });
        </script>
    @endpush

</x-app-layout>
