<?php
require('db.php');
include('includes/logs.php');

// Fonction pour extraire le texte d'un document selon son type
function extractTextFromDocument($documentId) {
    global $pdo; // Utilisation de la connexion PDO comme dans votre exemple

    // Récupérer les informations du document
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->execute([$documentId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$document) return false;

    $filePath = $document['file_path'];
    $mimeType = $document['mime_type'];
    $extractedText = '';

    try {
        switch ($mimeType) {
            case 'text/plain':
                $extractedText = file_get_contents($filePath);
                break;

            case 'application/pdf':
                $extractedText = extractTextFromPDF($filePath);
                break;

            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $extractedText = extractTextFromWord($filePath);
                break;

            case 'image/jpeg':
            case 'image/png':
                $extractedText = extractTextFromImage($filePath);
                break;

            default:
                $extractedText = 'Type de fichier non supporté pour l\'extraction de texte.';
        }

        // Nettoyer le texte
        $extractedText = cleanText($extractedText);

        // Mettre à jour la base de données
        $updateStmt = $pdo->prepare("UPDATE documents SET extracted_text = ? WHERE id = ?");
        $updateStmt->execute([$extractedText, $documentId]);

        return $extractedText;

    } catch (Exception $e) {
        error_log("Erreur extraction texte: " . $e->getMessage());
        return false;
    }
}

function extractTextFromPDF($filePath) {
    // Solution simple avec pdftotext (nécessite poppler-utils sur le serveur)
    if (shell_exec('which pdftotext')) {
        $output = shell_exec("pdftotext '$filePath' -");
        return $output ?: 'Impossible d\'extraire le texte du PDF.';
    }
    
    // Alternative : utiliser une bibliothèque PHP comme tcpdf ou fpdi
    // Pour ce MVP, on retourne un message
    return 'Extraction PDF nécessite pdftotext. Contenu non extrait.';
}

function extractTextFromWord($filePath) {
    $text = '';
    
    if (pathinfo($filePath, PATHINFO_EXTENSION) === 'docx') {
        // Extraction pour .docx (format ZIP)
        $zip = new ZipArchive();
        if ($zip->open($filePath) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            if ($xml) {
                $dom = new DOMDocument();
                $dom->loadXML($xml);
                $text = $dom->textContent;
            }
            $zip->close();
        }
    } else {
        // Pour .doc, solution plus complexe nécessitant des bibliothèques spécialisées
        $text = 'Extraction .doc nécessite une bibliothèque spécialisée.';
    }
    
    return $text ?: 'Impossible d\'extraire le texte du document Word.';
}

function extractTextFromImage($filePath) {
    // OCR avec Tesseract (nécessite tesseract installé sur le serveur)
    if (shell_exec('which tesseract')) {
        $output = shell_exec("tesseract '$filePath' stdout -l fra 2>/dev/null");
        return $output ?: 'Aucun texte détecté dans l\'image.';
    }
    
    return 'OCR nécessite Tesseract. Texte non extrait de l\'image.';
}

function cleanText($text) {
    // Supprimer les caractères indésirables
    $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', ' ', $text);
    
    // Normaliser les espaces
    $text = preg_replace('/\s+/', ' ', $text);
    
    // Supprimer les espaces en début et fin
    $text = trim($text);
    
    return $text;
}

// Fonction pour extraire les mots-clés du texte
function extractKeywords($text, $limit = 10) {
    // Mots vides français
    $stopWords = [
        'le', 'de', 'un', 'à', 'être', 'et', 'en', 'avoir', 'que', 'pour',
        'dans', 'ce', 'il', 'une', 'sur', 'avec', 'ne', 'se', 'pas', 'tout',
        'plus', 'par', 'grand', 'mais', 'me', 'bien', 'te', 'si', 'très',
        'comme', 'même', 'faire', 'était', 'lui', 'dit', 'elle', 'nous',
        'vous', 'ils', 'elles', 'ces', 'cette', 'celui', 'celle', 'ceux',
        'celles', 'mon', 'ton', 'son', 'ma', 'ta', 'sa', 'mes', 'tes', 'ses',
        'notre', 'votre', 'leur', 'nos', 'vos', 'leurs', 'du', 'des', 'au',
        'aux', 'où', 'qui', 'quoi', 'dont', 'quand', 'comment', 'pourquoi'
    ];
    
    // Convertir en minuscules et séparer les mots
    $words = preg_split('/[^\p{L}]+/u', mb_strtolower($text, 'UTF-8'));
    
    // Filtrer les mots vides et les mots trop courts
    $words = array_filter($words, function($word) use ($stopWords) {
        return strlen($word) > 3 && !in_array($word, $stopWords);
    });
    
    // Compter les occurrences
    $wordCount = array_count_values($words);
    arsort($wordCount);
    
    // Retourner les mots-clés les plus fréquents
    return array_slice(array_keys($wordCount), 0, $limit);
}
?>