<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBO</title>
    <style>
        * {
            font-family: Consolas, monaco, monospace;
        }
    </style>
</head>

<body style="text-align: center;">
    <?php
    $data = $_GET['data'];
    $venta = explode('|', $data);
    // print_r($venta);
    // $recibos = explode('~', $datos[1]);
    // $dato = explode('|', $recibos[0]);
    ?>
    <h3>
        <?php
        if ($venta[1] != 'EXTRA') {
            echo $venta[1];
        } else {
            echo $venta[4];
        }
        ?>
    </h3>

    <?php
    echo "Nro.:" . str_pad($venta[0], 6, "0", STR_PAD_LEFT);
    ?><br>
    ------------------- <br>
    CLIENTE : <?php echo $venta[2]; ?> <br>
    USUARIO : <?php echo $venta[5]; ?> <br>
    <b><?php echo $venta[3]; ?></b><br>

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