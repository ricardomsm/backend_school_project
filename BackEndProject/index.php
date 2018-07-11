<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stand Virtual - Home</title>
  <link rel="stylesheet" href="css/style.css">

</head>
<body>
<a href="index.php"><img src="imagens/logotipo.png"></a>

<nav class="navbar">
  <a href="registo.php">Registo de Utilizador</a>
  <span>|</span>
  <?php
  if (isset($_SESSION['id'])) {
      $_SESSION['pag']="perfil.php";
      echo "<a href='perfil.php'>Perfil</a>";
  } else {
      echo  "<a href='login.php'>Login</a>";
  }
  ?>
  <!-- <a href="login.php">Login</a> -->
  <span>|</span>
  <a href="acesso_reservado.php">Acesso Reservado</a>
</nav>
<hr>

<div class="pesquisa">
  <?php
    include "bdligaStandVirtual.php";
  ?>
  <h1>Pesquisa</h1>
  <form id="formpesquisa" method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <p><label for="marca">Marca:</label>
        <select id="marca" name="marca" onchange="escolheuMarca()">
            <option value="0">Selecione...</option>
            <?php
                $comandoMarca="SELECT * FROM marcas ORDER BY CAST(marca AS CHAR)";
                $resultadoMarca=mysqli_query($liga,$comandoMarca);
                while($linha=mysqli_fetch_assoc($resultadoMarca)) {
            ?>
            <option value="<?=$linha["cod_marca"]?>"><?=$linha["marca"]?></option>
              <?php
                }
              ?>
        </select>
    </p>
    <p><label for="modelo">Modelo:</label>
        <select id="modelo" name="modelo">
          <option value="0">

        </option>
        </select>
    </p>
    <p><label for="combustivel">Combustível:</label>
        <select id="combustivel" name="combustivel">
            <option value="0">Selecione..</option>
            <option value="G">Gasolina</option>
            <option value="D">Diesel</option>
        </select>
    </p>
    <p>
      <input type="submit" id="btPesquisa" name="btPesquisa" value="Pesquisar">
    </p>
  </form>
</div>

<div class="destaque">
  <h1>Destaques</h1>
  <?php
    $comandoDestaque="SELECT * FROM automoveis WHERE destaque=1 ORDER BY cod_automovel DESC";
    $resultadoDestaque=mysqli_query($liga,$comandoDestaque);
    if (!$resultadoDestaque) {
        echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
        die();
    }
    $linhaDestaque=mysqli_fetch_assoc($resultadoDestaque);
  ?>
  <a href="automovel.php?automovel=<?= $linhaDestaque['cod_automovel']?>"><img src="fotos/<?php
      $todasFotos=$linhaDestaque['fotos'];
      $fotos= explode(",",$todasFotos);
      echo $fotos[0];
  ?>"></a>
  <p id="destaqueTitulo"><a href="paginaautomovel.php?automovel=<?= $linha['cod_automovel']?>"><?= $linhaDestaque['titulo']?></a></p>
</div>

<div class="cf resultadosPesquisa">

  <?php
    if (isset($_POST["btPesquisa"])) {
      $marca= $_POST['marca'];
      $modelo= $_POST['modelo'];
      $combustivel= $_POST['combustivel'];

      $comandoPesquisa=  "SELECT * FROM automoveis INNER JOIN marcas ON automoveis.marca=marcas.cod_marca INNER JOIN modelos ON automoveis.modelo=modelos.cod_modelo WHERE automoveis.marca=$marca AND automoveis.modelo=$modelo AND automoveis.combustivel='$combustivel'";

      $resultadoPesquisa=mysqli_query($liga,$comandoPesquisa);
    	if (!$resultadoPesquisa) {
    			echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
    			die();
    	}

    	$totalPesquisa=mysqli_num_rows($resultadoPesquisa);
      $linha=mysqli_fetch_assoc($resultadoPesquisa);
    	if ($totalPesquisa===0) {
    			echo "<p>Não existe nenhum carro para os  valores especificados!</p>";
    	} else {
          $combustivelExtenso= ($linha['combustivel']==="D") ? 'Diesel': 'Gasolina' ;
    			echo "<p><strong>Pesquisa por:</strong> ".$linha['marca']." ".$linha['modelo']. " "."a ".$combustivelExtenso."</p>";
    			echo "<p><strong>Total de resultados:</strong> ".$totalPesquisa."</p>";
  ?>

    			<table id="tabelaPesquisa">
    				<thead>
    					<tr>
    						<th>Foto</th>
    						<th>Título do anúncio</th>
    						<th>Mês/Ano</th>
    						<th>Preço</th>
    					</tr>
            </thead>
            <tbody>
    					<tr>
      					<?php
                mysqli_data_seek($resultadoPesquisa, 0);
      					while($linha=mysqli_fetch_assoc($resultadoPesquisa)) {
      					?>
        				<td><a href="automovel.php?automovel=<?= $linha['cod_automovel']?>"><img src="fotos/<?php
        					$todasFotos=$linha['fotos'];
        					$fotos= explode(",",$todasFotos);
        					echo $fotos[0];
        				?>"></a></td>
        				<td><?= $linha['titulo']?></td>
        				<td><?= $linha['mes']?>/<?= $linha['ano']?></td>
        				<td><?= $linha['preco']?>€</td>
    			     </tr>
        			 <?php
        			  }
        			 ?>
    				</tbody>
    			</table>

    	<?php
    		}
       mysqli_free_result($resultadoPesquisa);
      }
    mysqli_close($liga);
    ?>

</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
  function escolheuMarca(marca) {
    var marcaEscolhida= $("#marca").val();
    $.ajax({
      type: "post",
      url: "pesquisa.php",
      data: {marcaEscolhida: marcaEscolhida},
      dataType: "html",
      success: function(modelos) {
          $("#modelo").html(modelos);
      } ,
      error: function(){
          $("#modelo").html("Ocorreu um erro");
      }
    });
}
</script>
</body>
</html>
