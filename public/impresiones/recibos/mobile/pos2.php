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
    // var_dump($data);
    $recibos = explode('~', $data);

    foreach ($recibos as $recibo) {
        $venta = explode('|', $recibo);

        echo "<br>
                <h3>";
        if ($venta[1] != 'EXTRA') {
            echo $venta[1];
        } else {
            echo $venta[4];
        }
        echo "</h3>";
        echo "Nro.:" . str_pad($venta[0], 6, "0", STR_PAD_LEFT);
        echo "<br> ";
        echo "CLIENTE : $venta[2] <br>USUARIO : $venta[5] <br><b>$venta[3]</b><br><br><br><br> -------------------<br>";
    }

    ?>




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