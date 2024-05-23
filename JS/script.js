document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('searchOption').addEventListener('change', applyFilters);
document.getElementById('categoryOption').addEventListener('change', applyFilters);
document.getElementById('sortOption').addEventListener('change', applyFilters);
console.log('script.js loaded');
function applyFilters() {
    var filter = document.getElementById('searchInput').value.toLowerCase();
    var searchOption = document.getElementById('searchOption').value;
    var categoryOption = document.getElementById('categoryOption').value;
    var sortOption = document.getElementById('sortOption').value;
    var vehiclesContainer = document.getElementById('vehiclesContainer');
    var vehicleCards = Array.from(vehiclesContainer.getElementsByClassName('vehicle-card'));

    vehicleCards.forEach(function(card) {
        var text = '';
        if (searchOption === 'marque_modele') {
            text = card.querySelector('h2').textContent.toLowerCase();
        } else if (searchOption === 'immatriculation') {
            text = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
        }
    
        var category = card.getAttribute('data-categorie');
        if (category) {
            category = category.toLowerCase();
        } else {
            category = '';
        }
    
        var matchesCategory = (categoryOption === 'all') || (category === categoryOption.toLowerCase());
    
        if (text.includes(filter) && matchesCategory) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
    console.log('Before if statement, sortOption:', sortOption, 'vehicleCards length:', vehicleCards.length);

    if (sortOption !== 'none') {
        vehicleCards.sort(function(a, b) {
            var kilomA = parseInt(a.getAttribute('data-kilometrage'));
            var kilomB = parseInt(b.getAttribute('data-kilometrage'));
            var priceA = parseInt(a.getAttribute('data-prix'));
            var priceB = parseInt(b.getAttribute('data-prix'));

            console.log('priceA:', priceA, 'priceB:', priceB);

    
            if (sortOption === 'kilometrage_asc') {
                return kilomA - kilomB;
            } else if (sortOption === 'kilometrage_desc') {
                return kilomB - kilomA;
            } else if (sortOption === 'prix_asc') {
                return priceA - priceB;
            } else if (sortOption === 'prix_desc') {
                return priceB - priceA;
            } else {
                return 0;
            }
        });
        
        console.log('After if statement');
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


