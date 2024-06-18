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
$sql = "SELECT v.immatriculation, v.kilometrage, c.categorie, m.marque, mo.modele, v.prix
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

// Requête SQL pour récupérer les images associées
$sql_images = "SELECT image_url FROM images WHERE immatriculation = '$immatriculation'";
$result_images = $conn->query($sql_images);

$images = [];
if ($result_images->num_rows > 0) {
    while ($row = $result_images->fetch_assoc()) {
        $images[] = $row["image_url"];
    }
}

// Requête SQL pour récupérer les options disponibles
$sql_options = "SELECT id_option, option, prix FROM options";
$result_options = $conn->query($sql_options);

$options = [];
if ($result_options->num_rows > 0) {
    while ($row = $result_options->fetch_assoc()) {
        $options[] = $row;
    }
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
    <link rel="stylesheet" href="CSS/vehicle.css">
    <style>
        /* Vehicle Details Page Styles */
body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #ffffff;
    color: #000000;
    color: white;
}

header {
    background-color: #333;
    color: white;
    position: fixed;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 15px 0;
    top: 0;
    width: 100%;
    z-index: 1000;
}

header nav ul {
    list-style: none;
    padding: 0;
    text-align: center;
    margin: 0;
}

header nav ul li {
    display: inline;
    margin: 0 15px;
    position: relative;
}

header nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
}

header nav ul li a:hover {
    color: #dcdcdc;
}

.menu-dropdown {
    display: inline-block;
}

.menu-dropdown .dropbtn {
    cursor: pointer;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    font-weight: bold;
    background-color: #333;
    border: none;
}

.menu-dropdown .dropdown-content {
    display: none;
    position: absolute;
    background-color: #1e1e1e;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 1;
}

.menu-dropdown .dropdown-content a {
    color: #ffffff;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.menu-dropdown .dropdown-content a:hover {
    background-color: #333;
}

.menu-dropdown:hover .dropdown-content {
    display: block;
}

.vehicle-details {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 80px 20px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.vehicle-image {
    flex: 1;
    margin-right: 20px;
    max-width: 600px;
    position: relative;
}

.vehicle-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.vehicle-info {
    flex: 2;
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.vehicle-info h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #dcdcdc;
}

.vehicle-info p {
    font-size: 1.2em;
    margin-bottom: 10px;
    color: #b0b0b0;
}

.vehicle-info form {
    margin-top: 20px;
}

.vehicle-info .option {
    margin-bottom: 10px;
}

.vehicle-info input[type="checkbox"] {
    margin-right: 10px;
}

.vehicle-info input[type="number"],
.vehicle-info input[type="date"] {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: calc(100% - 20px);
}

.vehicle-info button {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.vehicle-info button:hover {
    background-color: #45a049;
}

.vehicle-info #totalPrice {
    font-weight: bold;
}

.vehicle-info a {
    color: #4CAF50;
    text-decoration: none;
}

.vehicle-info a:hover {
    text-decoration: underline;
}

/* Styles for the image slider */
.image-slider {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
    margin-bottom: 10px;
}

.image-slider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: none;
}

.image-slider img.active {
    display: block;
}

.image-slider .prev, .image-slider .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    margin-top: -22px;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    background-color: rgba(0,0,0,0.5);
}

.image-slider .next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.image-slider .prev:hover, .image-slider .next:hover {
    background-color: rgba(0,0,0,0.8);
}

    </style>
    <script>
        function calculatePrice() {
            const pricePer100Km = <?php echo $vehicle['prix']; ?>;
            const kilometres = document.getElementById('kilometres').value;
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            const options = document.querySelectorAll('input[name="options[]"]:checked');
            let totalPrice = (pricePer100Km / 100) * kilometres;

            // Ajouter le prix des options sélectionnées
            options.forEach(option => {
                totalPrice += parseFloat(option.dataset.price);
            });

            // Calculer la durée en jours
            const days = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

            // Ajouter le coût supplémentaire par jour
            totalPrice += totalPrice * 0.5 * days;

            document.getElementById('totalPrice').textContent = totalPrice.toFixed(2) + ' €';
        }

        document.addEventListener("DOMContentLoaded", function() {
            const slider = document.querySelector(".image-slider");
            const images = slider.querySelectorAll("img");
            let currentIndex = 0;

            function showImage(index) {
                images.forEach((img, i) => {
                    img.classList.remove("active");
                    if (i === index) {
                        img.classList.add("active");
                    }
                });
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            }

            if (images.length > 0) {
                showImage(currentIndex);
                setInterval(nextImage, 3000); // Change image every 3 seconds
            }
        });
    </script>
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
            <div class="image-slider">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo $image; ?>" alt="<?php echo $vehicle['modele']; ?>">
                <?php endforeach; ?>
                <!-- <button class='prev' onclick='changeImage(this, -1)'>❮</button>
                <button class='next' onclick='changeImage(this, 1)'>❯</button> -->
            </div>
        </div>
        <div class="vehicle-info">
            <h1><?php echo $vehicle['marque'] . " " . $vehicle['modele']; ?></h1>
            <p>Immatriculation: <?php echo $vehicle['immatriculation']; ?></p>
            <p>Kilométrage: <?php echo $vehicle['kilometrage']; ?> km</p>
            <p>Catégorie: <?php echo $vehicle['categorie']; ?></p>
            <p>Prix par 100 km: <?php echo $vehicle['prix']; ?> €</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="reserve.php" method="POST" oninput="calculatePrice()">
                    <input type="hidden" name="immatriculation" value="<?php echo $vehicle['immatriculation']; ?>">
                    <label for="kilometres">Kilomètres:</label>
                    <input type="number" id="kilometres" name="kilometres" required>
                    <label for="start_date">Date de début:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <label for="end_date">Date de fin:</label>
                    <input type="date" id="end_date" name="end_date" required>
                    <fieldset>
                        <legend>Options:</legend>
                        <?php foreach ($options as $option): ?>
                            <label>
                                <input type="checkbox" name="options[]" value="<?php echo $option['id_option']; ?>" data-price="<?php echo $option['prix']; ?>">
                                <?php echo $option['option']; ?> (+<?php echo $option['prix']; ?> €)
                            </label><br>
                        <?php endforeach; ?>
                    </fieldset>
                    <p>Prix total: <span id="totalPrice">0 €</span></p>
                    <button type="submit">Réserver</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Connectez-vous</a> pour réserver ce véhicule.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
