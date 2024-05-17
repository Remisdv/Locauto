<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locauto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$immatriculation = $_GET['immatriculation'];

// Requête SQL pour récupérer les détails du véhicule
$sql = "SELECT v.immatriculation, v.image, v.kilometrage, c.categorie, m.marque, mo.modele, v.prix
        FROM voitures v
        JOIN categories c ON v.id_categorie = c.id_categorie
        JOIN marques m ON v.id_marque = m.id_marque
        JOIN modeles mo ON v.id_modele = mo.id_modele
        WHERE v.immatriculation = '$immatriculation'";

$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $vehicle = $result->fetch_assoc();
} else {
    echo "Véhicule non trouvé";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Véhicule</title>
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
    <div class="vehicle-details">
        <div class="vehicle-image">
            <img src="<?php echo $vehicle['image']; ?>" alt="<?php echo $vehicle['modele']; ?>">
        </div>
        <div class="vehicle-info">
            <h1><?php echo $vehicle['marque'] . " " . $vehicle['modele']; ?></h1>
            <p>Immatriculation: <?php echo $vehicle['immatriculation']; ?></p>
            <p>Kilométrage: <?php echo $vehicle['kilometrage']; ?> km</p>
            <p>Catégorie: <?php echo $vehicle['categorie']; ?></p>
            <p>Prix: <?php echo $vehicle['prix']; ?> €</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="reserve.php" method="POST">
                    <input type="hidden" name="immatriculation" value="<?php echo $vehicle['immatriculation']; ?>">
                    <label for="kilometres">Kilomètres:</label>
                    <input type="number" id="kilometres" name="kilometres" required>
                    <button type="submit">Réserver</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Connectez-vous</a> pour réserver ce véhicule.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
