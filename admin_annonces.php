<?php
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locauto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Traitement de l'ajout d'un véhicule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vehicle'])) {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $immatriculation = $_POST['immatriculation'];
    $image = $_POST['image'];
    $kilometrage = $_POST['kilometrage'];
    $categorie = $_POST['categorie'];
    $prix = $_POST['prix'];

    $sql = "INSERT INTO voitures (immatriculation, id_marque, id_modele, image, kilometrage, id_categorie, prix) 
            VALUES ('$immatriculation', (SELECT id_marque FROM marques WHERE marque='$marque'), 
                    (SELECT id_modele FROM modeles WHERE modele='$modele'), '$image', '$kilometrage', 
                    (SELECT id_categorie FROM categories WHERE categorie='$categorie'), '$prix')";

    if ($conn->query($sql) === TRUE) {
        $message = "Véhicule ajouté avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de l'ajout d'une option
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_option'])) {
    $option = $_POST['option'];
    $prix = $_POST['prix'];

    $sql = "INSERT INTO options (option, prix) VALUES ('$option', '$prix')";

    if ($conn->query($sql) === TRUE) {
        $message = "Option ajoutée avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de la modification d'un véhicule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_vehicle'])) {
    $id_voiture = $_POST['id_voiture'];
    $image = $_POST['image'];
    $kilometrage = $_POST['kilometrage'];
    $prix = $_POST['prix'];

    $sql = "UPDATE voitures SET image='$image', kilometrage='$kilometrage', prix='$prix' WHERE immatriculation='$id_voiture'";

    if ($conn->query($sql) === TRUE) {
        $message = "Véhicule mis à jour avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de la suppression d'un véhicule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_vehicle'])) {
    $id_voiture = $_POST['id_voiture'];

    $sql = "DELETE FROM voitures WHERE immatriculation='$id_voiture'";

    if ($conn->query($sql) === TRUE) {
        $message = "Véhicule supprimé avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupération de tous les véhicules
$sql_voitures = "SELECT v.immatriculation, m.marque, mo.modele, v.image, v.kilometrage, c.categorie, v.prix
                 FROM voitures v
                 JOIN marques m ON v.id_marque = m.id_marque
                 JOIN modeles mo ON v.id_modele = mo.id_modele
                 JOIN categories c ON v.id_categorie = c.id_categorie";
$result_voitures = $conn->query($sql_voitures);

// Récupération de toutes les options
$sql_options = "SELECT * FROM options";
$result_options = $conn->query($sql_options);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gérer les Annonces</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Réservations</a></li>
                <li><a href="admin_annonces.php">Gérer les Annonces</a></li>
                <li><a href="admin_clients.php">Gérer les Clients</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <div class="admin-container">
        <h2>Administration - Gérer les Annonces</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <div class="form-section">
            <h3>Ajouter un Véhicule</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="marque">Marque</label>
                <input type="text" id="marque" name="marque" required>
                <label for="modele">Modèle</label>
                <input type="text" id="modele" name="modele" required>
                <label for="immatriculation">Immatriculation</label>
                <input type="text" id="immatriculation" name="immatriculation" required>
                <label for="image">Image (URL)</label>
                <input type="text" id="image" name="image" required>
                <label for="kilometrage">Kilométrage</label>
                <input type="number" id="kilometrage" name="kilometrage" required>
                <label for="categorie">Catégorie</label>
                <input type="text" id="categorie" name="categorie" required>
                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
                <button type="submit" name="add_vehicle">Ajouter Véhicule</button>
            </form>
        </div>
        
        <div class="form-section">
            <h3>Ajouter une Option</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="option">Option</label>
                <input type="text" id="option" name="option" required>
                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
                <button type="submit" name="add_option">Ajouter Option</button>
            </form>
        </div>
        
        <div class="form-section">
            <h3>Modifier un Véhicule</h3>
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Image</th>
                        <th>Kilométrage</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_voitures->num_rows > 0): ?>
                        <?php while($row = $result_voitures->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['immatriculation']; ?></td>
                                <td><?php echo $row['marque']; ?></td>
                                <td><?php echo $row['modele']; ?></td>
                                <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['modele']; ?>"></td>
                                <td><?php echo $row['kilometrage']; ?></td>
                                <td><?php echo $row['categorie']; ?></td>
                                <td><?php echo $row['prix']; ?> €</td>
                                <td>
                                    <form action="admin_annonces.php" method="POST" class="actions-form">
                                        <input type="hidden" name="id_voiture" value="<?php echo $row['immatriculation']; ?>">
                                        <label for="image">Image (URL)</label>
                                        <input type="text" id="image" name="image" value="<?php echo $row['image']; ?>" required>
                                        <label for="kilometrage">Kilométrage</label>
                                        <input type="number" id="kilometrage" name="kilometrage" value="<?php echo $row['kilometrage']; ?>" required>
                                        <label for="prix">Prix</label>
                                        <input type="number" step="0.01" id="prix" name="prix" value="<?php echo $row['prix']; ?>" required>
                                        <button type="submit" name="update_vehicle">Modifier</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Aucun véhicule trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="form-section">
            <h3>Supprimer un Véhicule</h3>
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Image</th>
                        <th>Kilométrage</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_voitures->num_rows > 0): ?>
                        <?php while($row = $result_voitures->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['immatriculation']; ?></td>
                                <td><?php echo $row['marque']; ?></td>
                                <td><?php echo $row['modele']; ?></td>
                                <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['modele']; ?>"></td>
                                <td><?php echo $row['kilometrage']; ?></td>
                                <td><?php echo $row['categorie']; ?></td>
                                <td><?php echo $row['prix']; ?> €</td>
                                <td>
                                    <form action="admin_annonces.php" method="POST" class="actions-form">
                                        <input type="hidden" name="id_voiture" value="<?php echo $row['immatriculation']; ?>">
                                        <button type="submit" name="delete_vehicle">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Aucun véhicule trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="form-section">
            <h3>Options</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Option</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_options->num_rows > 0): ?>
                        <?php while($row = $result_options->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_option']; ?></td>
                                <td><?php echo $row['option']; ?></td>
                                <td><?php echo $row['prix']; ?> €</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Aucune option trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
