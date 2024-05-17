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

    $sql = "INSERT INTO voitures (immatriculation, id_marque, id_modele, image, kilometrage, id_categorie, prix) VALUES ('$immatriculation', (SELECT id_marque FROM marques WHERE marque='$marque'), (SELECT id_modele FROM modeles WHERE modele='$modele'), '$image', '$kilometrage', (SELECT id_categorie FROM categories WHERE categorie='$categorie'), '$prix')";

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

// Traitement de la mise à jour du statut d'administrateur d'un client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin_status'])) {
    $client_id = $_POST['client_id'];
    $is_admin = $_POST['is_admin'] ? 1 : 0;

    $sql = "UPDATE clients SET is_admin='$is_admin' WHERE id_client='$client_id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Statut d'administrateur mis à jour avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de l'acceptation ou du rejet des réservations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_reservation_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];
    $immatriculation = $_POST['immatriculation'];
    $kilometres = $_POST['kilometres'];

    if ($status == 'accepted') {
        // Mettre à jour le kilométrage de la voiture
        $sql_update_km = "UPDATE voitures SET kilometrage = kilometrage + '$kilometres' WHERE immatriculation = '$immatriculation'";
        $conn->query($sql_update_km);
    }

    // Mettre à jour le statut de la réservation
    $sql = "UPDATE reservations SET status='$status' WHERE id='$reservation_id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Réservation mise à jour avec succès.";
    } else {
        $message = "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupération de tous les clients
$sql_clients = "SELECT * FROM clients";
$result_clients = $conn->query($sql_clients);

// Récupération de toutes les réservations
$sql_reservations = "SELECT r.id, r.immatriculation, r.kilometres, r.status, c.nom, c.prenom FROM reservations r JOIN clients c ON r.id_client = c.id_client";
$result_reservations = $conn->query($sql_reservations);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <div class="admin-container">
        <h2>Administration</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="form-section">
            <h3>Ajouter un Véhicule</h3>
            <form action="admin.php" method="POST">
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
            <form action="admin.php" method="POST">
                <label for="option">Option</label>
                <input type="text" id="option" name="option" required>
                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
                <button type="submit" name="add_option">Ajouter Option</button>
            </form>
        </div>
        <div class="form-section">
            <h3>Gérer les Clients</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_clients->num_rows > 0): ?>
                        <?php while($row = $result_clients->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_client']; ?></td>
                                <td><?php echo $row['nom']; ?></td>
                                <td><?php echo $row['prenom']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['adresse']; ?></td>
                                <td><?php echo $row['is_admin'] ? 'Oui' : 'Non'; ?></td>
                                <td>
                                    <form action="admin.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="client_id" value="<?php echo $row['id_client']; ?>">
                                        <input type="hidden" name="is_admin" value="<?php echo $row['is_admin'] ? 0 : 1; ?>">
                                        <button type="submit" name="update_admin_status"><?php echo $row['is_admin'] ? 'Retirer Admin' : 'Mettre Admin'; ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Aucun client trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-section">
            <h3>Gérer les Réservations</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Immatriculation</th>
                        <th>Kilomètres</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_reservations->num_rows > 0): ?>
                        <?php while($row = $result_reservations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nom'] . " " . $row['prenom']; ?></td>
                                <td><?php echo $row['immatriculation']; ?></td>
                                <td><?php echo $row['kilometres']; ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <form action="admin.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="status" value="accepted">
                                            <input type="hidden" name="immatriculation" value="<?php echo $row['immatriculation']; ?>">
                                            <input type="hidden" name="kilometres" value="<?php echo $row['kilometres']; ?>">
                                            <button type="submit" name="update_reservation_status">Accepter</button>
                                        </form>
                                        <form action="admin.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" name="update_reservation_status">Rejeter</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
