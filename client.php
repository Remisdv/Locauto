<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "locauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $sql_update = "UPDATE clients SET nom='$nom', prenom='$prenom', email='$email', password='$password' WHERE id_client='$user_id'";
    } else {
        $sql_update = "UPDATE clients SET nom='$nom', prenom='$prenom', email='$email' WHERE id_client='$user_id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['username'] = $prenom . ' ' . $nom;
        $message = "Profil mis à jour avec succès.";
    } else {
        $message = "Erreur: " . $sql_update . "<br>" . $conn->error;
    }
}

// Fetch user details
$sql_user = "SELECT nom, prenom, email FROM clients WHERE id_client='$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Fetching latest reservations
$sql_latest = "SELECT r.id, v.immatriculation, m.marque, mo.modele, r.start_date, r.end_date, r.status, r.days, r.total_price 
               FROM reservations r
               JOIN voitures v ON r.immatriculation = v.immatriculation
               JOIN marques m ON v.id_marque = m.id_marque
               JOIN modeles mo ON v.id_modele = mo.id_modele
               WHERE r.id_client = '$user_id' 
               ORDER BY r.id DESC 
               LIMIT 5";

// Fetching ongoing reservations
$sql_current = "SELECT r.id, v.immatriculation, m.marque, mo.modele, r.start_date, r.end_date, r.status, r.days, r.total_price 
                FROM reservations r
                JOIN voitures v ON r.immatriculation = v.immatriculation
                JOIN marques m ON v.id_marque = m.id_marque
                JOIN modeles mo ON v.id_modele = mo.id_modele
                WHERE r.id_client = '$user_id' 
                  AND r.status = 'ongoing'";

$result_latest = $conn->query($sql_latest);
$result_current = $conn->query($sql_current);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Client</title>
    <link rel="stylesheet" href="CSS/client.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="menu-dropdown">
                    <a href="javascript:void(0)" class="dropbtn"><?php echo $_SESSION['username']; ?></a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <a href="admin.php">Administration</a>
                        <?php endif; ?>
                        <a href="logout.php">Déconnexion</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="client-dashboard">
        <h2>Bienvenue, <?php echo $_SESSION['username']; ?></h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <div class="reservations-section">
            <h3>Dernières Réservations</h3>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Véhicule</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Statut</th>
                        <th>Durée (jours)</th>
                        <th>Prix Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_latest->num_rows > 0) {
                        while ($row = $result_latest->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['marque']} {$row['modele']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['end_date']}</td>
                                    <td>{$row['status']}</td>
                                    <td>{$row['days']}</td>
                                    <td>{$row['total_price']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Aucune réservation récente</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="current-reservations-section">
            <h3>Réservations en Cours</h3>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Véhicule</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Statut</th>
                        <th>Durée (jours)</th>
                        <th>Prix Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_current->num_rows > 0) {
                        while ($row = $result_current->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['marque']} {$row['modele']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['end_date']}</td>
                                    <td>{$row['status']}</td>
                                    <td>{$row['days']}</td>
                                    <td>{$row['total_price']}</td>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Aucune réservation en cours</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="profile-update-section">
            <h3>Mettre à jour le profil</h3>
            <form action="client.php" method="POST">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>
                
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                
                <label for="password">Mot de passe (laisser vide pour ne pas changer):</label>
                <input type="password" id="password" name="password">
                
                <button type="submit" name="update_profile">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
