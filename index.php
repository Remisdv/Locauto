<?php
session_start();

// Nombre de résultats par page
$results_per_page = 5;

// Se connecter à la base de données
$conn = new mysqli("localhost", "root", "", "locauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les critères de recherche
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_option = isset($_GET['searchOption']) ? $conn->real_escape_string($_GET['searchOption']) : 'marque_modele';
$category_option = isset($_GET['categoryOption']) ? $conn->real_escape_string($_GET['categoryOption']) : 'all';
$sort_option = isset($_GET['sortOption']) ? $conn->real_escape_string($_GET['sortOption']) : 'none';

// Construire la clause WHERE pour la recherche
$where_clauses = [];
if (!empty($search_query)) {
    if ($search_option == 'marque_modele') {
        $where_clauses[] = "(m.marque LIKE '%$search_query%' OR mo.modele LIKE '%$search_query%')";
    } elseif ($search_option == 'immatriculation') {
        $where_clauses[] = "v.immatriculation LIKE '%$search_query%'";
    }
}
if ($category_option != 'all') {
    $where_clauses[] = "c.categorie = '$category_option'";
}
$where_clause = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Construire la clause ORDER BY pour le tri
$order_clause = '';
if ($sort_option == 'kilometrage_asc') {
    $order_clause = 'ORDER BY v.kilometrage ASC';
} elseif ($sort_option == 'kilometrage_desc') {
    $order_clause = 'ORDER BY v.kilometrage DESC';
} elseif ($sort_option == 'prix_asc') {
    $order_clause = 'ORDER BY v.prix ASC';
} elseif ($sort_option == 'prix_desc') {
    $order_clause = 'ORDER BY v.prix DESC';
} elseif ($sort_option == 'marque_asc') {
    $order_clause = 'ORDER BY m.marque ASC';
} elseif ($sort_option == 'marque_desc') {
    $order_clause = 'ORDER BY m.marque DESC';
}

// Déterminer le nombre total de résultats
$sql = "SELECT COUNT(*) AS total
        FROM voitures v
        JOIN categories c ON v.id_categorie = c.id_categorie
        JOIN marques m ON v.id_marque = m.id_marque
        JOIN modeles mo ON v.id_modele = mo.id_modele
        $where_clause";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_results = $row["total"];
$total_pages = ceil($total_results / $results_per_page);

// Déterminer la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
} elseif ($page > $total_pages) {
    $page = $total_pages;
}

// Déterminer le offset pour la requête SQL
$start_from = ($page - 1) * $results_per_page;

$sql = "SELECT v.immatriculation, c.categorie, m.marque, mo.modele, v.prix
        FROM voitures v
        JOIN categories c ON v.id_categorie = c.id_categorie
        JOIN marques m ON v.id_marque = m.id_marque
        JOIN modeles mo ON v.id_modele = mo.id_modele
        $where_clause
        $order_clause
        LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);

// Récupérer toutes les catégories
$sql_categories = "SELECT DISTINCT categorie FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row_categories = $result_categories->fetch_assoc()) {
        $categories[] = $row_categories['categorie'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Véhicules Disponibles</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/index.css">
    <script>
        function updateFilters() {
            const searchInput = document.getElementById('searchInput').value;
            const searchOption = document.getElementById('searchOption').value;
            const categoryOption = document.getElementById('categoryOption').value;
            const sortOption = document.getElementById('sortOption').value;

            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput);
            url.searchParams.set('searchOption', searchOption);
            url.searchParams.set('categoryOption', categoryOption);
            url.searchParams.set('sortOption', sortOption);

            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    updateFilters();
                }
            });
            document.getElementById('searchInput').addEventListener('blur', updateFilters);
            document.getElementById('searchOption').addEventListener('change', updateFilters);
            document.getElementById('categoryOption').addEventListener('change', updateFilters);
            document.getElementById('sortOption').addEventListener('change', updateFilters);
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li class="menu-dropdown">
                    <a href="javascript:void(0)" class="dropbtn"><?php echo isset($_SESSION['user_id']) ? $_SESSION['username'] : 'Compte'; ?></a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="client.php">Tableau de Bord</a>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                <a href="admin.php">Administration</a>
                            <?php endif; ?>
                            <a href="logout.php">Déconnexion</a>
                        <?php else: ?>
                            <a href="login.php">Connexion</a>
                            <a href="register.php">Inscription</a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="banner"></div>
    <div class="container">
        <div class="left">
            <section class="filters">
                <div class="searchOption-container">
                    <label for="searchOption">Recherche:</label>
                    <select id="searchOption" name="searchOption">
                        <option value="marque_modele" <?php if ($search_option == 'marque_modele') echo 'selected'; ?>>Marque et Modèle</option>
                        <option value="immatriculation" <?php if ($search_option == 'immatriculation') echo 'selected'; ?>>Immatriculation</option>
                    </select>
                </div>
                <div class="categoryOption-container">
                    <label for="categoryOption">Categorie:</label>
                    <select id="categoryOption" name="categoryOption">
                        <option value="all" <?php if ($category_option == 'all') echo 'selected'; ?>>Toutes les catégories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category; ?>" <?php if ($category_option == $category) echo 'selected'; ?>><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="sortOption-container">
                    <label for="sortOption">Trier par:</label>
                    <select id="sortOption" name="sortOption">
                        <option value="none" <?php if ($sort_option == 'none') echo 'selected'; ?>>Aucun</option>
                        <option value="kilometrage_asc" <?php if ($sort_option == 'kilometrage_asc') echo 'selected'; ?>>Kilométrage croissant</option>
                        <option value="kilometrage_desc" <?php if ($sort_option == 'kilometrage_desc') echo 'selected'; ?>>Kilométrage décroissant</option>
                        <option value="prix_asc" <?php if ($sort_option == 'prix_asc') echo 'selected'; ?>>Prix croissant</option>
                        <option value="prix_desc" <?php if ($sort_option == 'prix_desc') echo 'selected'; ?>>Prix décroissant</option>
                        <option value="marque_asc" <?php if ($sort_option == 'marque_asc') echo 'selected'; ?>>Marque croissant</option>
                        <option value="marque_desc" <?php if ($sort_option == 'marque_desc') echo 'selected'; ?>>Marque décroissant</option>
                    </select>
                </div>
                <div class="search-bar">
                    <input type="text" id="searchInput" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Rechercher...">
                </div>
            </section>
        </div>

        <div class="right">
            <section class="section2">
                <h1>Liste des Véhicules Disponibles</h1>
                <div class="vehicles-container" id="vehiclesContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $immatriculation = $row["immatriculation"];
                            echo "<div class='vehicle-card' data-immatriculation='" . $row["immatriculation"] . "' onclick='redirectToDetails(this)'>";
                            // Fetch images for this vehicle
                            $sql_images = "SELECT image_url FROM images WHERE immatriculation = '$immatriculation'";
                            $result_images = $conn->query($sql_images);
                            if ($result_images->num_rows > 0) {
                                echo "<div class='image-slider'>";
                                while ($image_row = $result_images->fetch_assoc()) {
                                    echo "<img src='" . $image_row["image_url"] . "' alt='" . $row["modele"] . "'>";
                                }
                                echo "<button class='prev' onclick='event.stopPropagation();changeImage(this, -1)'>❮</button>";
                                echo "<button class='next' onclick='event.stopPropagation();changeImage(this, 1)'>❯</button>";
                                echo "</div>";
                            }
                            echo "<h2>" . $row["marque"] . " " . $row["modele"] . "</h2>";
                            echo "<p>Prix par 100 km: " . $row["prix"] . " €</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Aucun véhicule disponible</p>";
                    }
                    ?>
                </div>

                <div class="pagination">
                    <?php
                    if ($page > 1) {
                        echo "<a href='index.php?page=" . ($page - 1) . "&search=" . urlencode($search_query) . "&searchOption=$search_option&categoryOption=$category_option&sortOption=$sort_option' class='prev-button'>Précédent</a>";
                    }
                    if ($page < $total_pages) {
                        echo "<a href='index.php?page=" . ($page + 1) . "&search=" . urlencode($search_query) . "&searchOption=$search_option&categoryOption=$category_option&sortOption=$sort_option' class='next-button'>Suivant</a>";
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>
    <script src="JS/script.js"></script>
    <script>
    function updateFilters() {
        const searchInput = document.getElementById('searchInput').value;
        const searchOption = document.getElementById('searchOption').value;
        const categoryOption = document.getElementById('categoryOption').value;
        const sortOption = document.getElementById('sortOption').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput);
        url.searchParams.set('searchOption', searchOption);
        url.searchParams.set('categoryOption', categoryOption);
        url.searchParams.set('sortOption', sortOption);

        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();  // Empêche le formulaire de soumettre si nécessaire
                updateFilters();
            }
        });

        searchInput.addEventListener('blur', updateFilters);
        document.getElementById('searchOption').addEventListener('change', updateFilters);
        document.getElementById('categoryOption').addEventListener('change', updateFilters);
        document.getElementById('sortOption').addEventListener('change', updateFilters);
    });
</script>

</body>
</html>
<?php
$conn->close();
?>
