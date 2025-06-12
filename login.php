<?php
require("includes/db.php");
include("includes/logs.php");

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['mot_de_passe'])) {
            session_start();
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            log_action("Connexion réussie");
            header("Location: index.php");
            exit();
        } else {
            $msg = "Mot de passe incorrect.";
            log_action("(Echec) Tentative de connexion avec mot de passe incorrect pour $user[email]");
        }
    } else {
        $msg = "Utilisateur introuvable.";
        log_action("(Echec) Tentative de connexion avec un email inexistant");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-sm" style="width: 22rem;">
        <h3 class="text-center mb-3">Connexion</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                    title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.">
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            <?php if (isset($msg)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </form>
        <p class="text-center mt-2"><a href="doclist.php">Accéder en invité</a><br><a href="register.php">Créer un compte</a></p>
    </div>
</body>
</html>