<?php
$email=$_GET['email'];
$token=$_GET['token'];
$erros="";
require "bdligaStandVirtual.php";
$comando=sprintf("SELECT * FROM utilizadores WHERE email='%s'",$email);
$resultado=mysqli_query($liga,$comando);
if (mysqli_num_rows($resultado)===0) {
    echo "<p>Não existe registado o email fornecido.</p>";
} else {
    $linha=mysqli_fetch_assoc($resultado);
    if (empty($linha['token']) && $linha['estado']==="A") {
        $erros="<p>O registo já foi confirmado.</p>";
    } else {
        if ($linha['token']!==$token) {
            $erros="<p>O token está errado.</p>";
        } else {
            $comandoActualiza="UPDATE utilizadores SET token=null, estado='A' WHERE email='".$email."'";
            mysqli_query($liga,$comandoActualiza);
            if (mysqli_affected_rows($liga)!==1) {
                $erros="<p>Ocorreu um erro na confirmação do registo no site. Por favor contacte-nos.</p>";
            } else {
                header("Location: registook.php?msg=1");
            }
        }
    }
}
mysqli_free_result($resultado);
mysqli_close($liga);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmação de registo</title>
</head>
<body>
<?=$erros?>
<p><a href="index.php">Voltar à página principal</a></p>
</body>
</html>
