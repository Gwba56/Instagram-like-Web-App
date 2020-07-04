<?php

class User {
    
    private $db;
    private $username;
    private $email;
    private  $password;
    private $token;
    private $notifications;
    

    public function __construct() {
        if (file_exists('config/database.php')) {
            include 'config/database.php';
        } elseif (file_exists('../config/database.php')){
            include '../config/database.php';
        }
        try {
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    /*****************************************************************************************************************
    *                                                 ACTIONS                                                        *
    ******************************************************************************************************************/
    
    function create_user() {
        try {
            $request = $this->db->prepare('INSERT INTO users_tb(username, email, password, token) VALUES (:username, :email, :password, :token)');
            $request->bindParam(':username', $this->username);
            $request->bindParam(':email', $this->email);
            $request->bindParam(':password', $this->password);
            $request->bindParam(':token', $this->token);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $header = 'MIME-Version: 1.0' . "\r\n";   
        $header .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
        $header .= 'From:camagru@camagru.fr' . "\r\n";
        $header .= 'X-Mailer: PHP/' . phpversion();
        $subject = "Activation de votre compte Camagru" ;
        $content = 'Bienvenue sur Camagru !
                Pour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur internet.
                http://localhost:8080/activation.php?activate_account='.urlencode($this->token).'
        
            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';
    
        mail($this->email, $subject, $content, $header);
        $_SESSION['success'] = "Compte créé : veuillez l'activer en cliquant sur le lien qui vient de vous avoir été envoyé par email. ";
    }

    function activate_and_login($token){
        try {
            $request = $this->db->prepare("SELECT * FROM users_tb WHERE username = :username AND password = :password");
            $request->bindParam(':username', $this->username);
            $request->bindParam(':password', $this->password);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetch(PDO::FETCH_ASSOC);
        if(!$result) {
            $_SESSION['error'] = "Mot de passe incorrect";
        }
        else if ($token == null OR $result['token'] != $token) {
            $_SESSION['error'] = "Lien d'activation est incorrect. ";
        }
        else if ($result['activated'] == 1) {
            $_SESSION['error'] = "Votre compte a déjà été activé: veuillez vous connecter avec vos identifiants. ";
        } else {
            $this->activate($result['userid']);
            $_SESSION['userid'] = $result['userid'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['notifications'] = $result['notifications'];
            $_SESSION['success'] = "Votre compte est activé.";
        }
    }

    function activate($userid) {
        try {
            $request = $this->db->prepare('UPDATE users_tb SET activated = 1 WHERE userid = :userid');
            $request->bindParam(':userid', $userid);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function login(){
        try {
            $request = $this->db->prepare("SELECT * FROM users_tb WHERE username = :username AND password = :password");
            $request->bindParam(':username', $this->username);
            $request->bindParam(':password', $this->password);
            $request->execute();
            $result = $request->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        if(!$result) {
            $_SESSION['error'] = "Mot de passe incorrect.";
        }
        else if ($result['activated'] != 1) {
            $_SESSION['error'] = "Votre compte n'est pas activé: vérifiez votre adresse email. ";
        } else {
            $_SESSION['userid'] = $result['userid'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['notifications'] = $result['notifications'];
            $_SESSION['success'] = "Vous êtes connecté.";
        }
    }

    function reset_and_login($token,$password){
        try {
            $request = $this->db->prepare("SELECT * FROM users_tb WHERE username = :username");
            $request->bindParam(':username', $this->username);
            $request->execute();
            $result = $request->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        if(!$result) {
            $_SESSION['error'] = "Nom d'utilisateur incorrect";
        }
        else if ($token == null OR $result['token'] != $token) {
            $_SESSION['error'] = "Lien d'activation est incorrect. ";
        }
        else if ($result['activated'] != 1) {
            $_SESSION['error'] = "Votre compte n'a pas été activé: veuillez vérifier votre adresse email. ";
        } else {
            echo $this->password;
            $this->reset_password($result['userid']);
            $_SESSION['userid'] = $result['userid'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['notifications'] = $result['notifications'];
            $_SESSION['success'] = "Votre mot de passe a été modifié. ";
        }
    }

    function reset_password($userid) {
        try {
            $request = $this->db->prepare('UPDATE users_tb SET `password` = :password, token = null WHERE userid = :userid');
            $request->bindParam(':password', $this->password);
            $request->bindParam(':userid', $userid);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function modify_user($userid) {
        try {
            $request = $this->db->prepare('UPDATE users_tb SET username = :username, email = :email, notifications = :notifications WHERE userid = :userid');
            $request->bindParam(':username', $this->username);
            $request->bindParam(':email', $this->email);
            $request->bindParam(':notifications', $this->notifications);
            $request->bindParam(':userid',$userid);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $_SESSION['username'] = $this->username;
        $_SESSION['email'] = $this->email;
        $_SESSION['notifications'] = $this->notifications;
        $_SESSION['success'] = "Vos données ont été modifiées. ";
    }

    function send_reset_link($username) {
        $token = $this->set_token();
        try {
            $request = $this->db->prepare('UPDATE users_tb SET `token` = :token WHERE username = :username');
            $request->bindParam(':token', $token);
            $request->bindParam(':username', $username);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $email = $this->get_email($username);
        $header = 'MIME-Version: 1.0' . "\r\n";   
        $header .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
        $header .= 'From:camagru@camagru.fr' . "\r\n";
        $header .= 'X-Mailer: PHP/' . phpversion();
        $subject = "Réinitialisation de votre mot de passe Camagru" ;
        $content = 'Bonjour !
                Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur internet.
                http://localhost:8080/resetPsswd2.php?reset_pwd='.urlencode($token).'
        
            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';
        mail($email, $subject, $content, $header);
        $_SESSION['success'] = "Un email de réinitialisation vous a été envoyé. ";
    }
    
    function check_registration_form($username, $email, $password, $password_confirmation) {
        $error="";
        if(empty($username) OR empty($email) OR empty($password) OR empty($password_confirmation)) {
            $error .= "Tous les champs doivent être complétés. ";
        }
        if($username AND !preg_match("/[a-zA-Z0-9]+/",$username)) {
            $error .= "Votre identifiant ne doit comporter que des lettres et des chiffres. ";
        }
        if($this->username_already_exists($username) == true) {
            $error .= "Votre identifiant est déjà utilisé. ";
        }
        if($email AND !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= "Le format de votre adresse email n'est pas valide. ";
        }
        if($this->email_already_exists($email) == true) {
            $error .= "Cette adresse email a déjà été utilisée. ";
        }
        if ($password AND !preg_match ("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$/", $password)) {
            $error .= "Votre mot de passe doit comporter au moins 8 caractères et au maximum 30, dont une lettre majuscule, une lettre minuscule et un chiffre.";
        }
        if($password AND $password_confirmation AND $password != $password_confirmation) {
            $error .= "Vos mots de passe ne sont pas identiques. ";
        }
        if($error == "") {
            $this->username = $username;
            $this->email = $email;
            $this->password = $this->hash_password($password);
            $this->token = $this->set_token();
            return ;
        }
        return $error;
    }

    function check_login_form($username, $password) {
        $error = "";
        if(empty($username) OR empty($password)) {
            $error .= "Tous les champs doivent être complétés. ";
        }
        if(!empty($username) AND $this->username_already_exists($username) == false) {
            $error .= "Ce nom d'utilisateur n'existe pas.";
        }
        if($error == "") {
            $this->username = $username;
            $this->password = $this->hash_password($password);
            return ;
        }
        return $error;
    }

    function check_reset_link_form($username) {
        $error = "";
        if(empty($username)) {
            $error .= "Vous devez compléter le formulaire. ";
        }
        if(!empty($username) AND $this->username_already_exists($username) == false) {
            $error .= "Ce nom d'utilisateur n'existe pas.";
        }
        if($error == "") {
            $this->username = $username;
            return ;
        }
        return $error;
    }

    function check_reset_password_form($username,$password,$password_confirmation,$token) {
        $error = "";
        if(empty($username)) {
            $error .= "Vous devez compléter le formulaire. ";
        }
        if(!empty($username) AND $this->username_already_exists($username) == false) {
            $error .= "Ce nom d'utilisateur n'existe pas.";
        }
        if($password AND !preg_match ("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$/", $password)) {
            $error .= "Votre mot de passe doit comporter au moins 8 caractères, dont une lettre majuscule, une lettre minuscule et un chiffre.";
        }
        if($password AND $password_confirmation AND $password != $password_confirmation) {
            $error .= "Vos mots de passe ne sont pas identiques. ";
        }
        if($error == "") {
            $this->username = $username;
            $this->password = $this->hash_password($password);
            return ;
        }
        return $error;
    }

    function check_reset_password2_form($userid,$old_password,$password,$password_confirmation) {
        $error = "";
        if(empty($old_password) OR empty($password) OR empty($password_confirmation)) {
            $error .= "Vous devez compléter le formulaire. ";
        }
        if($this->check_old_password($userid,$old_password) == false) {
            $error .= "Votre ancien mot de passe n'est pas valide. ";
        }
        if($password AND !preg_match ("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
            $error .= "Votre mot de passe doit comporter au moins 8 caractères et au maximum 30, dont une lettre majuscule, une lettre minuscule et un chiffre.";
        }
        if($password AND $password_confirmation AND $password != $password_confirmation) {
            $error .= "Vos mots de passe ne sont pas identiques. ";
        }
        if($error == "") {
            $this->password = $this->hash_password($password);
            return ;
        }
        return $error;
    }
    
    function check_user_modif_form($username, $email, $notifications) {
        $error = "";
        if($username !== $_SESSION['username']) {
            if(!preg_match("/[a-zA-Z0-9]+/",$username)) {
                $error .= "Votre identifiant ne doit pas comporter que des lettres et des chiffres. ";
            }
            if($this->username_already_exists($username) == true) {
                $error .= "Votre identifiant est déjà utilisé. ";
            }
        }
        if($email !== $_SESSION['email']) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error .= "Le format de votre adresse email n'est pas valide. ";
            }
            if($this->email_already_exists($email) == true) {
                $error .= "Cette adresse email a déjà été utilisée. ";
            } 
        }
        if($error == "") {
            $this->username = $username;
            $this->email = $email;
            $this->notifications = $notifications;
            return ;
        }
        return $error;
    }

    function check_old_password($userid,$password) {
        $password = $this->hash_password($password);
        try {
            $request = $this->db->prepare("SELECT * FROM users_tb WHERE userid = :userid AND password = :password");
            $request->bindParam(':userid', $userid);
            $request->bindParam(':password', $password);
            $request->execute();
            $result = $request->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        } 
        if(!$result) {
            return false;
        } else {
            return true;
        }  
    }

    function email_already_exists($email) {
        try {
            $request = $this->db->prepare('SELECT email FROM users_tb WHERE email = :email');
            $request->bindParam(':email', $email);
            $request->execute();
            $result = $request->fetch();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        if(!$result) {
            return false;
        } else {
            return true;
        }
    }

    function username_already_exists($username) {
        try {
            $request = $this->db->prepare('SELECT username FROM users_tb WHERE username = :username');
            $request->bindParam(':username', $username);
            $request->execute();
            $result = $request->fetch();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        if(!$result) {
            return false;
        } else {
            return true;
        }
    }

    function get_email($username) {
        try {
            $request = $this->db->prepare('SELECT email FROM users_tb WHERE username = :username');
            $request->bindParam(':username', $username);
            $request->execute(); 
            $result = $request->fetch();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        if(!$result) {
            $_SESSION['error'] = "Ce compte n'existe pas";
            return ;
        }
        return $result['email'];
    }
    
    /*****************************************************************************************************************
    *                                                 HELPERS                                                        *
    ******************************************************************************************************************/

    function hash_password($password) {
        $hashed_password = hash('whirlpool', "dream".$password."on");
        return $hashed_password;
    }

    function set_token() {
       $token = bin2hex(random_bytes(24)); 
       return $token;
    }
}