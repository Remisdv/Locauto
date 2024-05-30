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

// Récupération de tous les clients
$sql_clients = "SELECT * FROM clients";
$result_clients = $conn->query($sql_clients);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gérer les Clients</title>
    <link rel="stylesheet" href="CSS/styles.css">
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
        <h2>Administration - Gérer les Clients</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
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
                                    <form action="admin_clients.php" method="POST" style="display:inline;">
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
    </div>
    <?php $conn->close(); ?>
</body>
</html>
