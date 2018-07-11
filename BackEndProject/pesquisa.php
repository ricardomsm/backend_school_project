<?php
	include "bdligaStandVirtual.php";
	$comandoPesquisaModelo= "SELECT * FROM modelos WHERE cod_marca='".$_POST['marcaEscolhida']."'";
	$resultadoModelo= mysqli_query($liga,$comandoPesquisaModelo);
	if (!$resultadoModelo) {
			echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
			die();
	}
	 while($linha=mysqli_fetch_assoc($resultadoModelo)) {

?>
		<option value="<?= $linha['cod_modelo']?>"><?= $linha['modelo']?></option>
<?php
	 }
?>
