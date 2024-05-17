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

    $datos = explode('~', $data);
    $datosventa = explode('|', $datos[0]);
    $detalles = explode('^', $datos[1]);
    // var_export($detalles);
    foreach ($detalles as $item) {
        $recibo = explode('|', $item);
        // var_export($recibo);
    }
    ?>

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
                    <td style="width: 50%;"><?php echo str_pad($datosventa[0], 6, '0', STR_PAD_LEFT); ?></td>
                </tr style="border: hidden;">
                <tr>
                    <td align="right"><strong>FECHA:</strong></td>
                    <td><?php echo $datosventa[1]; ?></td>
                </tr>
            </table>
        </div>
        <hr>
        <?php
        $total = 0;
        foreach ($detalles as $detalle) {
            $recibo = explode('|', $detalle);
            echo '<div style="align-content: center; margin-bottom: 10px;">';
            echo '<table style="border: hidden; width: 100%;"><tr style="border: hidden;"><td align="right" style="width: 50%;"><strong>COD. ESTUDIANTE:</strong></td>';
            echo '<td style="width: 50%;">' . $recibo[1] . '</td></tr style="border: hidden;"><tr><td align="right" style="width: 50%;vertical-align:top;"><strong>ESTUDIANTE:</strong></td>';
            echo '<td style="width: 50%;">' . $recibo[2] . '</td></tr><tr style="border: hidden;"><td align="right"><strong>CURSO:</strong></td>';
            echo '<td>' . $recibo[3] . '</td></tr></table><div> ';
            $i = 1;
            echo '<table style="font-size: 8px; vertical-align: middle;" border="1"><thead><tr><th style="width: 10%;">NRO</th><th style="width: 40%;">DETALLE</th><th>CANT</th><th>P.UNIT</th><th>TP</th><th>DESC</th><th style="width: 20%;">SUBTOTAL</th></tr></thead><tbody>';

            echo "<tr>
        <td align='center'> " . str_pad($i, 3, '0', STR_PAD_LEFT) . "</td>                            
        <td>$recibo[6]</td>
        <td align='center'> $recibo[7]</td>
        <td align='center'> $recibo[8]</td>
        <td align='center'>$datosventa[3]</td>
        <td align='center'> $recibo[9]</td>
        <td align='right'>" . number_format($recibo[10], 2, '.', ',') . "</td>
        </tr>";
            $total = $total + $recibo[10];
            $i++;
            echo '<tr></tr></tbody></table><table><tr style="border: hidden;"><td align="right">Bono hasta el día: ' . $recibo[5] . '</td><td align="right" style="width: 20%;"></td></tr></table>';
            echo '<br><hr><br>';
        }
        echo '<table style="border: hidden; width: 100%;">
            <tr style="border: hidden;">
                <td align="right"><strong>TOTAL A PAGAR: Bs.</strong></td>';

        if ($datosventa[3] == 'GA') {
            $total = 0;
        }

        echo '<td align="right" style="width: 20%;"><strong>' . number_format($total, 2, '.', ',') . '</strong></td></tr>
        </table>';
        ?>
        <br><br>
        <table>
            <tr>
                <td style="width: 30%;">
                    <img src="../img/qr.png" style="width: 100%;">
                </td>
                <td>
                    <strong>GRACIAS POR SU COMPRA!</strong><br>
                    <p style="font-size:9px;">Ud. fue atendido por: <?php echo $datosventa[2]; ?> <br>
                        Sugerencias y reclamos: 62236698</p>

                </td>
            </tr>
        </table>
    </div>

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