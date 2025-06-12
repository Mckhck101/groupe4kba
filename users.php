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

// Récupérer les utilisateurs et le nombre de documents uploadés par chacun
$sql = "
    SELECT 
        u.id, u.nom, u.email, u.role, 
        COUNT(d.id) AS nb_documents
    FROM users u
    LEFT JOIN documents d ON u.id = d.user_id
    GROUP BY u.id, u.nom, u.email, u.role
    ORDER BY u.id ASC
";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
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
        <h1 class="mb-4">Liste des utilisateurs</h1>
        <div class="table-responsive">
            <form method="get" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou email" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
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
                        <a href="register.php" class="btn btn-success">Ajouter un utilisateur</a>
                    </div>
                    <div class="col-auto">
                        <a href="history.php" class="btn btn-info">Voir l'historique</a>
                    </div>
                </div>
            </form>
            <?php
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            if ($search !== '') {
                $sql = "
                    SELECT 
                        u.id, u.nom, u.email, u.role, 
                        COUNT(d.id) AS nb_documents
                    FROM users u
                    LEFT JOIN documents d ON u.id = d.user_id
                    WHERE u.nom LIKE :search OR u.email LIKE :search
                    GROUP BY u.id, u.nom, u.email, u.role
                    ORDER BY u.id ASC
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['search' => '%' . $search . '%']);
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            ?>
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Nb Documents Uploadés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['nb_documents']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>