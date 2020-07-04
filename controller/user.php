<?php

require_once '../model/User.Class.php';

// Create user
if(isset($_GET['create_user'])) {
    session_start();
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $user = new User;
    $_SESSION['error'] = $user->check_registration_form($username, $email, $password, $password_confirmation);
    if(isset($_SESSION['error'])) {
        header('Location:../register.php');
    } else {
        $user->create_user();
        header('Location:../login.php');
    }
}

if(isset($_GET['activate_account'])) {
    session_start();
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $token = $_POST['token'];
    $user = new User;
    $_SESSION['error'] = $user->check_login_form($username, $password);
    if(isset($_SESSION['error'])) {
        header('Location:../login.php');
    } else {
        $user->activate_and_login($token);
        if(isset($_SESSION['error'])) {
            header('Location:../activation.php');
        } else {
            header('Location:../home.php');
        }
    }
}

// Log user in
if(isset($_GET['login'])) {
    session_start();
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $user = new User;
    $_SESSION['error'] = $user->check_login_form($username, $password);
    if(isset($_SESSION['error'])) {
        header('Location:../login.php');
    } else {
        $user->login();
        if(isset($_SESSION['error'])) {
            header('Location:../login.php');
        } else {
            header('Location:../home.php');
        }
    }
}

// Log user out
if(isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header('Location:../home.php');
}

// Send reset link
if(isset($_GET['reset_link'])) {
    $username = htmlspecialchars($_POST['username']);
    $user = new User;
    $_SESSION['error'] = $user->check_reset_link_form($username);
    if(isset($_SESSION['error'])) {
        header('Location:../resetPsswd.php');
    } else {
        $user->send_reset_link($username);
        header('Location:../home.php');
    }
}

// Reset user password
if(isset($_GET['reset_pwd'])) {
    $token = $_POST['token'];
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $user = new User;
    $_SESSION['error'] = $user->check_reset_password_form($username, $password, $password_confirmation, $token);
    if(isset($_SESSION['error'])) {
        header('Location:../login.php');
    } else {
        $user->reset_and_login($token,$password);
        header('Location:../home.php');
    }
}

// Modify user informations
if(isset($_GET['modify_user'])) {
    session_start();
    $username = !empty($_POST['username']) ? htmlspecialchars($_POST['username']) : $_SESSION['username'];
    $email = !empty($_POST['email']) ? htmlspecialchars($_POST['email']) : $_SESSION['email'];
    $notifications = isset($_POST['notifications']) ? "1" : "0";
    $user = new User;
    $_SESSION['error'] = $user->check_user_modif_form($username, $email,$notifications);
    if(isset($_SESSION['error'])) {
        header('Location:../account.php');
    } else {
        $user->modify_user($_SESSION['userid']);
        header('Location:../account.php');
    }

}

if(isset($_GET['modify_pwd'])) {
    session_start();
    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $user = new User;
    $_SESSION['error'] = $user->check_reset_password2_form($_SESSION['userid'],$old_password,$password,$password_confirmation);
    if(isset($_SESSION['error'])) {
        header('Location:../account.php');
    } else {
        $user->reset_password($_SESSION['userid']);
        header('Location:../account.php');
    }
}
