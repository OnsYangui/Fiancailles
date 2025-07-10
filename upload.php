<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'fiancaille';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connexion échouée : " . $conn->connect_error);

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
  $imageData = file_get_contents($_FILES['photo']['tmp_name']);
  $stmt = $conn->prepare("INSERT INTO photos (image, nom_fichier, type) VALUES (?, ?, ?)");
  $stmt->bind_param("bss", $imageData, $_FILES['photo']['name'], $_FILES['photo']['type']);

  // Envoi du fichier binaire (image) dans la base
  $stmt->send_long_data(0, $imageData);
  $stmt->execute();
  $stmt->close();

  echo "✅ Photo enregistrée dans la base de données ! <a href='album.php'>Voir l'album</a>";
} else {
  echo "Aucune photo reçue ou une erreur est survenue.";
}

$conn->close();
?>
