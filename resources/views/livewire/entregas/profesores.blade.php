<div>
    <h1 class="text-center text-success mt-5"><strong>ENTREGAS PERSONALES</strong></h1>
    <div class="row">
        <div class="col-12 col-md-4"></div>
        <div class="col-12 col-md-4">
            <div class="row mt-5">
                <div class="col-12 col-md-1"></div>
                <div class="col-12 col-md-12">
                    <div class="form-group text-center">
                        <label class="fs-3 mt-2 text-secondary"><strong>INGRESE SU CEDULA O CODIGO</strong></label><br>
                        <input type="text" class="form-control text-center text-primary" wire:model="cedula"
                            wire:keydown.enter="buscar" id="cedula" style="font-size: 50px;font-weight: bold;">

                        <small class="text-secondary">Presione Enter</small>
                    </div>
                </div>
                <div class="col-12 col-md-1"></div>
            </div>
        </div>
        <div class="col-12 col-md-4"></div>

    </div>

</div>
@section('js')
    @if ($indicador)
        <script>
            // Swal.fire("Excelente!", 'Entrega registrada correctamente.', 'success');
            Swal.fire({
                icon: 'success',
                title: 'Excelente',
                text: 'Entrega registrada correctamente.',
                showConfirmButton: false,
                timer: 1500
            })   
        </script>
    @endif
    <script>
        document.getElementById("cedula").focus();

        Livewire.on('question', data => {
            estudiante = data.split('|');
            Swal.fire({
                title: estudiante[1],
                text: 'PRODUCTO: ALMUERZO COMPLETO',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI, continuar',
                cancelButtonText: 'NO, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('entrega');
                }
            })
        });

        Livewire.on('reserva', data => {
            estudiante = data.split('|');
            if (estudiante[2] == 0) {
                Swal.fire({
                    title: estudiante[1],
                    text: 'PRODUCTO: ' + estudiante[3],
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'SI, continuar',
                    cancelButtonText: 'NO, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('entregaReserva');
                    }
                })
            } else {
                Swal.fire("No hay productos para Entregar", "La Reserva de hoy ya fue entregada!", "warning");
            }

        });

        Livewire.on('bonos', data => {
            estudiante = data.split('|');
            Swal.fire({
                title: estudiante[1],
                text: 'PRODUCTO: ' + estudiante[2],
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI, continuar',
                cancelButtonText: 'NO, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('entregaBonos');
                }
            })
        });
    </script>
@endsection
