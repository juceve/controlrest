<div>
    
    <div class="content p-2">
        <div class="container py-3" style="background-color: #ffffffc9">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-12 col-md-7">
                    @if(!empty($successMessage))
                    <div class="alert alert-success">
                        {{ $successMessage }}
                    </div>
                    @endif

                    <div class="text-center">
                        <!-- progressbar -->
                        <ul class="progressbar">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li class="active"></li>
                        </ul>
                    </div>
                    <div class="row setup-content" id="step-4">
                        <div class="col-xs-12">
                            <div class="content text-center">
                                <span class="text-success" style="font-size: 10rem;"><i class="bi bi-check2-circle"></i></span>
                                <h1>PROCESO FINALIZADO!</h1>
                                <p>Se generó el Código de Venta Nro.: {{str_pad($id, 5, "0", STR_PAD_LEFT)}}</p>
                                <a href="">Haga click para descargar su comprobante</a>
                            </div>
                            <div class="text-center" style="margin-top: 50px">
                                <a href="/"><i class="bi bi-arrow-return-left"></i> Volver al Inicio</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
