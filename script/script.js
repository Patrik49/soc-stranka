document.addEventListener('DOMContentLoaded', () => {
    // Nájde všetky tlačidlá s triedou "pridat"
    const buttons = document.querySelectorAll('.pridat');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            
            // Ak už tlačidlo má triedu 'uspech', nerob nič (ignoruj kliknutie)
            if (this.classList.contains('uspech')) {
                return;
            }

            // Odložíme si pôvodný text tlačidla
            const originalText = this.innerText;

            // Pridáme triedu pre zelenú farbu
            this.classList.add('uspech');
            // Zmeníme text pre lepšiu odozvu
            this.innerText = "Pridané ✔";

            // Po 1.5 sekunde (1500 ms) vrátime všetko do pôvodného stavu
            setTimeout(() => {
                this.classList.remove('uspech');
                this.innerText = originalText;
            }, 1500);
        });
    });

    // --- New Filter Logic ---
    const filterForm = document.querySelector('.filter_sidebar form');
    const gallery = document.querySelector('.gallery');

    if (filterForm) {
        filterForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent page reload

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('filter_products.php?' + params.toString())
                .then(response => response.text())
                .then(html => {
                    gallery.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching filtered products:', error);
                    gallery.innerHTML = '<p>Chyba pri načítaní produktov.</p>';
                });
        });
    }
});