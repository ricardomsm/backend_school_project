<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stand Virtual</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="index.php"><img src="imagens/logotipo.png"></a>
<hr>

<?php
  if (empty($_GET['msg'])) {
    echo "<p>Obrigado por se ter registado. Vai receber uma mensagem de email para confirmar o registo.</p>";
  } else if ($_GET['msg']==1) {
    echo "<p>Registo confirmado!</p>";
  }
?>
<!-- <p>Obrigado por se ter registado. Vai receber uma mensagem de email para confirmar o registo.</p> -->

<p><a href="index.php">Voltar à página principal</a></p>



</body>
</html>
