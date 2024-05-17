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
    <div class="filters">
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
    </div>
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
        $sql = "SELECT v.immatriculation, v.image, v.kilometrage, c.categorie, m.marque, mo.modele
                FROM voitures v
                JOIN categories c ON v.id_categorie = c.id_categorie
                JOIN marques m ON v.id_marque = m.id_marque
                JOIN modeles mo ON v.id_modele = mo.id_modele";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='vehicle-card' data-immatriculation='" . $row["immatriculation"] . "' data-kilometrage='" . $row["kilometrage"] . "' data-categorie='" . $row["categorie"] . "' onclick='redirectToDetails(this)'>";
                echo "<img src='" . $row["image"] . "' alt='" . $row["modele"] . "'>";
                echo "<h2>" . $row["marque"] . " " . $row["modele"] . "</h2>";
                echo "<p>Immatriculation: " . $row["immatriculation"] . "</p>";
                echo "<p>Kilométrage: " . $row["kilometrage"] . " km</p>";
                echo "<p>Catégorie: " . $row["categorie"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun véhicule disponible</p>";
        }
        $conn->close();
        ?>
    </div>
    <script src="JS/script.js"></script>
</body>
</html>
