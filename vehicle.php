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
                <button class='prev' onclick='changeImage(this, -1)'>❮</button>
                <button class='next' onclick='changeImage(this, 1)'>❯</button>
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
