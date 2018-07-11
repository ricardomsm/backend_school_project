<?php
$liga = mysqli_connect("localhost","root","","standvirtual");
if (!$liga) {
        echo "Erro na ligação: " . mysqli_connect_errno() . " - " . mysqli_connect_error();
        exit();
}
mysqli_set_charset($liga, "utf8");
?>
