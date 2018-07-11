<?php
session_start();
if (!isset($_SESSION['id'])) {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}
require "bdligaStandVirtual.php";
$erros="";

$comando="SELECT * FROM utilizadores WHERE cod_utilizador=".$_SESSION['id'];
$resultado=mysqli_query($liga,$comando);
if (mysqli_num_rows($resultado)===0) {
    header("Location: index.php");
    exit();
}
$linha=mysqli_fetch_assoc($resultado);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Perfil de utilizador</title>
		<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<a href="index.php"><img src="imagens/logotipo.png"></a>

	<nav class="navbar">
	  <a href="perfil.php">Perfil</a>
	  <span>|</span>
	  <a href="anuncios.php">Anúncios</a>
	  <span>|</span>
	  <a href="logout.php">Sair</a>
		<span>|</span>
		<a href="acesso_reservado.php">Acesso Reservado</a>
	</nav>
	<hr>

<h1>Perfil de utilizador</h1>
<table>
	<tr>
		<th>Nome:</th>
		<td><?= $linha['nome']?></td>
	</tr>
	<tr>
		<th>Email:</th>
		<td><?= $linha['email']?></td>
	</tr>
	<tr>
		<th>Morada:</th>
		<td><?= $linha['morada']?></td>
	</tr>
	<tr>
		<th>CP:</th>
		<td><?= $linha['cp_numerico']?> <?= $linha['cp_localidade']?></td>
	</tr>
	<tr>
		<th>Telefone:</th>
		<td><?= $linha['telefone']?></td>
	</tr>
	<tr>
		<th>Data de registo:</th>
		<td><?= $linha['data_registo']?></td>
	</tr>
</table>
<?php
mysqli_free_result($resultado);
mysqli_close($liga);
?>
<p><a href="alterarperfil.php">Alterar perfil</a></p>
<p><a href="anuncios.php">Anúncios</a></p>
<p><a href="index.php">Página principal</a></p>

</body>
</html>
