<?php
require("includes/db.php");
include("includes/logs.php");

session_start();
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "documents/";
    $allowed_types = ['application/pdf'];

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (
        isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK &&
        isset($_POST['categorie']) && !empty($_POST['categorie'])
    ) {
        $file_tmp = $_FILES['document']['tmp_name'];
        $file_name = basename($_FILES['document']['name']);
        $file_type = mime_content_type($file_tmp);
        $categorie = htmlspecialchars($_POST['categorie']);
        $emails = isset($_POST['emails']) ? $_POST['emails'] : '';

        if (in_array($file_type, $allowed_types)) {
            $target_file = $target_dir . uniqid() . '_' . $file_name;
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Insertion en base de données
                $stmt = $pdo->prepare("INSERT INTO documents (user_id, nom, chemin, categorie) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $file_name, $target_file, $categorie]);
                $document_id = $pdo->lastInsertId();

                // Partage avec soi-même
                $stmtP = $pdo->prepare("INSERT INTO partage (user_id, document_id) VALUES (?, ?)");
                $stmtP->execute([$user_id, $document_id]);

                // Partage avec les emails saisis
                if (!empty($emails)) {
                    $emailList = array_map('trim', explode(',', $emails));
                    $emailList = array_filter($emailList); // remove empty
                    foreach ($emailList as $email) {
                        // Chercher l'id de l'utilisateur correspondant à l'email
                        $stmtU = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                        $stmtU->execute([$email]);
                        $row = $stmtU->fetch();
                        if ($row) {
                            $other_user_id = $row['id'];
                            // Eviter de dupliquer le partage avec soi-même
                            if ($other_user_id != $user_id) {
                                $stmtP->execute([$other_user_id, $document_id]);
                            }
                        }
                    }
                }

                if (isset($_POST['is_public']) && $_POST['is_public'] == '1') {
                    $stmtP->execute([200, $document_id]);
                }

                $message = '<div class="alert alert-success">✅ Document envoyé et partagé avec succès !</div>';
                log_action("(Creation) A upload un document", ['id' => $document_id]);
                header("location: doclist.php");
                exit();
            } else {
                $message = '<div class="alert alert-danger">❌ Erreur lors du transfert du fichier.</div>';
                log_action("(Echec) Mauvais transfert lors de l'upload un document");
            }
        } else {
            $message = '<div class="alert alert-warning">❌ Seuls les fichiers PDF sont autorisés.</div>';
            log_action("(Echec) Mauvais transfert de doc non-pdf");
        }
    } else {
        $message = '<div class="alert alert-danger">❌ Aucun fichier sélectionné, catégorie manquante ou erreur d’upload.</div>';
        log_action("(Echec) Mauvais transfert lors de l'upload un document sans fichier ou catégorie vide");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USend document</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-sm" style="width: 22rem;">
        <h3 class="text-center mb-3">Envoyer votre document PDF (2Go Max)</h3>
        <?php if (isset($message)) echo $message; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="document" class="form-label">Choisir un document PDF :</label>
                <input type="file" class="form-control" name="document" id="document" accept=".pdf" required>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie :</label>
                <input type="text" class="form-control" name="categorie" id="categorie" required>
            </div>
            <div class="mb-3">
                <label for="emails" class="form-label">Partager avec (emails séparés par des virgules) :</label>
                <input type="text" class="form-control" name="emails" id="emails" placeholder="ex: ami1@mail.com, ami2@mail.com">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_public" id="is_public" value="1">
                <label class="form-check-label" for="is_public">Rendre ce document public</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Uploader</button>
        </form>
        <p class="text-center mt-2"><a href="logout.php">Deconnexion</a><br><a href="doclist.php">Accéder aux docs</a></p>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
