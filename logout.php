<?php
include("includes/logs.php");
session_start();
session_unset();
session_destroy();
log_action("Deconnexion de l'utilisateur");
header("Location: login.php");
exit();
?>