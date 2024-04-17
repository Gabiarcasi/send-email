<?php

    // Definição do SMTP_PASSWORD
    // Para gerar uma senha de aplicativo para usar em vez da senha da conta do Gmail, siga os seguintes passos:
    //Acesse sua Conta do Google.
    //Selecione 'Segurança'.
    //Em 'Como você faz login no Google', selecione 'Verificação em duas etapas'.
    //Na parte de baixo da página, selecione 'Senhas de app'.
    //Insira o nome 'PHPMailer'.
    //Clique em 'Gerar'.
    //A senha de aplicativo gerada terá 16 dígitos e autorizará o acesso à sua Conta do Google por um dispositivo ou app menos seguro.

    global $CFG, $aDEBUG, $variaveis;

    require_once $CFG->dirroot . '/seu_caminho/PHPMailer/src/PHPMailer.php';
    require_once $CFG->dirroot . '/seu_caminho/PHPMailer/src/Exception.php';
    require_once $CFG->dirroot . '/seu_caminho/PHPMailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    function toSendEmail($file_path, $variaveis, $body) {

        $mail = new PHPMailer(true);


        
        $email_sender          =  $variaveis['-----']; // Email do Remetente
	      $password_sender       =  $variaveis['-----']; // Senha do email do Remetente
        $email_addressees      =  $variaveis['-----']; // Email(s) do(s) Destinatário(s)
        $email_title           =  $variaveis['-----']; // Titulo do Email
        $email_body            =  $variaveis['-----']; // Corpo do email, feito com html
        $email_sbody           =  $variaveis['-----']; // Corpo do email, para plataformas que NÃO aceitam html

        // Definindo a senha propria para o PHPMailer
        define('SMTP_PASSWORD', $password_sender);
        
        // Configurações do servidor
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; // dominio host
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = ---; // true or false
        $mail->Username = $email_sender;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = '---';
        $mail->Port = ---; 

        // Substituindo variáveis
        foreach ($variaveis as $variavel => $valor) {
            $email_title = str_replace(":$variavel", $valor, $email_title);
            $email_body = str_replace('$' . $variavel, $valor, $email_body);
            $email_sbody = str_replace('$' . $variavel, $valor, $email_sbody);
        }
        
        // Remetente
        $mail->setFrom($email_sender, 'NOME DO REMETENTE');
        
        // Destinatários
        $emails = explode(',', $email_addressees);
        foreach($emails as $email) {
            $mail->addAddress($email);
        }
        
        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $email_title;
        $mail->Body = $body;
        $mail->AltBody = $email_sbody;
        
        // Anexar o PDF
        $mail->addAttachment($file_path);

        $mail->send();
    }
?>
