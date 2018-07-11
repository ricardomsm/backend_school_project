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

<div id="detalhesAnuncio">
	<?php
	include "bdligaStandVirtual.php";

	$automovel=$_GET["automovel"];

	$comandoAutomovel= "SELECT * FROM automoveis INNER JOIN marcas ON automoveis.marca=marcas.cod_marca INNER JOIN modelos ON automoveis.modelo=modelos.cod_modelo  WHERE cod_automovel='".$automovel."'";

	$resultadoAutomovel=mysqli_query($liga,$comandoAutomovel);

	$linha=mysqli_fetch_assoc($resultadoAutomovel);

	if (mysqli_num_rows($resultadoAutomovel)===0) {
	    echo "<p>Automível inexistente!</p>";
	} else {

	?>

	<h1><?=$linha['titulo']?></h1>
	<p><strong>Marca: </strong><span><?=$linha['marca']?></span></p>
	<p><strong>Modelo: </strong><span><?=$linha['modelo']?></span></p>
	<p><strong>Registo: </strong><span><?=$linha['mes']?> de <?=$linha['ano']?></span></p>
	<p><strong>Cilindrada: </strong><span><?=$linha['cilindrada']?>cc (<?=$linha['potencia']?>cv)</span></p>
	<p><strong>Combustível: </strong><span>
		<?php
			if ($linha['combustivel']==="D") {
				echo "Diesel";
			} else if ($linha['combustivel']==="G") {
				echo "Gasolina";
			}
		?></span></p>
	<p><strong>Nº kms: </strong><span><?=$linha['kms']?></span></p>
	<p><strong>Cor: </strong><span><?=$linha['cor']?></span></p>
	<p><strong>Número de portas: </strong><span><?=$linha['nr_portas']?></span></p>
	<p><strong>Descrição: </strong><span><?=$linha['descricao']?></span></p>
	<p id="caracteristicasTitulo"><strong>Características: </strong><ul id="caracteristicas"><?php
		$caracteristicas=explode(",",$linha['caracteristicas']);
		foreach($caracteristicas as $caracteristica) {
			switch ($caracteristica) {
				case "JLL":
					echo "<li>Jantes de liga leve</li>";
					break;
				case "DA":
					echo "<li>Direcção assistida</li>";
					break;
				case "FC":
					echo "<li>Fecho central</li>";
					break;
				case "ESP":
					echo "<li>ESP</li>";
					break;
				case "AR":
					echo "<li>Ar condicionado</li>";
					break;
				case "VE":
					echo "<li>Vidros eléctricos</li>";
					break;
				case "CB":
					echo "<li>Computador de bordo</li>";
					break;
				case "FN";
					echo "<li>Faróis de nevoeiro</li>";
					break;
				case "LR":
					echo "<li>Livro de revisões</li>";
			}
		}
	?></ul></p>
	<p id="precoTitulo"><strong>Preço: </strong><span><?=$linha['preco']?>€</span></p>
	<p id="fotosTitulo"><strong>Fotos: </strong>
		<?php
			$todasFotos=$linha['fotos'];
			$fotos= explode(",",$todasFotos);
			foreach ($fotos as $foto) {
				echo '<span><img src="fotos/'.$foto.'"></span>';
			}
		?>
	</p>
</div>

<div id="ofertas">
	<?php
		$comandoOfertas= "SELECT * FROM ofertas INNER JOIN utilizadores ON ofertas.cod_utilizador=utilizadores.cod_utilizador WHERE cod_automovel=$automovel";

		$resultadoOfertas=mysqli_query($liga,$comandoOfertas);
		if (!$resultadoOfertas) {
				echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
				die();
		}

		$totalOfertas=mysqli_num_rows($resultadoOfertas);
    if ($totalOfertas===0) {
        echo "<p>Ainda não tem nenhuma oferta por este automóvel</p>";
    } else {
        echo "<p>Total de ofertas: ".$totalOfertas."</p>";
	?>

	<table id="tabelaOfertas">
		<thead>
			<th>Utilizador</th>
			<th>Texto</th>
			<th>Valor</th>
			<th>Estado da oferta</th>
		</thead>
		<tbody>
			<?php
				while($linhaOfertas=mysqli_fetch_assoc($resultadoOfertas)){
					$ofertaExtenso= ($linhaOfertas['estado_oferta']==="P") ? 'Pendente' : 'Aceite';
			?>
					<tr>
						<td><?= $linhaOfertas['nome']?></td>
						<td><?= $linhaOfertas['texto']?></td>
						<td><?= $linhaOfertas['valor_oferta']?>€</td>
						<td><?= $ofertaExtenso?></td>
					</tr>
			<?php
				}
			}
		}
			?>

		</tbody>


	</table>

</div>

<p class="fimPaginaAnuncio"><a href="index.php">Página principal</a></p>

</body>
</html>
