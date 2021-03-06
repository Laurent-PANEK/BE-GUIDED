<?php
require_once 'php/load.php';
$db = App::getDatabase();
if (!empty($_POST)) {
    $validator = new Validator($_POST, Session::getInstance());
    $validator->isAlphaNum('lastname', 'Votre Nom ne doit contenir que des caractère alphanumérique');
    if ($validator->isValid()) {
        $validator->isUnique('lastname', $db, 'users', 'Votre Nom d\'utilisateur est déjà utilisé ! Veuillez changer.');
    }
    $validator->isAlphaNum('firstname', 'Votre Prenom ne doit contenir que des caractère alphanumérique');
    if ($validator->isValid()) {
        $validator->isUnique('firstname', $db, 'users', 'Votre Nom d\'utilisateur est déjà utilisé ! Veuillez changer.');
    }
    $validator->isEmail('email', 'Votre email n\'est pas valide !');
    if ($validator->isValid()) {
        $validator->isUnique('email', $db, 'users', 'Votre email est déjà utilisé ! Veuillez changer.');
    }
    $validator->isConfirmed('pass', 'Les mots de passe ne correspondent pas.');
    
    if ($validator->isValid()) {
        App::getUser()->register($db, $_POST['firstname'], $_POST['lastname'], $_POST['pass'], $_POST['email']);
        Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour validé votre compte');
        App::redirect('connexion.php');
    }
}
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, target-densitydpi=device-dpi"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto|Poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/animate.css/3.5.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <!-- Theme CSS -->
    <link href="css/inscription.css" rel="stylesheet">
    <link rel="stylesheet" href="css/flash.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include 'php/nav.php'; ?>
<div class="form">
    <div id="signup">

        <h1>Inscrivez-vous gratuitement et bénéficiez de tous les avantages sur nos offres.</h1>

        <form action="" method="post">

            <div class="top-row">
                <div class="field-wrap">
                    <label>
                        Nom<span class="req">*</span>
                    </label>
                    <input id="lst" name="lastname" type="text" value="" >
                    <span id="error" class="error"></span>
                </div>

                <div class="field-wrap">
                    <label>
                        Prénom<span class="req">*</span>
                    </label>
                    <input id="fst" name="firstname" type="text" value="" >
                </div>
            </div>

            <div class="field-wrap">
                <label>
                    Adresse e-mail<span class="req">*</span>
                </label>
                <input id="mail"name="email" type="email" value="">
            </div>

            <div class="field-wrap">
                <label>
                    Mot de passe<span class="req">*</span>
                </label>
                <input id="pass" name="pass" type="password" value="">
            </div>

            <div class="field-wrap">
                <label>
                    Confirmez votre mot de passe<span class="req">*</span>
                </label>
                <input id="repass" name="pass_confirm" type="password" value="" >
            </div>

            <button type="submit" class="button button-block">Inscription</button>

        </form>
        <br>
        <a href="index.php" style="float:right;"><i class="fa fa-home" aria-hidden="true"></i> Accueil</a>

    </div>
</div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script src="js/inscon.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Plugin JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

<!-- Theme JavaScript -->
<script src="js/project.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</body>
</html>
