<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBO</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    $data = $_GET['data'];
    $datos = explode('||', $data);
    $venta = explode('|', $datos[0]);
    $recibos = explode('~', $datos[1]);
    $dato = explode('|', $recibos[0]);
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <link rel="stylesheet" href="style.css">
        <script src="script.js"></script>
    </head>

    <body>
        <div class="ticket">
            <div style="text-align: center;">
                <img src="../img/logoAR.png" alt="Logotipo" style="width: 40%;">
            </div>

            <h1 class="centrado" style="font-size: 18px;">RECIBO</h1>
            <hr>
            <div style="align-content: center; margin-bottom: 10px;">
                <table style="border: hidden; width: 100%;">
                    <tr style="border: hidden;">
                        <td align="right" style="width: 50%;"><strong>NRO VENTA:</strong></td>
                        <td style="width: 50%;"><?php echo str_pad($venta[0], 6, '0', STR_PAD_LEFT); ?></td>
                    </tr style="border: hidden;">
                    <tr>
                        <td align="right"><strong>FECHA:</strong></td>
                        <td><?php echo $venta[1]; ?></td>
                    </tr>
                </table>
            </div>
            <hr>
            <div style="align-content: center; margin-bottom: 10px;">
                <table style="border: hidden; width: 100%;">
                    <tr style="border: hidden;">
                        <td align="right" style="width: 50%;"><strong>COD. ESTUDIANTE:</strong></td>
                        <td style="width: 50%;"><?php echo $dato[0]; ?></td>
                    </tr style="border: hidden;">
                    <tr>
                        <td align="right" style="width: 50%;vertical-align:top;"><strong>ESTUDIANTE:</strong></td>
                        <td style="width: 50%;"><?php echo $dato[1]; ?></td>
                    </tr>
                    <tr style="border: hidden;">
                        <td align="right"><strong>CURSO:</strong></td>
                        <td><?php echo $dato[2]; ?></td>
                    </tr>
                </table>
            </div>
            <?php
            $i = 1;
            ?>
            <table style="font-size: 8px; vertical-align: middle;" border="1">
                <thead>
                    <tr>
                        <th style='width: 10%;'>NRO</th>
                        <th style='width: 40%;'>DETALLE</th>
                        <th>CANT</th>
                        <th>P.UNIT</th>
                        <th>TP</th>
                        <th>DESC</th>
                        <th style='width: 20%;'>SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($recibos as $recibo) {
                        $dato = explode('|', $recibo);
                        echo "<tr>
                            <td align='center'> " . str_pad($i, 3, '0', STR_PAD_LEFT) . "</td>                            
                            <td>$dato[3]</td>
                            <td align='center'> $dato[4]</td>
                            <td align='center'> $dato[5]</td>
                            <td align='center'>$venta[3]</td>
                            <td align='center'> $dato[7]</td>
                            <td align='right'>" . number_format($dato[6], 2, '.', ',') . "</td>
                            </tr>";
                        $total = $total + $dato[6];
                        $i++;
                    }



                    ?>

                    <tr>

                    </tr>
                </tbody>
            </table><br>
            <table style="border: hidden; width: 100%;">
                <tr style="border: hidden;">
                    <td align='right'><strong>Total a Pagar: Bs.</strong></td>
                    <?php 
                    if($venta[3] == 'GA'){
                        $total = 0;
                    }
                    ?>
                    <td align="right" style='width: 20%;'><strong><?php echo number_format($total, 2, '.', ','); ?></strong></td>
                </tr>
                <!-- <tr style="border: hidden;">
                    <td align='right'>Bono hasta el día: <?php //echo $recibo[5]; 
                                                            ?></td>
                    <td align="right" style='width: 20%;'></td>
                </tr> -->
            </table>
            <br>
            <table>
                <tr>
                    <td style="width: 30%;">
                        <img src="../img/qr.png" style="width: 100%;">
                    </td>
                    <td>
                        <strong>GRACIAS POR SU COMPRA!</strong><br>
                        <p style="font-size:9px;">Ud. fue atendido por: <?php echo $venta[2]; 
                                                                        ?> <br>
                            Sugerencias y reclamos: 62236698</p>

                    </td>
                </tr>
            </table>
        </div>
    </body>

    </html>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            window.print();
            setTimeout(function() {
                window.close();
            }, 3000);
        });
    </script>
</body>

</html>