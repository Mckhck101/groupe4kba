<?php
require("includes/db.php");
include("includes/logs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $msg = "Cet email est déjà utilisé.";
        log_action("(Echec) Tentative d'inscription avec un email déjà utilisé", ['email' => $email]);
    } else {
        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'user')");
        if ($stmt->execute([$nom, $email, $hashedPassword])) {
            $msg = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            log_action("Inscription réussie $eamil");
            header("Location: login.php");
            exit();
        } else {
            $msg = "Erreur lors de l'inscription.";
            log_action("(Echec) Erreur lors de l'inscription");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-sm" style="width: 22rem;">
        <h3 class="text-center mb-3">Inscription</h3>
        <?php if (isset($msg)): ?>
            <div class="alert alert-danger"><?= $msg ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Créer un mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                    title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.">
            </div>
            <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            <?php if (isset($msg)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </form>
        <p class="text-center mt-2"><a href="login.php">Déjà un compte ? Se connecter</a></p>
    </div>
</body>
</html>