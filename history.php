<?php

require("includes/db.php");

session_start();
if (isset($_SESSION['id']) && $_SESSION['id'] == 745) {
    $user_id = $_SESSION['id'];
} else {
    header("Location: login.php");
    log_action("Tentative d'accès au dashboard sans connexion");
    exit;
}

try {
    // Récupérer tous les logs
    $stmt = $pdo->query("SELECT * FROM logs ORDER BY dateL DESC");
    $logs = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des logs</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        table {
            margin-top: 20px;
        }
        th, td {
            text-align: center;
        }
        th {
            background-color: #e9ecef;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }
        .table-striped tbody tr:hover {
            background-color: #e9ecef;
        }
        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            font-weight: bold;
        }
        .table td {
            font-size: 0.9rem;
        }
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #6c757d;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background-color: #f8f9fa;
        }
    </style>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Historique des logs</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <form method="get" class="mb-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par action, utilisateur ou document..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </div>
                        <div class="col-auto">
                        <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
                    </div>
                    <div class="col-auto">
                        <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
                    </div>
                    <div class="col-auto">
                    </div>
                </form>
                <?php
                if (isset($_GET['search']) && trim($_GET['search']) !== '') {
                    $search = '%' . trim($_GET['search']) . '%';
                    $stmt = $pdo->prepare("SELECT * FROM logs WHERE actionU LIKE ? OR user_id LIKE ? OR document_id LIKE ? ORDER BY dateL DESC");
                    $stmt->execute([$search, $search, $search]);
                    $logs = $stmt->fetchAll();
                }
                ?>
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>ID Utilisateur</th>
                        <th>Action</th>
                        <th>ID Document</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']) ?></td>
                            <td><?= htmlspecialchars($log['user_id']) ?></td>
                            <td><?= htmlspecialchars($log['actionU']) ?></td>
                            <td><?= htmlspecialchars($log['document_id'] ?? 'Aucun document concerné sur cette action') ?></td>
                            <td><?= htmlspecialchars($log['dateL']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>