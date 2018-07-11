<?php
session_start();
require "bdligaStandVirtual.php";

$comandoAdmin= "SELECT * FROM utilizadores WHERE cod_utilizador='".$_SESSION['id']."'AND estado='X'";
$resultadoAdmin=mysqli_query($liga,$comandoAdmin);
$linhaAdmin=mysqli_fetch_assoc($resultadoAdmin);

if ($_SESSION['id']!==$linhaAdmin['cod_utilizador']) {
    echo "<p>Não tem permissões para esta página</p>";
    echo "<p><a href='index.php'>Voltar á página principal</a></p>";
    exit();
}

if (!isset($_SESSION['id'])) {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Backoffice</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="index.php"><img src="imagens/logotipo.png"></a>
<hr>



</body>
</html>