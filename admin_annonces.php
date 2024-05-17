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

// Traitement de l'ajout d'une marque
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_marque'])) {
    $marque = $_POST['marque'];

    $sql = "INSERT INTO marques (marque) VALUES ('$marque')";

    if ($conn->query($sql) === TRUE) {
        $message = "Marque ajoutée avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de l'ajout d'un modèle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_modele'])) {
    $modele = $_POST['modele'];

    $sql = "INSERT INTO modeles (modele) VALUES ('$modele')";

    if ($conn->query($sql) === TRUE) {
        $message = "Modèle ajouté avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de l'ajout d'une catégorie
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_categorie'])) {
    $categorie = $_POST['categorie'];

    $sql = "INSERT INTO categories (categorie) VALUES ('$categorie')";

    if ($conn->query($sql) === TRUE) {
        $message = "Catégorie ajoutée avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de l'ajout d'un véhicule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vehicle'])) {
    $marque_id = $_POST['marque'];
    $modele_id = $_POST['modele'];
    $immatriculation = $_POST['immatriculation'];
    $image = $_POST['image'];
    $kilometrage = $_POST['kilometrage'];
    $categorie_id = $_POST['categorie'];
    $prix = $_POST['prix'];

    if ($marque_id && $modele_id && $categorie_id) {
        $sql = "INSERT INTO voitures (immatriculation, id_marque, id_modele, image, kilometrage, id_categorie, prix) 
                VALUES ('$immatriculation', '$marque_id', '$modele_id', '$image', '$kilometrage', '$categorie_id', '$prix')";

        if ($conn->query($sql) === TRUE) {
            $message = "Véhicule ajouté avec succès.";
        } else {
            $message = "Erreur: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Erreur: Marque, modèle ou catégorie invalide.";
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
    $immatriculation = $_POST['immatriculation'];

    $sql = "DELETE FROM voitures WHERE immatriculation='$immatriculation'";

    if ($conn->query($sql) === TRUE) {
        $message = "Véhicule supprimé avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupération de toutes les marques
$sql_marques = "SELECT * FROM marques";
$result_marques = $conn->query($sql_marques);

// Récupération de tous les modèles
$sql_modeles = "SELECT * FROM modeles";
$result_modeles = $conn->query($sql_modeles);

// Récupération de toutes les catégories
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

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
            <h3>Ajouter une Marque</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="marque">Marque</label>
                <input type="text" id="marque" name="marque" required>
                <button type="submit" name="add_marque">Ajouter Marque</button>
            </form>
        </div>

        <div class="form-section">
            <h3>Ajouter un Modèle</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="modele">Modèle</label>
                <input type="text" id="modele" name="modele" required>
                <button type="submit" name="add_modele">Ajouter Modèle</button>
            </form>
        </div>

        <div class="form-section">
            <h3>Ajouter une Catégorie</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="categorie">Catégorie</label>
                <input type="text" id="categorie" name="categorie" required>
                <button type="submit" name="add_categorie">Ajouter Catégorie</button>
            </form>
        </div>

        <div class="form-section">
            <h3>Ajouter un Véhicule</h3>
            <form action="admin_annonces.php" method="POST">
                <label for="marque">Marque</label>
                <select id="marque" name="marque" required>
                    <?php if ($result_marques->num_rows > 0): ?>
                        <?php while($row = $result_marques->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_marque']; ?>"><?php echo $row['marque']; ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <label for="modele">Modèle</label>
                <select id="modele" name="modele" required>
                    <?php if ($result_modeles->num_rows > 0): ?>
                        <?php while($row = $result_modeles->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_modele']; ?>"><?php echo $row['modele']; ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <label for="immatriculation">Immatriculation</label>
                <input type="text" id="immatriculation" name="immatriculation" required>
                <label for="image">Image (URL)</label>
                <input type="text" id="image" name="image" required>
                <label for="kilometrage">Kilométrage</label>
                <input type="number" id="kilometrage" name="kilometrage" required>
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" required>
                    <?php if ($result_categories->num_rows > 0): ?>
                        <?php while($row = $result_categories->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_categorie']; ?>"><?php echo $row['categorie']; ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
                <button type="submit" name="add_vehicle">Ajouter Véhicule</button>
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
            <form action="admin_annonces.php" method="POST" class="actions-form">
                <label for="immatriculation">Immatriculation</label>
                <input type="text" id="immatriculation" name="immatriculation" required>
                <button type="submit" name="delete_vehicle">Supprimer</button>
            </form>
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
