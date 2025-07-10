<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'fiancaille';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
  die("Connexion échouée : " . $conn->connect_error);

$res = $conn->query("SELECT * FROM photos ORDER BY date_ajout DESC");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Album de fiançailles</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff0f5;
      padding: 20px;
      margin: 0;
      text-align: center;
    }

    h2 {
      color: #d36b8d;
      font-family: Georgia, serif;
      margin-bottom: 20px;
    }

    .gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    .photo-container {
      max-width: 300px;
      max-height: 300px;
      overflow: hidden;
      cursor: pointer;
      position: relative;
      transition: transform 0.2s;
    }

    .photo-container:hover {
      transform: scale(1.05);
    }

    .photo-container img {
      width: 100%;
      height: auto;
      object-fit: contain;
      display: block;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #d36b8d;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <h2>Album de fiançailles</h2>
  <div class="gallery" id="gallery"></div>
  <div style="display:flex; flex-wrap:wrap;">
    <?php while ($row = $res->fetch_assoc()): ?>
      <img src="<?= htmlspecialchars($row['chemin']) ?>" class="photo">
    <?php endwhile; ?>
  </div>
  <a href="index.html">➕ Ajouter une autre photo</a>

  <script>
    const gallery = document.getElementById("gallery");
    let photos = JSON.parse(localStorage.getItem("photos") || "[]");

    function renderGallery() {
      gallery.innerHTML = "";
      if (photos.length === 0) {
        gallery.innerHTML = "<p>Aucune photo pour le moment.</p>";
        return;
      }

      photos.forEach((src, index) => {
        const container = document.createElement("div");
        container.classList.add("photo-container");

        const img = document.createElement("img");
        img.src = src;
        img.title = "Cliquez pour supprimer cette photo";

        container.appendChild(img);
        gallery.appendChild(container);

        container.addEventListener("click", () => {
          if (confirm("Voulez-vous supprimer cette photo ?")) {
            photos.splice(index, 1);
            localStorage.setItem("photos", JSON.stringify(photos));
            container.remove();

            if (photos.length === 0) {
              gallery.innerHTML = "<p>Aucune photo pour le moment.</p>";
            }
          }
        });
      });
    }

    renderGallery();
  </script>
</body>

</html>
<?php $conn->close(); ?>