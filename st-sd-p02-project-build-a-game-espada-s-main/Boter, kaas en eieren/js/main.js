let turnX = true; // Bijhouden of het de beurt van 'X' is (true = X, false = O)
let turnCount = 1; // Het aantal zetten dat is gedaan
let isFinished = false; // Of het spel is afgelopen
let turnIndicator = null; // Verwijzing naar het element dat de beurt toont

// Win-condities (combinaties van cellen die winnen)
const winConditions = [
    ['tl', 'tc', 'tr'], ['ml', 'mc', 'mr'], ['bl', 'bc', 'br'],
    ['tl', 'ml', 'bl'], ['tc', 'mc', 'bc'], ['tr', 'mr', 'br'],
    ['tl', 'mc', 'br'], ['tr', 'mc', 'bl']
];

// Functie die wordt uitgevoerd zodra de pagina is geladen
window.onload = function () {
    resetButtons(); // Reset de cellen op het bord
    document.querySelector('.reset').onclick = reset; // Koppel de reset knop aan de reset functie
    turnIndicator = document.querySelector('.turn-indicator'); // Koppel het element voor de beurt-indicator
};

// Functie die wordt uitgevoerd bij een zet van de speler
function move(event) {
    const cell = event.target; // De cel waar de speler op heeft geklikt

    if (cell.innerHTML === '&nbsp;' && !isFinished) { // Controleer of de cel leeg is en het spel niet voorbij is
        const currentSymbol = turnX ? 'X' : 'O'; // Bepaal welk symbool gebruikt wordt
        cell.innerHTML = currentSymbol; // Zet het symbool in de cel
        cell.classList.add(currentSymbol.toLowerCase()); // Voeg de klasse 'x' of 'o' toe voor CSS-styling

        resetIndicator(); // Werk de beurt-indicator bij

        if (checkWinCondition()) { // Controleer of iemand gewonnen heeft
            isFinished = true; // Zet het spel op "afgerond"
            turnIndicator.innerHTML = turnX
                ? 'Kruisje wint!' // Als X wint
                : 'Rondje wint!'; // Als O wint
        } else if (turnCount === 9) { // Als het bord vol is en niemand heeft gewonnen
            isFinished = true;
            turnIndicator.innerHTML = 'Gelijkspel'; // Er is een gelijkspel
        } else {
            turnX = !turnX; // Wissel van beurt
            turnCount++; // Verhoog het aantal zetten
        }
    }
}

// Reset het spel
function reset() {
    turnCount = 1; // Zet het aantal zetten terug naar 1
    isFinished = false; // Zet de status van het spel terug naar "niet afgerond"

    resetButtons(); // Reset de knoppen (cellen)
    resetIndicator(); // Reset de beurt-indicator
}

// Reset alle cellen
function resetButtons() {
    const buttons = document.querySelectorAll('.cell'); // Haal alle cellen op
    buttons.forEach(function (button) {
        button.onclick = move; // Voeg de functie 'move' toe als de knop wordt geklikt
        button.innerHTML = '&nbsp;'; // Zet de inhoud van de cel terug naar leeg
        button.className = 'cell'; // Verwijder de klassen 'x' en 'o'
    });
}

// Reset de beurt-indicator
function resetIndicator() {
    turnIndicator.innerHTML = turnX
        ? 'De beurt is aan kruisje' // Als het X is
        : 'De beurt is aan rondje'; // Als het O is
}

// Functie die controleert of er een winnaar is
function checkWinCondition() {
    let result = false;

    winConditions.forEach(function (condition) { // Loop door alle win-condities
        const firstButton = document.querySelector(`[data-cell="${condition[0]}"]`).innerHTML; // Eerste knop in de win-conditie
        const secondButton = document.querySelector(`[data-cell="${condition[1]}"]`).innerHTML; // Tweede knop
        const thirdButton = document.querySelector(`[data-cell="${condition[2]}"]`).innerHTML; // Derde knop

        // Controleer of alle drie de knoppen gelijk zijn en niet leeg
        if (firstButton !== '&nbsp;' && secondButton !== '&nbsp;' && thirdButton !== '&nbsp;') {
            if (firstButton === secondButton && secondButton === thirdButton) { // Als ze gelijk zijn
                result = true; // Zet het resultaat op true (winnaar gevonden)

                // Markeer de winnende cellen
                condition.forEach(cell => {
                    document.querySelector(`[data-cell="${cell}"]`).classList.add('winner');
                });
            }
        }
    });

    return result; // Return true als er een winnaar is, anders false
}
