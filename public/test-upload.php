<?php
/**
 * Fichier de test pour vérifier la configuration des uploads
 * SUPPRIMEZ CE FICHIER APRÈS LES TESTS !
 */

echo "<h1>Test de configuration des uploads</h1>";

// Vérifier les limites PHP
echo "<h2>Limites PHP actuelles :</h2>";
echo "<ul>";
echo "<li><strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>post_max_size:</strong> " . ini_get('post_max_size') . "</li>";
echo "<li><strong>max_execution_time:</strong> " . ini_get('max_execution_time') . "</li>";
echo "<li><strong>max_input_time:</strong> " . ini_get('max_input_time') . "</li>";
echo "<li><strong>memory_limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>max_file_uploads:</strong> " . ini_get('max_file_uploads') . "</li>";
echo "</ul>";

// Vérifier si les modules Apache sont chargés
echo "<h2>Modules Apache :</h2>";
echo "<ul>";
echo "<li><strong>mod_php:</strong> " . (extension_loaded('php') ? 'Chargé' : 'Non chargé') . "</li>";
echo "<li><strong>mod_rewrite:</strong> " . (function_exists('apache_get_modules') ? (in_array('mod_rewrite', apache_get_modules()) ? 'Chargé' : 'Non chargé') : 'Impossible de vérifier') . "</li>";
echo "</ul>";

// Test d'upload simple
echo "<h2>Test d'upload :</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    echo "<p><strong>Fichier reçu :</strong></p>";
    echo "<ul>";
    echo "<li>Nom : " . $file['name'] . "</li>";
    echo "<li>Taille : " . $file['size'] . " bytes (" . round($file['size'] / 1024 / 1024, 2) . " MB)</li>";
    echo "<li>Type : " . $file['type'] . "</li>";
    echo "<li>Erreur : " . $file['error'] . "</li>";
    echo "</ul>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        echo "<p style='color: green;'>✅ Upload réussi !</p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur d'upload : " . $file['error'] . "</p>";
    }
} else {
    echo "<form method='POST' enctype='multipart/form-data'>";
    echo "<p>Sélectionnez un fichier pour tester l'upload :</p>";
    echo "<input type='file' name='test_file' required>";
    echo "<button type='submit'>Tester l'upload</button>";
    echo "</form>";
}

// Vérifier les permissions du dossier storage
echo "<h2>Permissions du dossier storage :</h2>";
$storagePath = __DIR__ . '/../storage';
if (is_dir($storagePath)) {
    echo "<ul>";
    echo "<li><strong>Chemin:</strong> " . $storagePath . "</li>";
    echo "<li><strong>Existe:</strong> " . (is_dir($storagePath) ? 'Oui' : 'Non') . "</li>";
    echo "<li><strong>Lisible:</strong> " . (is_readable($storagePath) ? 'Oui' : 'Non') . "</li>";
    echo "<li><strong>Écrivable:</strong> " . (is_writable($storagePath) ? 'Oui' : 'Non') . "</li>";
    echo "<li><strong>Permissions:</strong> " . substr(sprintf('%o', fileperms($storagePath)), -4) . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Dossier storage non trouvé</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT : Supprimez ce fichier après les tests !</strong></p>";
?>
