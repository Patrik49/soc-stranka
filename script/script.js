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

    // --- Enhanced Filter Logic ---
    const filterForm = document.querySelector('.filter_sidebar form');
    const gallery = document.querySelector('.gallery');
    const filterCheckboxes = document.querySelectorAll('.filter_sidebar input[type="checkbox"]');

    // Auto-submit form on filter change
    if (filterCheckboxes.length > 0 && filterForm) {
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                // Debounce the submission
                clearTimeout(window.filterTimeout);
                window.filterTimeout = setTimeout(() => {
                    const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                    filterForm.dispatchEvent(submitEvent);
                }, 500);
            });
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent page reload

            if (gallery) {
                gallery.style.opacity = '0.5'; // Visual feedback
            }

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            
            // Update URL for shareability
            history.pushState(null, '', 'index.php?' + params.toString());

            fetch('filter_products.php?' + params.toString())
                .then(response => response.text())
                .then(html => {
                    if (gallery) {
                        gallery.innerHTML = html;
                        gallery.style.opacity = '1';
                    }
                })
                .catch(error => {
                    console.error('Error fetching filtered products:', error);
                    if (gallery) {
                        gallery.innerHTML = '<p>Chyba pri načítaní produktov.</p>';
                        gallery.style.opacity = '1';
                    }
                });
        });
    }
});
