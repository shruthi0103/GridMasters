const rows = 30;
const cols = 50; // Wider grid for Gosper Gun
let grid = createEmptyGrid();
let generation = 0;
let intervalId = null;

// Create empty grid
function createEmptyGrid() {
    return Array.from({ length: rows }, () => Array(cols).fill(0));
}

// Dynamically create grid HTML
function createGrid() {
    const gridContainer = document.getElementById('grid');
    gridContainer.style.gridTemplateColumns = `repeat(${cols}, 20px)`;
    gridContainer.innerHTML = '';
    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');
            cell.dataset.row = i;
            cell.dataset.col = j;
            cell.addEventListener('click', () => toggleCell(i, j));
            gridContainer.appendChild(cell);
        }
    }
}

// Toggle cell alive/dead
function toggleCell(i, j) {
    grid[i][j] = grid[i][j] ? 0 : 1;
    updateGrid();
}

// Update the visual grid
function updateGrid() {
    const cells = document.getElementsByClassName('cell');
    let index = 0;
    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            cells[index].style.backgroundColor = grid[i][j] ? '#007bff' : '#d3d3d3'; // Blue for alive, Grey for dead
            index++;
        }
    }
    document.getElementById('generation-count').innerText = generation;
    document.getElementById('population-count').innerText = countPopulation();
}

// Count living cells
function countPopulation() {
    return grid.flat().filter(c => c === 1).length;
}

// Start game
function startGame() {
    if (intervalId) return;
    intervalId = setInterval(nextGeneration, 200);
}

// Stop game
function stopGame() {
    clearInterval(intervalId);
    intervalId = null;
}

// Advance one generation
function nextGeneration() {
    const newGrid = createEmptyGrid();
    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            const aliveNeighbors = countAliveNeighbors(i, j);
            if (grid[i][j] === 1) {
                newGrid[i][j] = (aliveNeighbors === 2 || aliveNeighbors === 3) ? 1 : 0;
            } else {
                newGrid[i][j] = (aliveNeighbors === 3) ? 1 : 0;
            }
        }
    }
    grid = newGrid;
    generation++;
    updateGrid();
}

// Advance 23 generations
function plus23Generations() {
    for (let i = 0; i < 23; i++) {
        nextGeneration();
    }
}

// Reset game
function resetGame() {
    grid = createEmptyGrid();
    generation = 0;
    updateGrid();
}

// Count alive neighbors
function countAliveNeighbors(x, y) {
    let count = 0;
    for (let dx = -1; dx <= 1; dx++) {
        for (let dy = -1; dy <= 1; dy++) {
            if (dx === 0 && dy === 0) continue;
            const nx = x + dx;
            const ny = y + dy;
            if (nx >= 0 && nx < rows && ny >= 0 && ny < cols) {
                count += grid[nx][ny];
            }
        }
    }
    return count;
}

// Load pattern into grid
function loadPattern(pattern) {
    resetGame();
    if (pattern === "block") {
        grid[1][1] = 1; grid[1][2] = 1; grid[2][1] = 1; grid[2][2] = 1;
    } else if (pattern === "boat") {
        grid[1][1] = 1; grid[1][2] = 1; grid[2][1] = 1; grid[2][3] = 1; grid[3][2] = 1;
    } else if (pattern === "beehive") {
        grid[1][2] = 1; grid[1][3] = 1; grid[2][1] = 1; grid[2][4] = 1; grid[3][2] = 1; grid[3][3] = 1;
    } else if (pattern === "loaf") {
        grid[1][2] = 1; grid[1][3] = 1; grid[2][1] = 1; grid[2][4] = 1; grid[3][2] = 1; grid[3][4] = 1; grid[4][3] = 1;
    } else if (pattern === "blinker") {
        grid[2][1] = 1; grid[2][2] = 1; grid[2][3] = 1;
    } else if (pattern === "beacon") {
        grid[1][1] = 1; grid[1][2] = 1; grid[2][1] = 1;
        grid[3][4] = 1; grid[4][3] = 1; grid[4][4] = 1;
    } else if (pattern === "toad") {
        grid[2][2] = 1; grid[2][3] = 1; grid[2][4] = 1;
        grid[3][1] = 1; grid[3][2] = 1; grid[3][3] = 1;
    } else if (pattern === "pulsar") {
        // Small version of pulsar
        for (let i = 2; i <= 4; i++) {
            grid[i][0] = 1; grid[i][5] = 1;
            grid[0][i] = 1; grid[5][i] = 1;
        }
        for (let i = 2; i <= 4; i++) {
            grid[i+7][0] = 1; grid[i+7][5] = 1;
            grid[0][i+7] = 1; grid[5][i+7] = 1;
        }
    } else if (pattern === "glider") {
        grid[1][2] = 1; grid[2][3] = 1; grid[3][1] = 1; grid[3][2] = 1; grid[3][3] = 1;
    } else if (pattern === "gospergun") {
        // Coordinates for Gosper Glider Gun
        const coords = [
            [5,1],[5,2],[6,1],[6,2],
            [5,11],[6,11],[7,11],[4,12],[8,12],[3,13],[9,13],[3,14],[9,14],[6,15],
            [4,16],[8,16],[5,17],[6,17],[7,17],[6,18],
            [3,21],[4,21],[5,21],[3,22],[4,22],[5,22],[2,23],[6,23],
            [1,25],[2,25],[6,25],[7,25],
            [3,35],[4,35],[3,36],[4,36]
        ];
        coords.forEach(([r, c]) => {
            grid[r][c] = 1;
        });
    }
    updateGrid();
}

// Event listeners
document.getElementById('start').addEventListener('click', startGame);
document.getElementById('stop').addEventListener('click', stopGame);
document.getElementById('next').addEventListener('click', nextGeneration);
document.getElementById('plus23').addEventListener('click', plus23Generations);
document.getElementById('reset').addEventListener('click', resetGame);
document.getElementById('pattern').addEventListener('change', function() {
    loadPattern(this.value);
});

// Initialize grid
window.onload = createGrid;
