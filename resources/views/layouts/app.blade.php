<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('template_title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('img/admin/favicon_food.png') }}">
    <script language="JavaScript" type="text/javascript" src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor /responsive.bootstrap5.css') }}" rel="stylesheet" type="texto/css" />
    <!-- Fullcalendar -->
    <link type="text/css" href="{{ asset('vendor/fullcalendar/lib/main.css') }}" rel="stylesheet">
    @livewireStyles

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        @include('layouts.sidebarleft')
    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page mt-2">
        <div class="content">
            <!-- Topbar Start -->
            @include('layouts.navbar')
            <!-- end Topbar -->

            <!-- Start Content-->
            <div class="container-fluid">
                @yield('content')
                <!-- start page title -->
                <!-- end page title -->

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        {{-- @include('layouts.footer') --}}
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->



    <!-- bundle -->

    <script language="JavaScript" type="text/javascript" src="{{ asset('assets/js/app.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('assets/js/vendor/jquery.dataTables.min.js') }}">
    </script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js') }}">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
    <script language="JavaScript" type="text/javascript"
        src="{{ asset('assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript"
        src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>

    <!-- Fullcalendar -->
    <script src="{{ asset('vendor/fullcalendar/lib/main.js') }}"></script>
    @livewireScripts
    @yield('js')
    @if (session('success'))
        <script>
            // Swal.fire("Excelente!", '{{ session('success') }}','success');
            Swal.fire({
                icon: 'success',
                title: 'Excelente',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire("Error!", '{{ session('error') }}', 'error');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                destroy: true,

                keys: !0,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

            $(".dataTable5").DataTable({
                destroy: true,
                lengthMenu: [
                    [5, 10, 25, 50],
                    [5, 10, 25, 50],
                ],
                keys: !0,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

            $(".dataTableL").DataTable({
                destroy: true,
                "dom": '<lf<t>ip>',
                keys: !0,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

        });
        $('.delete').submit(function(e) {
            Swal.fire({
                title: 'Eliminar el Registro de la BD',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });

        $('.anular').submit(function(e) {
            Swal.fire({
                title: 'Anular Venta',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });



        $('.reset').submit(function(e) {
            Swal.fire({
                title: 'RESET PASSWORD',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });

        Livewire.on('success', message => {
            // Swal.fire('Excelente!',message,'success');  
            Swal.fire({
                icon: 'success',
                title: 'Excelente',
                text: message,
                showConfirmButton: false,
                timer: 1500
            })
        });
        Livewire.on('error', message => {
            Swal.fire('Error!', message, 'error');

        });
        Livewire.on('warning', message => {
            Swal.fire('Atención!', message, 'warning');
        });
        Livewire.on('datatableRender', () => {
            $(".dataTable").DataTable({
                destroy: true,
                keys: !0,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    }
                },
                
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
        });

        function preview_image(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('output_image');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        Livewire.on('loading', () => {
            $.blockUI({
                message: '<h1 class="text-success"><div class="spinner-grow text-success" role="status"></div> Espere por favor...</h1>'
            });
        });
        Livewire.on('unLoading', () => {
            $.unblockUI();
        });
    </script>
</body>

</html>
