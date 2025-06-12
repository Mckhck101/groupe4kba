# Projet Groupe 4 KBA

Gestion de documents avec authentification, upload, classification automatique, historique et sauvegarde.

## Structure du projet

```
groupe4_kba/
├── index.php           # Page d’accueil, redirige selon connexion
├── login.php           # Formulaire de connexion
├── register.php        # Formulaire d’inscription
├── dashboard.php       # Tableau de bord (documents utilisateur)
├── upload.php          # Formulaire d’envoi de document
├── groupe4_kba.sql     # Base de données du projet
├── doclist.php         # Liste des documents utilisateur
├── history.php         # Historique des actions utilisateur
├── logout.php          # Déconnexion
├── users.php           # Gestion des utilisateurs
│
├── includes/
│   ├── db.php              # Connexion à la base de données
│   ├── extract_text.php    # Extraction de texte des documents
│   ├── classifier.php      # Classification automatique
│   └── logs.php            # Gestion des logs
│
├── icon/              # Icônes (*.svg)
├── documents/         # Documents uploadés
├── bootstrap/         # Fichiers Bootstrap
```

## Fonctionnalités principales

- Authentification avec gestion des rôles
- Upload de documents
- Classification automatique par mot-clé
- Liste des documents par utilisateur
- Historique d'accès et modifications
- Sauvegarde manuelle (export ZIP)
- Monitoring simple (qui a consulté quoi)

## Répartition des tâches

| Membre        | Module / Rôle                        | Tâches principales                                                                 |
|---------------|--------------------------------------|------------------------------------------------------------------------------------|
| Mackedzoa     | Lead dev, structure, BD, intégration | Structure, base de données, classement auto, supervision, intégration finale        |
| Christelle    | Frontend, pages                      | Maquettage HTML/CSS, accueil, upload, affichage documents                          |
| Issa etoke    | Authentification                     | Login/register, gestion sessions, vérification rôles                               |
| Bossogeno     | Upload, stockage                     | Traitement upload, stockage fichiers, insertion en base                            |
| Zambo Stephane| Classification automatique           | Extraction texte, script de classement, liaison upload                             |
| Prescillia    | Monitoring des accès                 | Table logs, ajout log consultation/upload, affichage historique                    |
| Mackedzoa     | Backup & restauration                | Bouton “Télécharger tout”, script backup ZIP, affichage date dernier backup        |
| Mackedzoa     | Encadrement et suivi                 | Suivi modules, récupération, tests intégration, rapport/démo                       |

## Planning conseillé

| Journée | Objectif                            |
| ------- | ----------------------------------- |
| 1       | Création base + page login/register |
| 2       | Upload + classification             |
| 3       | Monitoring + affichage documents    |
| 4       | Backup + finalisation + tests       |
