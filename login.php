<?php
session_start();
include 'parts/header.php';
require 'parts/functions.php';
$errors = [];
if($_SERVER["REQUEST_METHOD"] == 'POST'){
    if(empty($_POST["email"])){
        $errors["email"] = 'Veuillez saisir un identifiant';
    }
    // if(!filter_var($_POST["email"])){
    //     $errors["email"] = "L'identifiant n'est pas valide";
    // }
    if(empty($_POST["password"])){
        $errors["password"] = 'Veuillez saisir un mot de passe';
    }
    if(count($errors) == 0){
        $stmt = $pdo->prepare(
                'SELECT * FROM manager WHERE email = :email');
        $stmt->bindParam(':email', $_POST["email"]);
        var_dump($_POST["email"]);
        $stmt->execute();
        $res = $stmt->fetch();
        // la variable $res prend pour valeur les mails des users
var_dump($res);
        // si un email n'exsite pas, $res prend pour valeur "false"
        // et si le mdp ne correspond pas, on renvoie un message d'erreur
        if(!$res || !password_verify($_POST["password"], $res["password"])){
            $errors["password"] = 'Identifiant ou mot de passe incorrect';
        } else {
            
            $_SESSION["email"] = $res["email"];
            header('Location: restricted.php');
            // sinon (un mail exsite) et donc on verifier le mdp avec le hachage
            // Le hash correspond, c'est ok
            // J'ajoute la session et je redirige l'utilisateur
        }
        // var_dump($res);
        die();
    }
}
?>

<body>
<div class="container">
<h1>Vous connecter</h1>

<?php
if(isset($_GET["message"]) && $_GET["message"] == 'success-login'){
    echo('<div class="alert alert-success" role="alert">
Vous êtes enregistré !
</div>');
}
?>


<?php
if(isset($_GET["message"]) && $_GET["message"] == 'logout'){
    echo('<div class="alert alert-warning" role="alert">
Déconnecté
</div>');
}
?>

<?php
if(isset($_GET["message"]) && $_GET["message"] == 'error-login'){
    echo('<div class="alert alert-danger" role="alert">
Vous devez dabord vous connecter ...
</div>');
}
?>

<form method="post">
<div class="form-group">
    <label for="email">Identifiant</label>
    <input id="email" class="form-control  <?php
    if(array_key_exists("email", $errors)){
        echo('is-invalid');
    } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
        echo('is-valid');
    }?>" type="text"
           name="email" placeholder="identifiant">

    <?php
    if(array_key_exists("email", $errors)){
        echo('<div id="validationServerUsernameFeedback" class="invalid-feedback">
            '.$errors['email'].'
            </div>');
    } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
        echo('<div class="valid-feedback">
                Looks good!
        </div>');
    }
    ?>
</div>

<div class="form-group mt-2">
    <label for="password">Mot de passe</label>
    <input id="password" class="form-control  <?php
    if(array_key_exists("email", $errors)){
        echo('is-invalid');
    } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
        echo('is-valid');
    }?>" type="password" name="password" placeholder="Mot de passe">

    <?php
    if(array_key_exists("password", $errors)){
        echo('<div id="validationServerUsernameFeedback" class="invalid-feedback">
            '.$errors['password'].'
            </div>');
    } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
        echo('<div class="valid-feedback">
                Looks good!
        </div>');
    }
    ?>

</div>

<input type="submit" class="btn btn-success mt-3">
</form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>


<?php
include 'parts/footer.php';
?>