<?php
$erros="";
if (isset($_POST['btSubmit'])) {
    // echo "<pre>".print_r($_POST,TRUE)."</pre>";
    require "bdligaStandVirtual.php";
    $nome=mysqli_real_escape_string($liga,$_POST['nome']);
    $email=mysqli_real_escape_string($liga,$_POST['email']);
    $morada=mysqli_real_escape_string($liga,$_POST['morada']);
    $localidade=mysqli_real_escape_string($liga,$_POST['localidade']);
    $cp=mysqli_real_escape_string($liga,$_POST['cp']);
    $cp_localidade=mysqli_real_escape_string($liga,$_POST['cp_localidade']);
    $telefone=(int)$_POST['telefone'];
    $senha=$_POST['senha'];
    $rsenha=$_POST['rsenha'];
    $token="";

    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave])) {
            $erros.="<p>Todos os campos são de preenchimento obrigatório.</p>";
            break;
        }
    }

    /* Verificar o Captcha */
    include_once 'securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false) {
        $erros.="<p>Código de segurança errado.</p>";
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
        // testar senha e rsenha - verificar se são iguais
        if ($senha!==$rsenha) {
           $erros.="<p>O conteúdo dos campos 'senha' e 'repetir senha' tem de ser igual.</p>";
        }

        if ($erros==="") {

            // Verificar se o email já existe antes de inserir

            $comandoExiste="SELECT email FROM utilizadores WHERE email='".$email."'";
            $resultadoExiste=mysqli_query($liga,$comandoExiste);

            if (mysqli_num_rows($resultadoExiste)===0) {

                $token=sha1(uniqid());

                $senha=password_hash($senha, PASSWORD_DEFAULT);

                $comandoInserir=sprintf("INSERT INTO utilizadores(nome,email,morada,localidade,cp_numerico,cp_localidade,telefone,senha,token,estado) VALUES('%s','%s','%s','%s','%s','%s','%d','%s','%s','%s')",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$senha,$token,'R');
                if (!mysqli_query($liga,$comandoInserir)) {
                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                    exit();
                }
                if (mysqli_affected_rows($liga)===0) {
                    $erros.="<p>Erro na inserção do utilizador.</p>";
                } else {
                    // enviar email
                    require_once 'swiftmailer-5.x/lib/swift_required.php';

                    $transport = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25)
                        ->setUsername('4b7d7abba0ef82')
                        ->setPassword('a08a9fb9218ed3');
                    $mailer = Swift_Mailer::newInstance($transport);
                    $message = Swift_Message::newInstance()
                        // Give the message a subject
                        ->setSubject('Confirmação de registo')

                        // Set the From address with an associative array
                        ->setFrom(array('teste@flag.pt' => 'Flag'))

                        // Set the To addresses with an associative array
                        ->setTo(array($email => $nome))

                        // Give it a body
                        ->setBody('Para activar a sua conta copie o endereço seguinte para o seu browser - http://localhost/projecto2/activar_registo.php?email='.$email.'&token='.$token)

                        // And optionally an alternative body
                        ->addPart('<p>Clique no <a href="http://localhost/projecto2/activar_registo.php?email='.$email.'&token='.$token.'">link</a> para activar a sua conta!</p>', 'text/html');

                        // Optionally add any attachments
                        // ->attach(Swift_Attachment::fromPath('fotos/JoJoMoyes.jpg'));

                    if ($mailer->send($message)==0) {
                        $erros.="<p>O seu registo foi efectuado, mas houve um erro no envio do email de confirmação. Por favor, contacte-nos.</p>";
                    } else {
                        header("Location: registook.php");
                    }

                }
            } else {
                $erros.="<p>Já existe um utilizador com o email fornecido.</p>";
            }
            mysqli_free_result($resultadoExiste);
        }
    }
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stand Virtual - Registo</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="index.php"><img src="imagens/logotipo.png"></a>
	<nav class="navbar">
	  <a href="#">Recuperar senha</a>
	  <span>|</span>
	  <a href="registo.php">Registo de utilizador</a>
	  <span>|</span>
	  <a href="#">Acesso Reservado</a>
	</nav>
	<hr>

<h1>Registo de utilizador</h1>
<?php
    if ($erros!=="") {
        echo $erros;
    }
?>
<form id="formutilizador" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <p><label for="nome">Nome:</label> <input type="text" id="nome" name="nome" maxlength="80" value="<?=($erros!=="") ? $nome : ''?>"></p>

    <p><label for="email">Email: </label> <input type="text" id="email" name="email" maxlength="50" value="<?=($erros!=="") ? $email : ''?>"></p>

    <p><label for="morada">Morada:</label> <input type="text" id="morada" name="morada" maxlength="120" value="<?=($erros!=="") ? $morada : ''?>"></p>

    <p><label for="localidade">Localidade:</label> <input type="text" id="localidade" name="localidade" maxlength="30" value="<?=($erros!=="") ? $localidade : ''?>"></p>

    <p><label for="cp">CP:</label> <input type="text" id="cp" name="cp" maxlength="8" value="<?=($erros!=="") ? $_POST['cp'] : '' ?>"> <input type="text" id="cp_localidade" name="cp_localidade" maxlength="30" value="<?=($erros!=="") ? $cp_localidade : ''?>"></p>

    <p><label for="telefone">Telefone: </label> <input type="text" id="telefone" name="telefone" maxlength="9"  value="<?=($erros!=="") ? $telefone : ''?>"></p>

    <p><label for="senha">Senha: </label> <input type="password" id="senha" name="senha" maxlength="20"></p>

    <p><label for="rsenha">Repetir Senha: </label> <input type="password" id="rsenha" name="rsenha" maxlength="20"></p>

    <p><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"></p>

    <p><input type="text" name="captcha_code" size="10" maxlength="6"> <a href="">[ Outra imagem ]</a></p>

    <p><input type="submit" id="btSubmit" name="btSubmit" value="Inserir registo"></p>
</form>
</body>
</html>
