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

// Traitement de la suppression des réservations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    // Supprimer les options associées à la réservation
    $sql_delete_options = "DELETE FROM reservation_options WHERE reservation_id='$reservation_id'";
    $conn->query($sql_delete_options);

    // Supprimer la réservation
    $sql_delete_reservation = "DELETE FROM reservations WHERE id='$reservation_id'";

    if ($conn->query($sql_delete_reservation) === TRUE) {
        $message = "Réservation supprimée avec succès.";
    } else {
        $message = "Erreur: " . $sql_delete_reservation . "<br>" . $conn->error;
    }
}

// Récupération de toutes les réservations
$sql_reservations = "SELECT r.id, r.immatriculation, r.kilometres, r.days, r.total_price, r.status, r.start_date, r.end_date, c.nom, c.prenom
                     FROM reservations r
                     JOIN clients c ON r.id_client = c.id_client";
$result_reservations = $conn->query($sql_reservations);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body>
<header>
    <nav>
    <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="admin.php">Réservations</a></li>
                <li><a href="admin_annonces.php">Gérer les Annonces</a></li>
                <li><a href="admin_clients.php">Gérer les Clients</a></li>
                <li><a href="planing.php">Voir le Planing</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
    </nav>
</header>

    <div class="admin-container">
        <h2>Administration - Réservations</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="form-section">
            <h3>Gérer les Réservations</h3>
            <table>
                <thead>
                    <tr>

                        <th>Client</th>
                        <th>Immatriculation</th>
                        <th>Kilomètres</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Durée (jours)</th>
                        <th>Options</th>
                        <th>Prix Total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_reservations->num_rows > 0): ?>
                        <?php while($row = $result_reservations->fetch_assoc()): ?>
                            <tr>
                                
                                <td><?php echo $row['nom'] . " " . $row['prenom']; ?></td>
                                <td><?php echo $row['immatriculation']; ?></td>
                                <td><?php echo $row['kilometres']; ?></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td><?php echo $row['end_date']; ?></td>
                                <td><?php echo $row['days']; ?></td>
                                <td>
                                    <?php
                                    $reservation_id = $row['id'];
                                    $sql_options = "SELECT o.option, o.prix FROM reservation_options ro 
                                                    JOIN options o ON ro.option_id = o.id_option 
                                                    WHERE ro.reservation_id = '$reservation_id'";
                                    $result_options = $conn->query($sql_options);
                                    if ($result_options->num_rows > 0) {
                                        while ($option = $result_options->fetch_assoc()) {
                                            echo $option['option'] . " (+".$option['prix']." €)<br>";
                                        }
                                    } else {
                                        echo "Aucune option";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $row['total_price']; ?> €</td>
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
                                    <form action="admin.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_reservation">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
