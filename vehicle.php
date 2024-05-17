<?php
// Configuration de la connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locauto";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer l'immatriculation du véhicule depuis l'URL
$immatriculation = $_GET['immatriculation'];

// Requête SQL pour récupérer les détails du véhicule
$sql = "SELECT v.immatriculation, v.image, v.kilometrage, v.prix, c.categorie, m.marque, mo.modele
        FROM voitures v
        JOIN categories c ON v.id_categorie = c.id_categorie
        JOIN marques m ON v.id_marque = m.id_marque
        JOIN modeles mo ON v.id_modele = mo.id_modele
        WHERE v.immatriculation = '$immatriculation'";

$result = $conn->query($sql);
$vehicle = $result->fetch_assoc();

// Requête SQL pour récupérer les options disponibles
$options_sql = "SELECT id_option, option, prix FROM options";
$options_result = $conn->query($options_sql);
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
            </ul>
        </nav>
    </header>
    <div class="vehicle-details">
        <?php if ($vehicle): ?>
            <div class="vehicle-image">
                <img src="<?php echo $vehicle['image']; ?>" alt="<?php echo $vehicle['modele']; ?>">
            </div>
            <div class="vehicle-info">
                <h1><?php echo $vehicle['marque'] . " " . $vehicle['modele']; ?></h1>
                <p><strong>Immatriculation:</strong> <?php echo $vehicle['immatriculation']; ?></p>
                <p><strong>Kilométrage:</strong> <?php echo $vehicle['kilometrage']; ?> km</p>
                <p><strong>Catégorie:</strong> <?php echo $vehicle['categorie']; ?></p>
                <p><strong>Prix:</strong> <?php echo $vehicle['prix']; ?> €</p>
                
                <h2>Options disponibles</h2>
                <form>
                    <?php
                    if ($options_result->num_rows > 0) {
                        while($option = $options_result->fetch_assoc()) {
                            echo "<div class='option'>";
                            echo "<input type='checkbox' id='option_" . $option['id_option'] . "' name='options[]' value='" . $option['id_option'] . "'>";
                            echo "<label for='option_" . $option['id_option'] . "'>" . $option['option'] . " (+ " . $option['prix'] . " €)</label>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Aucune option disponible</p>";
                    }
                    ?>
                </form>
            </div>
        <?php else: ?>
            <p>Véhicule non trouvé</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
