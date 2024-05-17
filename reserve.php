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
$id_client = $_SESSION['user_id'];

$sql = "INSERT INTO reservations (id_client, immatriculation, kilometres) VALUES ('$id_client', '$immatriculation', '$kilometres')";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
