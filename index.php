<?php
include("includes/logs.php");
session_start();

if (!isset($_SESSION['role'])) {
    log_action("Tentative d'accès à index.php sans connexion");
    header('Location: login.php');
    exit();
}

if ($_SESSION['role'] === 'admin') {
    log_action("Connexion réussie d'un administrateur");
    header('Location: dashboard.php');
    exit();
} else if ($_SESSION['role'] === 'user'){
    log_action("Connexion réussie d'un utilisateur");
    header('Location: upload.php');
    exit();
} else{
    log_action("Connexion réussie d'un invité");
    header('Location: doclist.php');
    exit();
}
?>