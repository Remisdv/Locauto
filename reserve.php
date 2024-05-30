<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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

$user_id = $_SESSION['user_id'];
$immatriculation = $_POST['immatriculation'];
$kilometres = $_POST['kilometres'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$options = isset($_POST['options']) ? $_POST['options'] : [];
$status = 'pending';

// Calculate total days
$startDateTime = new DateTime($start_date);
$endDateTime = new DateTime($end_date);
$days = $startDateTime->diff($endDateTime)->days + 1;

// Calculate total price
$sql_price = "SELECT prix FROM voitures WHERE immatriculation = '$immatriculation'";
$result_price = $conn->query($sql_price);
$row_price = $result_price->fetch_assoc();
$price_per_100_km = $row_price['prix'];
$total_price = ($price_per_100_km / 100) * $kilometres;
$total_price += $total_price * 0.5 * $days;

// Add options price
foreach ($options as $option_id) {
    $sql_option_price = "SELECT prix FROM options WHERE id_option = $option_id";
    $result_option_price = $conn->query($sql_option_price);
    if ($result_option_price->num_rows > 0) {
        $row_option_price = $result_option_price->fetch_assoc();
        $total_price += $row_option_price['prix'];
    }
}

// Insert reservation into database
$sql_reservation = "INSERT INTO reservations (id_client, immatriculation, kilometres, days, total_price, status, start_date, end_date) 
                    VALUES ('$user_id', '$immatriculation', '$kilometres', '$days', '$total_price', '$status', '$start_date', '$end_date')";

if ($conn->query($sql_reservation) === TRUE) {
    $reservation_id = $conn->insert_id;

    // Insert options into reservation_options table
    foreach ($options as $option_id) {
        $sql_reservation_option = "INSERT INTO reservation_options (reservation_id, option_id) VALUES ('$reservation_id', '$option_id')";
        $conn->query($sql_reservation_option);
    }

    echo "Réservation effectuée avec succès!";
    // Redirect to a success page or the client dashboard
    header('Location: client.php');
} else {
    echo "Erreur: " . $sql_reservation . "<br>" . $conn->error;
}

$conn->close();
?>
