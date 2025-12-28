document.addEventListener('DOMContentLoaded', () => {
    // Nájde všetky tlačidlá s triedou "pridat"
    const buttons = document.querySelectorAll('.pridat');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            
            // --- FIX START ---
            // Ak už tlačidlo má triedu 'uspech', nerob nič (ignoruj kliknutie)
            if (this.classList.contains('uspech')) {
                return;
            }
            // --- FIX END ---

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

    // Funkcionalita pre filter veľkostí na index.html
    const sizeBoxes = document.querySelectorAll('.size_box');
    
    sizeBoxes.forEach(box => {
        box.addEventListener('click', () => {
            // Najprv odstránime triedu 'active' zo všetkých
            sizeBoxes.forEach(innerBox => {
                innerBox.classList.remove('active');
            });
            // Potom pridáme triedu 'active' iba na kliknutý prvok
            box.classList.add('active');
        });
    });
});