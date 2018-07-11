<?php
session_start();
if (!isset($_SESSION['id'])) {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}
$erros="";
if (!isset($_POST['btSubmit'])) {
    require "bdligaStandVirtual.php";
    $comando="SELECT * FROM utilizadores WHERE cod_utilizador=".$_SESSION['id'];
    $resultado=mysqli_query($liga,$comando);
    if (mysqli_num_rows($resultado)===0) {
        header("Location: index.php");
        exit();
    }
    $linha=mysqli_fetch_assoc($resultado);
    $nome=$linha['nome'];
    $morada=$linha['morada'];
    $localidade=$linha['localidade'];
		$cp=$linha['cp_numerico'];
    $cp_localidade=$linha['cp_localidade'];
    $telefone=$linha['telefone'];
    $email=$linha['email'];
    $_SESSION['email']=$email;
} else {
    echo "Submetido";
    echo "<pre>".print_r($_POST,TRUE)."</pre>";
    echo "<pre>".print_r($_FILES,TRUE)."</pre>";
    $nome=$_POST['nome'];
    $morada=$_POST['morada'];
    $localidade=$_POST['localidade'];
    $cp=$_POST['cp'];
    $cp_localidade=$_POST['cp_localidade'];
    $telefone=$_POST['telefone'];
    $email=$_POST['email'];

    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave])) {
            $erros.="<p>Todos os campos são de preenchimento obrigatório.</p>";
            break;
        }
    }

    if ($erros==="") {
        if (!preg_match("/^[a-zA-Z ]{6,}$/",$nome)) {
            $erros.="<p>O campo 'nome' só pode conter letras e espaços e ter no mínimo 6 caracteres.</p>";
        }
        if (!preg_match("/^[1-9][0-9]{3}-[0-9]{3}$/",$cp)) {
            $erros.="<p>O campo 'cp' tem o formato 9999-999.</p>";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros.="<p>O valor introduzido no campo 'email' não tem o formato correcto.</p>";
        }

        if (!preg_match("/^[1-9]{1}[0-9]{8}$/",$telefone)) {
            $erros.="<p>O campo 'telefone' tem de conter 9 caracteres.</p>";
        }

        if ($erros==="") {
            // Verificar se o email já existe antes de alterar
            require "bdligaStandVirtual.php";
            $comandoExiste="SELECT email FROM utilizadores WHERE email='".$email."' AND cod_utilizador<>".$_SESSION['id'];
            $resultadoExiste=mysqli_query($liga,$comandoExiste);

            if (mysqli_num_rows($resultadoExiste)===0) {
                $_SESSION['nome']=$nome;
                if ($_SESSION['email']!==$email) {
                    $token=sha1(uniqid());
                			$comandoAltera=sprintf("UPDATE utilizadores SET nome='%s',email='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d,token='%s',estado='R' WHERE cod_utilizador=%d",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$token,$_SESSION['id']);

                    if (!mysqli_query($liga,$comandoAltera)) {
                        echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                        exit();
                    }
                    if (mysqli_affected_rows($liga)===0) {
                        $erros.="<p>Erro na inserção do utilizador.</p>";
                    } else {
                        require_once 'swiftmailer-5.x/lib/swift_required.php';

                        $transport = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25)
                            ->setUsername('4b7d7abba0ef82')
                            ->setPassword('a08a9fb9218ed3');
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()
                            // Give the message a subject
                            ->setSubject('Confirmação de alteração')

                            // Set the From address with an associative array
                            ->setFrom(array('teste@flag.pt' => 'Flag'))

                            // Set the To addresses with an associative array
                            ->setTo(array($email => $nome))

                            // Give it a body
                            ->setBody('Para reactivar a sua conta copie o endereço seguinte para o seu browser - http://localhost/remoaldo/conf_registo.php?email='.$email.'&token='.$token)

                            // And optionally an alternative body
                            ->addPart('<p>Clique no <a href="http://localhost/remoaldo/conf_registo.php?email='.$email.'&token='.$token.'">link</a> para reactivar a sua conta!</p>', 'text/html');

                            // Optionally add any attachments
                            // ->attach(Swift_Attachment::fromPath('fotos/JoJoMoyes.jpg'));

                        if ($mailer->send($message)==0) {
                            $erros.="<p>O seu registo foi efectuado, mas houve um erro no envio do email de confirmação. Por favor, contacte-nos.</p>";
                        } else {
                            header("Location: mensagens.php?msg=3");
                            exit();
                        }
                    }
                } else {
                    $comandoAltera=sprintf("UPDATE utilizadores SET nome='%s',email='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d,token='%s',estado='R' WHERE cod_utilizador=%d",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$token,$_SESSION['id']);
                    }
                    if (!mysqli_query($liga,$comandoAltera)) {
                        echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                        exit();
                    }
                    if (mysqli_affected_rows($liga)===0) {
                        $erros.="<p>Erro na inserção do utilizador.</p>";
                    } else {
                        header("Location: perfil.php");
                        exit();
                    }
                }
            } else {
                $erros.="<p>Já existe um utilizador registado na base de dados com o email fornecido.</p>";
            }
            mysqli_free_result($resultadoExiste);
        }
    }
if (isset($_POST['btCancel'])) {
	header("Location: perfil.php");
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alterar perfil</title>
		<link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="index.php"><img src="imagens/logotipo.png"></a>

<nav class="navbar">
  <a href="perfil.php">Perfil</a>
  <span>|</span>
  <a href="logout.php">Sair</a>
</nav>
<hr>

<h1>Alterar perfil</h1>
<?php
    if ($erros!=="") {
        echo $erros;
    }
?>
<form id="formutilizador" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

		<p><label for="nome">Nome:</label> <input type="text" id="nome" name="nome" maxlength="80" value="<?=$nome?>"></p>

		<p><label for="email">Email: </label> <input type="text" id="email" name="email" maxlength="50" value="<?=$email?>"></p>

		<p><label for="morada">Morada:</label> <input type="text" id="morada" name="morada" maxlength="120" value="<?=$morada?>"></p>

		<p><label for="localidade">Localidade:</label> <input type="text" id="localidade" name="localidade" maxlength="30" value="<?=$localidade?>"></p>

		<p><label for="cp">CP:</label> <input type="text" id="cp" name="cp" maxlength="8" value="<?=$cp?>"><input type="text" id="cp_localidade" name="cp_localidade" maxlength="30" value="<?=$cp_localidade?>"></p>

		<p><label for="telefone">Telefone: </label> <input type="text" id="telefone" name="telefone" maxlength="9"  value="<?=$telefone?>"></p>

		<p><input type="submit" id="btSubmit" name="btSubmit" value="Alterar perfil"> <input type="submit" id="btCancel" name="btCancel" value="Cancelar"></p>
</form>
<p><a href="perfil.php">Voltar ao perfil</a></p>
<p><a href="index.php">Voltar à página principal</a></p>
</body>
</html>
