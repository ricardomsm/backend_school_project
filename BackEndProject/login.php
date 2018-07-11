<?php
session_start();
$erros="";
if (isset($_SESSION['id'])) {
    $_SESSION['pag']="perfil.php";
    header("Location: perfil.php");
}
if (isset($_POST['btSubmit'])) {
    $email=$_POST['email'];
    $senha=$_POST['senha'];
    if (empty($email) || empty($senha)) {
        $erros="<p>Todos os campos são de preenchimento obrigatório.</p>";
    } else {
        require "bdligaStandVirtual.php";
        $comando=sprintf("SELECT nome,cod_utilizador,estado,senha FROM utilizadores WHERE email='%s'",$email);
        $resultado=mysqli_query($liga,$comando);
        if (mysqli_num_rows($resultado)===0) {
            $erros="<p>Utilizador inexistente.</p>";
        } else {
            // utilizador existe
            $linha=mysqli_fetch_assoc($resultado);
            switch ($linha['estado']) {
                case "R":
                    $erros="<p>A sua conta não está activada. Por favor verifique o seu email.</p>";
                    break;
                case "X":
                case "A":
                    if (password_verify($senha,$linha['senha'])) {
                        $_SESSION['id']=$linha['cod_utilizador'];
                        $_SESSION['nome']=$linha['nome'];
                        if (isset($_SESSION['pag'])) {
                            $pagina=$_SESSION['pag'];
                            unset($_SESSION['pag']);
                            header("Location: ".$pagina);
                            exit();
                        } else {
                            header("Location: perfil.php");
                            exit();
                        }
                    } else {
                        $erros="<p>Senha errada.</p>";
                    }
                    break;

                default:
                    $erros="<p>Erro inexistente.</p>";
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
  <title>Stand Virtual - Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="index.php"><img src="imagens/logotipo.png"></a>
<hr>
<h1>Login</h1>
<?php
    if (isset($erros)) {
        echo $erros;
    }
?>
<form id="formlogin" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <p><label for="nome">Email:</label> <input type="text" id="email" name="email" maxlength="50"></p>
    <p><label for="senha">Senha: </label> <input type="password" id="senha" name="senha" maxlength="20"></p>
    <p><input type="submit" id="btSubmit" name="btSubmit" value="Validar"></p>
</form>
</body>
</html>
</body>
</html>
