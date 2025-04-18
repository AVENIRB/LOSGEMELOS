<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

date_default_timezone_set('Africa/Algiers'); // <- ICI on fixe le fuseau horaire

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['table']) && isset($data['produits'])) {
    $table = $data['table'];
    $produits = $data['produits'];

    $commande = "Commande de la Table: " . $table . "\n";
    $commande .= "----------------------------------------------------\n";
    $date = date('d/m/Y H:i:s');
    $total = 0;

    foreach ($produits as $produit) {
        $commande .= "date:" . $date . "\n";
        $commande .= "Table: " . $table . "\n";
        $commande .= "Produit: " . $produit['produit'] . "\n";
        $commande .= "Quantité: " . $produit['quantite'] . "\n";
        $commande .= "Prix Unitaire: " . number_format($produit['prix'], 2) . "\n";
        $commande .= "Sous-total: " . number_format($produit['sousTotal'], 2) . "\n";
        $commande .= "----------------------------------------------------\n";
        $total += $produit['sousTotal'];
    }

    $commande .= "Total: " . number_format($total, 2) . "\n";
    $commande .= "----------------------------------------------------\n";

    $file = 'commandes.txt';
    $fileHandle = fopen($file, 'a');
    if ($fileHandle === false) {
        echo json_encode(['status' => 'error', 'message' => 'Impossible d\'ouvrir le fichier de commandes.']);
        exit;
    }

    fwrite($fileHandle, $commande);
    fclose($fileHandle);

    echo json_encode(['status' => 'success', 'message' => 'Commande enregistrée avec succès.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données de commande invalides.']);
}
?>
