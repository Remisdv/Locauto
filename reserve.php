<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

$immatriculation = $_POST['immatriculation'];
$kilometres = $_POST['kilometres'];
$days = $_POST['days'];
$id_client = $_SESSION['user_id'];
$price_per_100km = $_POST['price_per_100km'];
$total_price = ($price_per_100km / 100) * $kilometres;

// Ajouter le coût supplémentaire par jour
$total_price += $total_price * 0.5 * $days;

// Ajouter le prix des options sélectionnées
$options = isset($_POST['options']) ? $_POST['options'] : [];
$options_total_price = 0;
foreach ($options as $option_id) {
    $sql_option = "SELECT prix FROM options WHERE id_option='$option_id'";
    $result_option = $conn->query($sql_option);
    if ($result_option->num_rows == 1) {
        $option = $result_option->fetch_assoc();
        $options_total_price += $option['prix'];
    }
}

// Ajouter les options au prix total
$total_price += $options_total_price;

$sql = "INSERT INTO reservations (id_client, immatriculation, kilometres, days, total_price) VALUES ('$id_client', '$immatriculation', '$kilometres', '$days', '$total_price')";

if ($conn->query($sql) === TRUE) {
    $reservation_id = $conn->insert_id;
    foreach ($options as $option_id) {
        $sql_option = "INSERT INTO reservation_options (reservation_id, option_id) VALUES ('$reservation_id', '$option_id')";
        $conn->query($sql_option);
    }
    header("Location: index.php");
    exit();
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
