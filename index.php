<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Véhicules Disponibles</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="#">Véhicules</a></li>
                <li><a href="#">Contact</a></li>
                <li class="menu-dropdown">
                    <a href="javascript:void(0)" class="dropbtn"><?php echo isset($_SESSION['user_id']) ? $_SESSION['username'] : 'Compte'; ?></a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                <a href="admin.php">Administration</a>
                            <?php endif; ?>
                            <a href="logout.php">Déconnexion</a>
                        <?php else: ?>
                            <a href="login.php">Connexion</a>
                            <a href="register.php">Inscription</a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="banner"></div>
    <div class="container">
        <section class="filters">
            <select id="searchOption">
                <option value="marque_modele">Marque et Modèle</option>
                <option value="immatriculation">Immatriculation</option>
            </select>
            <select id="categoryOption">
                <option value="all">Toutes les catégories</option>
                <option value="Economique">Economique</option>
                <option value="Standard">Standard</option>
                <option value="Luxe">Luxe</option>
            </select>
            <select id="sortOption">
                <option value="none">Trier par</option>
                <option value="kilometrage_asc">Kilométrage croissant</option>
                <option value="kilometrage_desc">Kilométrage décroissant</option>
            </select>
        </section>

        <section class="section2">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Rechercher...">
                <br>
                <br>
                <h1>Liste des Véhicules Disponibles</h1>
            </div>

            <div class="vehicles-container" id="vehiclesContainer">
                <?php
                $conn = new mysqli("localhost", "root", "", "locauto");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT v.immatriculation, c.categorie, m.marque, mo.modele, v.prix
                        FROM voitures v
                        JOIN categories c ON v.id_categorie = c.id_categorie
                        JOIN marques m ON v.id_marque = m.id_marque
                        JOIN modeles mo ON v.id_modele = mo.id_modele";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $immatriculation = $row["immatriculation"];
                        echo "<div class='vehicle-card' data-immatriculation='" . $row["immatriculation"] . "' onclick='redirectToDetails(this)'>";
                        // Fetch images for this vehicle
                        $sql_images = "SELECT image_url FROM images WHERE immatriculation = '$immatriculation'";
                        $result_images = $conn->query($sql_images);
                        if ($result_images->num_rows > 0) {
                            echo "<div class='image-slider'>";
                            while ($image_row = $result_images->fetch_assoc()) {
                                echo "<img src='" . $image_row["image_url"] . "' alt='" . $row["modele"] . "'>";
                            }
                            echo "<button class='prev' onclick='event.stopPropagation();changeImage(this, -1)'>❮</button>";
                            echo "<button class='next' onclick='event.stopPropagation();changeImage(this, 1)'>❯</button>";
                            echo "</div>";
                        }
                        echo "<h2>" . $row["marque"] . " " . $row["modele"] . "</h2>";
                        echo "<p>Prix par 100 km: " . $row["prix"] . " €</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun véhicule disponible</p>";
                }
                $conn->close();
                ?>
            </div>
        </section>
    </div>
    <script src="JS/script.js"></script>
</body>
</html>
