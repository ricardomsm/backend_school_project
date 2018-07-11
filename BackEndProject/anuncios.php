<?php
session_start();
if (!isset($_SESSION['id'])) {
		$_SESSION['pag']="perfil.php";
		header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My HTML Page</title>
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
	</nav>
	<hr>

	<h1>Anúncios activos</h1>
	<p><a href="inseriranuncio.php">Inserir anúncio</a></p>
	<table id="tabelaAnuncios">
		<thead>
			<tr>
				<th>Foto</th>
				<th>Título</th>
				<th>Marca/Modelo</th>
				<th>Mês/Ano</th>
				<th>Preço</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php
					include "bdligaStandVirtual.php";
					$comandoAutomoveis= "SELECT * FROM automoveis INNER JOIN marcas ON automoveis.marca=marcas.cod_marca INNER JOIN modelos ON automoveis.modelo=modelos.cod_modelo WHERE cod_utilizador='".$_SESSION['id']."'";
					$resultadoAutomoveis=mysqli_query($liga,$comandoAutomoveis);
					while($linha=mysqli_fetch_assoc($resultadoAutomoveis)) {
				?>
				<td><a href="mostraanuncio.php?automovel=<?= $linha['cod_automovel']?>"><img src="fotos/<?php
					$todasFotos=$linha['fotos'];
					// $primeiraFoto= substr($fotos, 0, strpos($fotos, ','));
					$fotos= explode(",",$todasFotos);
					echo $fotos[0];
				?>"></a></td>
				<td><?= $linha['titulo']?></td>
				<td><?= $linha['marca']?> <?= $linha['modelo']?></td>
				<td><?= $linha['mes']?>/<?= $linha['ano']?></td>
				<td><?= $linha['preco']?>€</td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</body>
</html>
