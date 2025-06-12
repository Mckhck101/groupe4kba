<?php
require('db.php');

/**
 * Enregistre une action dans la table logs.
 * 
 * @param string       $action    Description de l'action
 * @param object|array|null $document Document optionnel (doit contenir l'id)
 * 
 * @return bool True si succès, false sinon
 */
function log_action1(string $action, $document = null): bool {
    global $pdo;

    // Récupérer l'id utilisateur depuis la session ou mettre 200 par défaut
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 200;

    // Récupérer l'id document (optionnel)
    $document_id = null;
    if ($document !== null) {
        if (is_object($document) && isset($document->id)) {
            $document_id = $document->id;
        } elseif (is_array($document) && isset($document['id'])) {
            $document_id = $document['id'];
        }
    }

    try {
        $sql = "INSERT INTO logs (user_id, actionU, document_id) VALUES (:user_id, :actionU, :document_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':actionU', $action, PDO::PARAM_STR);

        if ($document_id !== null) {
            $stmt->bindParam(':document_id', $document_id, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':document_id', null, PDO::PARAM_NULL);
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion du log : " . $e->getMessage());
        return false;
    }
}

// Alias pour compatibilité
/**
 * Alias de la fonction log_action1 pour compatibilité.
 * 
 * @param string       $action    Description de l'action
 * @param object|array|null $document Document optionnel (doit contenir l'id)
 * 
 * @return bool True si succès, false sinon
 */
function log_action($action, $document = null) {
    return log_action1($action, $document);
}
