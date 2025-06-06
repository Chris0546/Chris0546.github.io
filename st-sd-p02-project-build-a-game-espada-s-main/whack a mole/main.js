let score = 0;
let currentMole = null;
let moleTimer;
let playerName = '';

const holes = document.querySelectorAll('.hole');
const scoreDisplay = document.querySelector('#score');
const resultDisplay = document.querySelector('.result');
const playerNameInput = document.querySelector('#player-name-input');
const playerNameDisplay = document.querySelector('#player-name-display');
const hammer = document.querySelector('#hammer');
const startButton = document.querySelector('#start-button');

// Start het spel
function startGame() {
    playerName = playerNameInput.value.trim();
    if (playerName === '') {
        alert('Voer je naam in om te beginnen!');
        return;
    }
    playerNameDisplay.textContent = `Speler: ${playerName}`;
    score = 0;
    scoreDisplay.textContent = `Score: ${score}`;
    resultDisplay.textContent = '';
    startButton.disabled = true;
    playerNameInput.disabled = true;
    startMoleTimer();

    // Stop het spel na 30 seconden
    setTimeout(() => {
        clearInterval(moleTimer);
        resultDisplay.textContent = `Tijd om! Eindscore: ${score}`;
        startButton.disabled = false;
        playerNameInput.disabled = false;
    }, 30000);
}

// Laat willekeurig een mol zien
function showMole() {
    const randomIndex = Math.floor(Math.random() * holes.length);
    const hole = holes[randomIndex];
    hole.classList.add('mole');
    currentMole = hole;

    setTimeout(() => {
        hole.classList.remove('mole');
        currentMole = null;
    }, Math.random() * 1500 + 500);
}

// Start de mole-timer
function startMoleTimer() {
    moleTimer = setInterval(showMole, Math.random() * 1500 + 1000);
}

// Verplaats de hamer met de muis
document.addEventListener('mousemove', (event) => {
    hammer.style.left = `${event.pageX - 25}px`;
    hammer.style.top = `${event.pageY - 25}px`;
});

// Verwerk kliks op de gaten
holes.forEach(hole => {
    hole.addEventListener('click', () => {
        if (hole === currentMole) {
            score++;
            scoreDisplay.textContent = `Score: ${score}`;
            resultDisplay.textContent = 'Raak!';
            resultDisplay.style.color = 'green';
            hole.classList.remove('mole');
            currentMole = null;
        } else {
            resultDisplay.textContent = 'Mis!';
            resultDisplay.style.color = 'red';
        }
    });
});

// Event listeners
startButton.addEventListener('click', startGame);
playerNameInput.addEventListener('keyup', (event) => {
    if (event.key === 'Enter') {
        startGame();
    }
});
