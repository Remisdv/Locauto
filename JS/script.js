document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('searchOption').addEventListener('change', applyFilters);
document.getElementById('categoryOption').addEventListener('change', applyFilters);
document.getElementById('sortOption').addEventListener('change', applyFilters);

function applyFilters() {
    var filter = document.getElementById('searchInput').value.toLowerCase();
    var searchOption = document.getElementById('searchOption').value;
    var categoryOption = document.getElementById('categoryOption').value;
    var sortOption = document.getElementById('sortOption').value;
    var vehiclesContainer = document.getElementById('vehiclesContainer');
    var vehicleCards = Array.from(vehiclesContainer.getElementsByClassName('vehicle-card'));

    vehicleCards.forEach(function(card) {
        var text;
        if (searchOption === 'marque_modele') {
            text = card.querySelector('h2').textContent.toLowerCase();
        } else if (searchOption === 'immatriculation') {
            text = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
        }

        var category = card.getAttribute('data-categorie').toLowerCase();
        var matchesCategory = (categoryOption === 'all') || (category === categoryOption.toLowerCase());

        if (text.includes(filter) && matchesCategory) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });

    if (sortOption !== 'none') {
        vehicleCards.sort(function(a, b) {
            var kilomA = parseInt(a.getAttribute('data-kilometrage'));
            var kilomB = parseInt(b.getAttribute('data-kilometrage'));

            if (sortOption === 'kilometrage_asc') {
                return kilomA - kilomB;
            } else if (sortOption === 'kilometrage_desc') {
                return kilomB - kilomA;
            } else {
                return 0;
            }
        });

        vehicleCards.forEach(function(card) {
            vehiclesContainer.appendChild(card);
        });
    }
}

function redirectToDetails(element) {
    var immatriculation = element.getAttribute('data-immatriculation');
    window.location.href = 'vehicle.php?immatriculation=' + immatriculation;
}
