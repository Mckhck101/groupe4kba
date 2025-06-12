<?php
require("includes/db.php");
include("includes/logs.php");

session_start();
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = 200;
}

$orderby = "d.date_upload DESC";
if (isset($_GET['filtre']) && $_GET['filtre'] === 'true') {
    $orderby = "d.categorie ASC, d.date_upload DESC";
    $stmt = $pdo->prepare("SELECT d.* FROM documents d JOIN partage p ON d.id = p.document_id WHERE p.user_id = ? OR p.user_id = 200 ORDER BY $orderby");
    $stmt->execute([$user_id]);
    $documents = $stmt->fetchAll();
} elseif (isset($_GET['admin']) && $_GET['admin'] === 'true' && $user_id=745) {
    $stmt = $pdo->prepare("SELECT * FROM documents ORDER BY date_upload DESC");
    $stmt->execute();
    $documents = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT d.* FROM documents d JOIN partage p ON d.id = p.document_id WHERE p.user_id = ? OR p.user_id = 200 ORDER BY d.date_upload DESC");
    $stmt->execute([$user_id]);
    $documents = $stmt->fetchAll();
}
log_action("Consulte la liste des documents");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des documents</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 40px auto;
            max-width: 1200px;
            justify-content: center;
        }
        .card-doc {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            width: 200px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.2s;
        }
        .card-doc:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .card-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #eaeaea;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .card-img img {
            max-width: 100%;
            max-height: 100%;
        }
        .card-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 6px;
            text-align: center;
        }
        .card-category {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 8px;
        }
        .card-date {
            font-size: 0.85em;
            color: #aaa;
        }
        .card-link {
            text-decoration: none;
            color: #3498db;
            margin-top: 10px;
            font-size: 0.95em;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Aperçu de vos documents</h1>
        <div class="mb-4 text-center">
            <a href="upload.php" class="btn btn-primary">
                Ajouter un document
            </a>
        </div>
        <div class="container-cards">
            <?php foreach ($documents as $doc): ?>
                <div class="card-doc">
                    <div class="card-img">
                        <?php
                        $ext = strtolower(pathinfo($doc['chemin'], PATHINFO_EXTENSION));
                        if ($ext === 'pdf') {
                            echo '<img src="icon/pdf.svg" alt="PDF" width="48">';
                        } else {
                            echo '<img src="icon/file.svg" alt="Fichier" width="48">';
                        }
                        ?>
                    </div>
                    <div class="card-title"><?= htmlspecialchars($doc['nom']) ?></div>
                    <div class="card-category"><?= htmlspecialchars($doc['categorie']) ?></div>
                    <div class="card-date"><?= date('d/m/Y', strtotime($doc['date_upload'])) ?></div>
                    <?php
                        // Récupérer le nom de l'uploader
                            $uploaderStmt = $pdo->prepare("SELECT nom FROM users WHERE id = ?");
                            $uploaderStmt->execute([$doc['user_id']]);
                            $uploader = $uploaderStmt->fetch();
                            if ($uploader) {
                                echo '<div class="card-uploader">Ajouté par : ' . htmlspecialchars($uploader['nom']) . '</div>';
                            }
                    ?>
                    <a class="card-link" href="<?= htmlspecialchars($doc['chemin']) ?>" target="_blank">Ouvrir</a>
                </div>
            <?php endforeach; ?>
            <?php if (empty($documents)): ?>
                <div class="alert alert-info">Aucun document trouvé.</div>
            <?php endif; ?>
        </div>
        <div class="mb-4 text-center">
            <a href="logout.php" class="btn btn-primary">
                Deconnexion
            </a>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
