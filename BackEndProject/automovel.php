<?php
session_start();
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
	<?php
	if (isset($_SESSION['id'])) {
			$_SESSION['pag']="perfil.php";
			echo "<a href='perfil.php'>Perfil</a>";
			echo 	"<span> | </span>";
			echo "<a href='anuncios.php'>Anúncios activos</a>";
			echo "<span> | </span>";
			echo "<a href='logout.php'>Sair</a>";

	} else {
			echo "<a href='registo.php'>Registo de Utilizador</a>";
			echo 	"<span> | </span>";
			echo  "<a href='login.php'>Login</a>";
	}
	?>
</nav>
<hr>

<?php
include "bdligaStandVirtual.php";

$automovel=$_GET["automovel"];

$comandoAutomovel= "SELECT * FROM automoveis INNER JOIN marcas ON automoveis.marca=marcas.cod_marca INNER JOIN modelos ON automoveis.modelo=modelos.cod_modelo  WHERE cod_automovel='".$automovel."'";

$resultadoAutomovel=mysqli_query($liga,$comandoAutomovel);

$linha=mysqli_fetch_assoc($resultadoAutomovel);

if (mysqli_num_rows($resultadoAutomovel)===0) {
    echo "<p>Automóvel inexistente!</p>";
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
<div id="imagens">
	<div class="bigThumbnail">
			<?php
			$todasFotos=$linha['fotos'];
			$fotos= explode(",",$todasFotos);
			for($i=0; $i<count($fotos); $i++) {
				echo "<img src='fotos/".$fotos[$i]."'>";
			}
			?>
	</div>

	<ul>
		<?php
			$todasFotos=$linha['fotos'];
			$fotos= explode(",",$todasFotos);
			for($i=0; $i<count($fotos); $i++) {
				echo '<li class="thumbnail" data-index="'.$i.'"><a><img src="fotos/'.$fotos[$i].'"></a></li>';
			}
		?>
	</ul>
</div>
<?php
	}
 ?>

<?php
	if (isset($_SESSION['id'])) {
	   $_SESSION['pag']="perfil.php";
	   $comandoUtilizador= "SELECT * FROM utilizadores WHERE cod_utilizador='".$_SESSION['id']."'";
	   $resultadoUtilizador=mysqli_query($liga,$comandoUtilizador);
	   $linhaUtilizador=mysqli_fetch_assoc($resultadoUtilizador);
		 ?>
	   <h2>Entre em contacto com o vendedor</h2>
	   <p><strong>Nome:</strong><?=$linhaUtilizador['nome']?></p>
	   <p><strong>Email:</strong><?=$linhaUtilizador['email']?></p>
	   <p><strong>Telefone:</strong> <?=$linhaUtilizador['telefone']?></p>
		 <form id='formOferta' method='post'>
	      <p><strong><label for='texto'>Texto: </label></strong> <textarea  id='descricao' name='texto' maxlength='250'></textarea></p>

	      <p><strong><label for='valorOferta'>Oferta: </label></strong> <input type='text' id='valorOferta' name='valorOferta' maxlength='20'> €</p>

	      <p><input type='submit' id='btSubmit' name='btSubmit' value='Fazer oferta'></p>
	   </form>
		 <?php

	   if (isset($_POST['btSubmit'])) {

	     $cod_utilizador=$_SESSION['id'];
	     $cod_automovel=$_GET["automovel"];
	     $texto=mysqli_real_escape_string($liga,$_POST['texto']);
	     $valorOferta=(int)$_POST['valorOferta'];
	     $comandoInserir=sprintf("INSERT INTO ofertas(cod_automovel,cod_utilizador,texto,valor_oferta,estado_oferta) VALUES(%d,%d,'%s',%d,'%s')",$cod_automovel,$cod_utilizador,$texto,$valorOferta,'P');
	     if (!mysqli_query($liga,$comandoInserir)) {
				 echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
	        exit();
	    }
	    if (mysqli_affected_rows($liga)===0) {
	        $erros.="<p>Erro a fazer a oferta.</p>";
	    }
	   }
		} else {
	   echo "<p>Para realizar uma oferta, por favor registe-se e confirme a sua conta</p>";
	   echo "<p><a href='index.php'>Voltar á página principal</a></p>";
	}
	?>
 <div id="agradecimento">
	 <p>Obrigado pela sua oferta!</p>
 </div>

<script src="js/jquery-3.3.1.min.js"></script>
<script>
$(".thumbnail").on("mouseover", function(){
		var imagem= $(".bigThumbnail").find("img");
		var index= $(this).data("index");
		imagem.removeClass("activeimage");
		imagem.eq(index).addClass("activeimage");
});
$('#formOferta').submit(function() {
    $(this).hide();
		$('#agradecimento').show();
})
</script>
</body>
</html>
