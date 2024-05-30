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

// Helper function to get the start and end of the current week
function getStartAndEndOfWeek($date) {
    $dto = new DateTime($date);
    $dto->setISODate($dto->format('o'), $dto->format('W'));
    $start = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $end = $dto->format('Y-m-d');
    return array($start, $end);
}

// Get current week's start and end date
list($start_date, $end_date) = getStartAndEndOfWeek(date('Y-m-d'));

// Fetch all cars
$sql_cars = "SELECT v.immatriculation, m.marque, mo.modele 
             FROM voitures v 
             JOIN marques m ON v.id_marque = m.id_marque 
             JOIN modeles mo ON v.id_modele = mo.id_modele";
$result_cars = $conn->query($sql_cars);

$cars = [];
while ($row = $result_cars->fetch_assoc()) {
    $cars[] = $row;
}

// Fetch all reservations for the current week
$sql_reservations = "SELECT r.immatriculation, r.start_date, r.end_date, r.status, c.nom, c.prenom, c.email 
                     FROM reservations r 
                     JOIN clients c ON r.id_client = c.id_client
                     WHERE r.start_date <= '$end_date' AND r.end_date >= '$start_date'";
$result_reservations = $conn->query($sql_reservations);

$reservations = [];
while ($row = $result_reservations->fetch_assoc()) {
    $reservations[] = $row;
}

// Generate an array of days for the current week
$week_days = [];
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime($start_date . " + $i days"));
    $week_days[] = [
        'date' => $date,
        'day' => strftime('%A', strtotime($date))
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Hebdomadaire des Voitures</title>
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
    <div class="planning-container">
        <h2>Planning Hebdomadaire des Voitures</h2>
        <table>
            <thead>
                <tr>
                    <th>Véhicule</th>
                    <?php foreach ($week_days as $day): ?>
                        <th><?php echo $day['day'] . '<br>' . date('d/m/Y', strtotime($day['date'])); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo $car['marque'] . ' ' . $car['modele'] . ' (' . $car['immatriculation'] . ')'; ?></td>
                        <?php foreach ($week_days as $day): ?>
                            <td>
                                <?php
                                $reserved = false;
                                foreach ($reservations as $reservation) {
                                    if ($reservation['immatriculation'] == $car['immatriculation'] &&
                                        $reservation['start_date'] <= $day['date'] &&
                                        $reservation['end_date'] >= $day['date']) {
                                        echo 'Réservé par ' . $reservation['nom'] . ' ' . $reservation['prenom'] . ' (' . $reservation['email'] . ')';
                                        $reserved = true;
                                        break;
                                    }
                                }
                                if (!$reserved) {
                                    echo 'Disponible';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
