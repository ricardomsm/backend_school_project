<?php
session_start();
if (!isset($_SESSION['id'])) {
		$_SESSION['pag']="perfil.php";
		header("Location: login.php");
}

$erros="";
if (isset($_POST['btSubmit'])) {
    // echo "<pre>".print_r($_POST,TRUE)."</pre>";
    require "bdligaStandVirtual.php";
		$cod_utilizador= $_SESSION['id'];
    $titulo=mysqli_real_escape_string($liga,$_POST['titulo']);
    $marca=mysqli_real_escape_string($liga,$_POST['marca']);
    $modelo=mysqli_real_escape_string($liga,$_POST['modelo']);
    $mes=mysqli_real_escape_string($liga,$_POST['mes']);
		$ano=(int)$_POST['ano'];
		$cilindrada=mysqli_real_escape_string($liga,$_POST['cilindrada']);
    $potencia=mysqli_real_escape_string($liga,$_POST['potencia']);
    $combustivel=mysqli_real_escape_string($liga,$_POST['combustivel']);
    $kms=(int)$_POST['kms'];
    $cor=mysqli_real_escape_string($liga,$_POST['cor']);
    $nr_portas=(int)$_POST['nrportas'];
		$descricao=mysqli_real_escape_string($liga,$_POST['descricao']);
 		$caracteristicas=mysqli_real_escape_string($liga,implode(",",$_POST['caracteristicas']));
    $preco= (int)$_POST['preco'];
		$destaque= (int)$_POST['destaque'];
		$foto1=$_FILES['foto1'];
		$foto2=$_FILES['foto2'];
		$foto3=$_FILES['foto3'];
		$foto4=$_FILES['foto4'];
		$foto5=$_FILES['foto5'];


    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave]) && $_POST[$chave]==(NULL)) {
            $erros.="<p>O campo ".$chave." é de preenchimento obrigatório.</p>";
            break;
        }
    }
    /* Upload de ficheiros - fotos */
		function verificaFotos($foto){
			$erros="";
			if ($foto['error']!==0) {
	        switch($_FILES['foto']['error']) {
	            case '1':
	                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
	                break;
	            case '2':
	                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
	                break;
	            case '3':
	                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
	                break;
	//            case '4':
	//                $erros.='<p>Nenhum ficheiro foi transferido.</p>';
	//                break;
	            case '6':
	                $erros.='<p>Não existe pasta temporária.</p>';
	                break;
	            case '7':
	                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
	                break;
	            case '8':
	                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
	                break;
	            /*case '999':*/
	            default:
	                $erros.='<p>'.$foto['error'].' - Código de erro não disponível.</p>';
	        }
	    } else {
	        if (empty($foto['tmp_name']) || $foto['tmp_name'] == 'none') {
	            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
	        } else {
	            switch ($foto['type']) {
	              case 'image/jpeg':
	              case 'image/jpg':
	              case 'image/gif':
	              case 'image/png':
	                 $extvalida=true;
	                 break;
	              default:
	                 $extvalida=false;
	            }
	            if (!$extvalida) {
	                $erros.="<p>O formato do ficheiro não é permitido.</p>";
	            } else {
	                $aleatorio=mt_rand();
	                $nome_temp=date('YmdHis').$aleatorio;
	                $extensao=strtolower(strrchr($foto['name'],'.'));
	                $nome_ficheiro= $nome_temp.$extensao;
	                if (!move_uploaded_file($foto['tmp_name'], "fotos/" . $nome_ficheiro)) {
	                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
	                }
									return $nome_ficheiro;
	            }
	        }
	    }
		}

		$fotos= verificaFotos($foto1).",".verificaFotos($foto2).",".verificaFotos($foto3).",".verificaFotos($foto4).",".verificaFotos($foto5);

    if ($erros==="") {
			function campoNumerico($campo) {
				if (!preg_match("/^[0-9]$/",$campo)) {
					$erros="";
					$erros.="<p>O campo ".$campo." só pode conter números.</p>";
				}
			}

      if (!preg_match("/^[a-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑA-Z 0-9´`ç^~.]{6,}$/",$titulo)) {
          $erros.="<p>O campo 'Título' tem de ter no mínimo 6 caracteres.</p>";
      }
      if (!preg_match("/^[a-zA-Z]{1,}$/",$mes)) {
          $erros.="<p>O campo ".$mes." tem o formato mês/ano.</p>";
      }
			if (!preg_match("/^[0-9]{4}$/",$ano)) {
					$erros.="<p>O campo 'Data de registo' tem o formato mês/ano.</p>";
			}
      campoNumerico($cilindrada);
			campoNumerico($potencia);
			if ($combustivel===0) {
					$erros.="<p>Seleccione um combustível.</p>";
			}
			campoNumerico($kms);
			if (!preg_match("/^[a-zA-Z ]{1,}$/",$cor)) {
					$erros.="<p>O campo ".$cor." só pode conter letras e espaços.</p>";
			}
			campoNumerico($nr_portas);
			campoNumerico($preco);

      if ($erros==="") {

				$comandoInserir=sprintf("INSERT INTO automoveis(cod_utilizador,titulo,marca,modelo,mes,ano,cilindrada,potencia,combustivel,kms,cor,nr_portas,descricao,caracteristicas,preco,destaque,fotos) VALUES(%d,'%s','%s','%s','%s',%d,%d,%d,'%s',%d,'%s',%d,'%s','%s',%d,%d,'%s')",$cod_utilizador,$titulo,$marca,$modelo,$mes,$ano,$cilindrada,$potencia,$combustivel,$kms,$cor,$nr_portas,$descricao,$caracteristicas,$preco,$destaque,$fotos);
				if (!mysqli_query($liga,$comandoInserir)) {
						echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
            exit();
        }
        if (mysqli_affected_rows($liga)===0) {
        	$erros.="<p>Erro na inserção do utilizador.</p>";
        } else {
            header("Location: anuncioinserido.php");
        	}

      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My HTML Page</title>
  <link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-3.3.1.min.js"></script>
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

	<h1>Colocar anúncio</h1>

	<?php
	    if ($erros!=="") {
	        echo $erros;
	    }
	?>
	<form id="formInserirAnuncio" method="post" enctype="multipart/form-data">
		<?php
			include "bdligaStandVirtual.php";
		?>
	    <p><label for="titulo">Título:</label> <input type="text" id="titulo" name="titulo" maxlength="100" value="<?=($erros!=="") ? $titulo : ''?>"></p>

        <p><label for="marca">Marca:</label>
            <select id="marca" name="marca" onchange="escolheuMarca();">
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
                <script>
                  function escolheuMarca(marca) {
                    var marcaEscolhida= $("#marca").val();
                   // alert(marcaEscolhida);
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
            </option>
                </select>
        </p>

			<p><label>Data de registo:</label> <input type="text" id="mes" name="mes" maxlength="50" value="<?=($erros!=="") ? $mes : '' ?>"> / <input type="text" id="ano" name="ano" maxlength="4" value="<?=($erros!=="") ? $ano : '' ?>"></p>

			<p><label for="cilindrada">Cilindrada: </label> <input type="text" id="cilindrada" name="cilindrada" maxlength="20"  value="<?=($erros!=="") ? $cilindrada : ''?>"></p>

			<p><label for="potencia">Potência: </label> <input type="text" id="potencia" name="potencia" maxlength="50" value="<?=($erros!=="") ? $potencia : ''?>"></p>

			<p><label for="combustivel">Combustível:</label>
	        <select id="combustivel" name="combustivel">
	            <option value="0">Selecione..</option>
							<option value="G">Gasolina</option>
							<option value="D">Diesel</option>
	        </select>
	    </p>

			<p><label for="kms">Kms: </label> <input type="text" id="kms" name="kms" maxlength="50" value="<?=($erros!=="") ? $kms : ''?>"></p>

			<p><label for="cor">Cor: </label> <input type="text" id="cor" name="cor" maxlength="50" value="<?=($erros!=="") ? $cor : ''?>"></p>

			<p><label for="nrportas">Nº de portas: </label> <input type="text" id="nrportas" name="nrportas" maxlength="1" value="<?=($erros!=="") ? $nr_portas : ''?>"></p>

			<p><label for="descricao">Descrição: </label> <textarea  id="descricao" name="descricao" maxlength="250" value="<?=($erros!=="") ? $descricao : ''?>"></textarea></p>

			<div>
				<p><label for="caracteristicas[]">Características</label>
					<p><label><input type="checkbox" name="caracteristicas[]" value="JLL">Jantes de liga leve</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="DA">Direcção assistida</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="FC">Fecho central</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="ESP">ESP</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="AR">Ar condicionado</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="VE">Vidros eléctricos</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="CB">Computador de bordo</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="FN">Faróis de nevoeiro</label></p>

					<p><label><input type="checkbox" name="caracteristicas[]" value="LR">Livro de revisões</label></p>
				</p>
			</div>

			<p><label for="preco">Preço: </label> <input type="text" id="preco" name="preco" maxlength="20" value="<?=($erros!=="") ? $preco : ''?>"><span> €</span></p>

			<p>
				<input type="hidden" name="destaque" value="0">
				<label><input type="checkbox" name="destaque" value="1">Colocar em destaque?</label>
			</p>

			<p><label>Foto1:</label> <input type="file" id="foto1" name="foto1"></p>

			<p><label>Foto2:</label> <input type="file" id="foto2" name="foto2"></p>

			<p><label>Foto3:</label> <input type="file" id="foto3" name="foto3"></p>

			<p><label>Foto4:</label> <input type="file" id="foto4" name="foto4"></p>

			<p><label>Foto5:</label> <input type="file" id="foto5" name="foto5"></p>

			<p><input type="submit" id="btSubmit" name="btSubmit" value="Registar anúncio"></p>
</body>
</html>
