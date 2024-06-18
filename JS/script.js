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

document.addEventListener("DOMContentLoaded", function() {
    const sliders = document.querySelectorAll('.image-slider');
    sliders.forEach(slider => {
        const images = slider.querySelectorAll('img');
        images.forEach((img, index) => {
            img.style.display = index === 0 ? 'block' : 'none';
        });

        let currentIndex = 0;

        function showImage(index) {
            images.forEach((img, i) => {
                img.style.display = 'none';
                if (i === index) {
                    img.style.display = 'block';
                }
            });
        }

        function changeImage(button, n) {
            const slider = button.parentElement;
            const images = slider.querySelectorAll('img');
            let currentIndex;
            images.forEach((img, index) => {
                if (img.style.display === 'block') {
                    currentIndex = index;
                    img.style.display = 'none';
                }
            });
            let newIndex = currentIndex + n;
            if (newIndex >= images.length) newIndex = 0;
            if (newIndex < 0) newIndex = images.length - 1;
            images[newIndex].style.display = 'block';
        }

        slider.querySelector('.prev').addEventListener('click', function(e) {
            e.stopPropagation();
            changeImage(this, -1);
        });

        slider.querySelector('.next').addEventListener('click', function(e) {
            e.stopPropagation();
            changeImage(this, 1);
        });
    });
});

function redirectToDetails(element) {
    const immatriculation = element.getAttribute('data-immatriculation');
    window.location.href = 'vehicle.php?immatriculation=' + immatriculation;
}



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
