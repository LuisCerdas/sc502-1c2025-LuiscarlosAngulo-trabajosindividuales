<?php

$transacciones = array(
    array("id" => 01, "descripcion" => "Compra en Am Pm", "monto" => 5000),
    array("id" => 02, "descripcion" => "Compra en restaurante", "monto" => 20000)
);

$tasa_interes = 0.026;
$tasaCashback = 0.001;

//registro de transaccion
function registrarTransaccion($id, $descripcion, $monto)
{
    global $transacciones;

    // Agregar transaccion 
    array_push($transacciones, [
        "id" => $id,
        "descripcion" => $descripcion,
        "monto" => $monto
    ]);
    echo "Transaccion exitosa. \n";
}


// nueva transaccion
registrarTransaccion("03", "Compra en Am Pm", 15000);
registrarTransaccion("04","compra en vindi",25000);

echo '<br>';

//Funcion de estado de cuenta
function EstadoCuenta()
{
    global $transacciones, $tasa_interes, $tasaCashback;

    $montoTotal = 0;
    $contenidoArchivo = "Estado de Cuenta:\n\n";
    foreach ($transacciones as $transaccion) {
        $montoTotal += $transaccion["monto"];
    }
    $montoTotal_ConIntereses = $montoTotal + ($montoTotal * $tasa_interes);

    echo "Estado de cuenta: <br><br>";
    foreach ($transacciones as $transaccion) {
        $monto_contado = $transaccion['monto']; 
        $monto_con_intereses = $monto_contado * (1 + $tasa_interes); 
        $cashback = $monto_contado * $tasaCashback; 
        $monto_final = $monto_con_intereses - $cashback; 


       
        //crear y escribir en un archivo
        $archivo = fopen("ejemplo.txt", "w") or die("no se puede abrir el archivo");
        $txt = "Hola Mundo! \n";
        fwrite($archivo, $txt);
        $txt = "PHP Es genial!";
        fwrite($archivo, $txt);
        fclose($archivo);


      

        // Mostrar detalles de la transacción
        echo "ID: " . $transaccion['id'] . "<br>";
        echo "Descripción: " . $transaccion['descripcion'] . "<br>";
        echo "Monto de contado: $" . number_format($monto_contado, 2) . "<br>";
        echo "Monto con intereses (2.6%): $" . number_format($monto_con_intereses, 2) . "<br>";
        echo "Cashback (0.1%): $" . number_format($cashback, 2) . "<br>";
        echo "Monto final a pagar: $" . number_format($monto_final, 2) . "<br><br>";

      
     // Agregar la transacción al contenido del archivo
     $contenidoArchivo .= "ID: " . $transaccion['id'] . "\n";
     $contenidoArchivo .= "Descripción: " . $transaccion['descripcion'] . "\n";
     $contenidoArchivo .= "Monto de contado: $" . number_format($monto_contado, 2) . "\n";
     $contenidoArchivo .= "Monto con intereses (2.6%): $" . number_format($monto_con_intereses, 2) . "\n";
     $contenidoArchivo .= "Cashback (0.1%): $" . number_format($cashback, 2) . "\n";
     $contenidoArchivo .= "Monto final a pagar: $" . number_format($monto_final, 2) . "\n\n";
 }
    // Mostrar el monto total con intereses y el monto total final con cashback
    echo "Monto Total (Contado): $" . number_format($montoTotal, 2) . "<br>";
    echo "Monto Total con intereses (2.6%): $" . number_format($montoTotal_ConIntereses, 2) . "<br>";


     // Agregar los totales al contenido del archivo
     $contenidoArchivo .= "Monto Total (Contado): $" . number_format($montoTotal, 2) . "\n";
     $contenidoArchivo .= "Monto Total con intereses (2.6%): $" . number_format($montoTotal_ConIntereses, 2) . "\n";
 

     // Crear y escribir en el archivo de texto
    $archivo = fopen("estado_cuenta.txt", "w") or die("No se puede abrir el archivo");
    fwrite($archivo, $contenidoArchivo);
    fclose($archivo);


}

EstadoCuenta();


?>