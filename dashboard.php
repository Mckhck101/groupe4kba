<?php
session_start();
if (isset($_SESSION['id']) && $_SESSION['id'] == 745) {
    $user_id = $_SESSION['id'];
} else {
    header("Location: login.php");
    log_action("Tentative d'accès au dashboard sans connexion");
    exit;
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Groupe 4 KBA | Tableau de Bord</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Groupe 4 KBA | Tableau de Bord" />
    <meta name="description" content="Tableau de bord de l'application de gestion de documents pour le Groupe 4 KBA." />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css" />
    <link rel="stylesheet" href="style/dashboard.css">
  </head>
  <body>
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand d-lg-none" href="dashboard.php">KBA Docs</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
              <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-house-door-fill me-2"></i>Accueil</a></li>
              <li class="nav-item"><a class="nav-link" href="upload.php"><i class="bi bi-cloud-arrow-up-fill me-2"></i>Uploader</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="icon/profil.svg" class="rounded-circle me-1" alt="User Image" style="height: 25px; width: 25px; object-fit: cover;"/>
                  <span class="d-none d-md-inline">Utilisateur KBA</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                  <li class="dropdown-item user-header text-bg-primary text-center py-3">
                    <img src="icon/profil.svg" class="rounded-circle mb-2" alt="User Image" style="height: 90px; width: 90px; object-fit: cover;"/>
                    <?php
                    // Connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=groupe4_kba;charset=utf8', 'root', '');

                    // Récupérer les infos utilisateur depuis la table `users`
                    $stmt = $pdo->prepare("SELECT nom, role FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                      $nom = htmlspecialchars($user['nom']);
                      $role = htmlspecialchars($user['role']);
                      echo "<p class=\"mb-0\">$nom - $role<small> Gérant de la BD </small></p>";
                    } else {
                      echo "<p class=\"mb-0\">Utilisateur inconnu</p>";
                    }
                    ?>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a href="logout.php" class="dropdown-item btn btn-default btn-flat">Déconnexion</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="page-content-wrapper">
        <aside class="app-sidebar">
          <div class="sidebar-brand">
        <a href="dashboard.php" class="brand-link">
          <img src="Projet2/Projet2/preview.png" alt="" class="brand-image"/>
          <span class="brand-text">KBA Docs</span>
        </a>
          </div>
          <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="sidebar-menu">
            <li class="nav-item"><a href="index.php" class="nav-link active"><i class="nav-icon bi bi-speedometer"></i><p>Tableau de Bord</p></a></li>
            <li class="nav-item"><a href="doclist.php" class="nav-link"><i class="nav-icon bi bi-folder-fill"></i><p>Mes Documents</p></a></li>
            <li class="nav-item"><a href="upload.php" class="nav-link"><i class="nav-icon bi bi-cloud-arrow-up-fill"></i><p>Uploader un Document</p></a></li>
            <li class="nav-item"><a href="history.php" class="nav-link"><i class="nav-icon bi bi-clock-history"></i><p>Historique des Actions</p></a></li>
            <li class="nav-header">ADMINISTRATION</li>
            <li class="nav-item"><a href="users.php" class="nav-link"><i class="nav-icon bi bi-people-fill"></i><p>Gestion des Utilisateurs</p></a></li>
            <li class="nav-item"><a href="backup.html" class="nav-link"><i class="nav-icon bi bi-database-fill-up"></i><p>Sauvegarde & Restauration</p></a></li>
            <li class="nav-header">PARAMÈTRES</li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="nav-icon bi bi-box-arrow-right"></i><p>Déconnexion</p></a></li>
          </ul>
        </nav>
          </div>
        </aside>

        <main class="app-main">
          <div class="app-content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
          <h1 class="m-0">Tableau de Bord</h1>
            </div>
            <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
            <li class="breadcrumb-item active">Tableau de Bord</li>
          </ol>
            </div>
          </div>
        </div>
          </div>

          <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
          <div class="small-box bg-primary text-white">
            <div class="inner">
              <h3>
            <span id="totalDocumentsCount">
              <?php
              // Connexion à la base de données
              $pdo = new PDO('mysql:host=localhost;dbname=groupe4_kba;charset=utf8', 'root', '');
              $stmt = $pdo->query("SELECT COUNT(*) FROM documents");
              echo $stmt->fetchColumn();
              ?>
            </span>
              </h3>
              <p>Documents Totaux</p>
            </div>
            <div class="icon"><i class="bi bi-folder-fill"></i></div>
            <a href="doclist.php" class="small-box-footer">Voir tous les documents <i class="bi bi-arrow-right"></i></a>
          </div>
            </div>
            <div class="col-lg-3 col-6">
          <div class="small-box bg-success text-white">
            <div class="inner">
              <h3>
            <span id="classifiedDocumentsCount">
              <?php
              $stmt = $pdo->query("SELECT COUNT(*) FROM documents WHERE categorie IS NOT NULL AND categorie != ''");
              echo $stmt->fetchColumn();
              ?>
            </span>
              </h3>
              <p>Documents Classifiés</p>
            </div>
            <div class="icon"><i class="bi bi-tag-fill"></i></div>
            <a href="doclist.php?filtre=true" class="small-box-footer">Voir les catégories <i class="bi bi-arrow-right"></i></a>
          </div>
            </div>
            <div class="col-lg-3 col-6">
          <div class="small-box bg-warning text-dark">
            <div class="inner">
              <h3>
            <span id="lastUploadDate">
              <?php
              $stmt = $pdo->query("SELECT DATE_FORMAT(MAX(date_upload), '%d/%m/%Y') FROM documents");
              echo $stmt->fetchColumn();
              ?>
            </span>
              </h3>
              <p>Dernier Upload</p>
            </div>
            <div class="icon"><i class="bi bi-upload"></i></div>
            <a href="upload.php" class="small-box-footer">Uploader un nouveau <i class="bi bi-arrow-right"></i></a>
          </div>
            </div>
            <div class="col-lg-3 col-6">
          <div class="small-box bg-info text-white">
            <div class="inner">
              <h3>
            <span id="storageUsed">
              <?php
              /*$stmt = $pdo->query("SELECT SUM(taille) FROM documents");
              $size = $stmt->fetchColumn();
              if ($size >= 1073741824) {
                echo round($size / 1073741824, 2) . ' GB';
              } elseif ($size >= 1048576) {
                echo round($size / 1048576, 2) . ' MB';
              } elseif ($size >= 1024) {
                echo round($size / 1024, 2) . ' KB';
              } else {
                echo $size . ' B';
              }*/ echo '-2048 Mo';
              ?>
            </span>
              </h3>
              <p>Espace Utilisé</p>
            </div>
            <div class="icon"><i class="bi bi-hdd-fill"></i></div>
            <a href="doclist.php?admin=true" class="small-box-footer">Gérer l'espace <i class="bi bi-arrow-right"></i></a>
          </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Mes Documents Récents</h3>
              <div class="card-tools">
            <a href="upload.php" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i> Ajouter un Document</a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom du Document</th>
                <th>Catégorie</th>
                <th>Date d'Upload</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="recentDocumentsTableBody">
              <?php
              $stmt = $pdo->prepare("SELECT id, nom, categorie, date_upload FROM documents ORDER BY date_upload DESC LIMIT 10");
              $stmt->execute();
              $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
              if (count($documents) > 0) {
                foreach ($documents as $doc) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars($doc['id']) . '</td>';
              echo '<td>' . htmlspecialchars($doc['nom']) . '</td>';
              echo '<td>' . htmlspecialchars($doc['categorie']) . '</td>';
              echo '<td>' . date('d/m/Y', strtotime($doc['date_upload'])) . '</td>';
              echo '<td>
                <a href="view.php?id=' . urlencode($doc['id']) . '" class="btn btn-sm btn-info" title="Voir" aria-label="Voir"><i class="bi bi-eye-fill"></i></a>
                <a href="download.php?id=' . urlencode($doc['id']) . '" class="btn btn-sm btn-success" title="Télécharger" aria-label="Télécharger"><i class="bi bi-download"></i></a>
                <a href="delete.php?id=' . urlencode($doc['id']) . '" class="btn btn-sm btn-danger" title="Supprimer" aria-label="Supprimer" onclick="return confirm(\'Supprimer ce document ?\')"><i class="bi bi-trash-fill"></i></a>
              </td>';
              echo '</tr>';
                }
              } else {
                echo '<tr><td colspan="5" class="text-center">Aucun document trouvé.</td></tr>';
              }
              ?>
            </tbody>
              </table>
            </div>
          </div>
            </div>
          </div>
        </div>
          </div>
        </main>
      </div>

      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Version 1.0</div>
        <strong>Copyright &copy; 2025 <a href="https://groupe4-kba.com">Groupe 4 KBA</a>.</strong> Tous droits réservés.
      </footer>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
  </body>
</html>