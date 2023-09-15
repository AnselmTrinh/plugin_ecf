const gameContainer = document.getElementById("game-container");
const player = document.getElementById("player");
const movementLog = document.getElementById("movement-log");
const maxLogItems = 4; // Nombre maximum d'éléments dans le journal
let playerX = 0;
let playerY = 0;
let playerHP = 100; // Points de vie du joueur
let playerAttack = 300; // Force d'attaque du joueur
const step = 10;
const gameWidth = gameContainer.clientWidth;
const gameHeight = gameContainer.clientHeight;

// Fonction de mise à jour de la position du joueur
function updatePlayerPosition() {
  // Vérifier les limites horizontales
  playerX = Math.max(0, Math.min(playerX, gameWidth - player.clientWidth));
  // Vérifier les limites verticales
  playerY = Math.max(0, Math.min(playerY, gameHeight - player.clientHeight));

  player.style.left = playerX + "px";
  player.style.top = playerY + "px";
}

// Fonction pour ajouter un message au journal de mouvements
function logMovement(direction) {
  const movement = document.createElement("p");
  movement.textContent = `Déplacement: ${direction}`;

  // Limiter le nombre d'éléments dans le journal
  if (movementLog.childElementCount >= maxLogItems) {
    movementLog.removeChild(movementLog.firstChild);
  }

  movementLog.appendChild(movement);
}

// Gestion des événements de touche
document.addEventListener("keydown", (event) => {
  switch (event.key) {
    case "ArrowUp":
      playerY -= step;
      logMovement("Haut");
      break;
    case "ArrowDown":
      playerY += step;
      logMovement("Bas");
      break;
    case "ArrowLeft":
      playerX -= step;
      logMovement("Gauche");
      break;
    case "ArrowRight":
      playerX += step;
      logMovement("Droite");
      break;
  }

  updatePlayerPosition();
  checkCollisions(); // Vérifier les collisions à chaque mouvement du joueur
});

// Initialisation de la position du joueur
updatePlayerPosition();

// Fonction pour générer un nombre aléatoire dans une plage donnée
function getRandomNumber(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Fonction pour générer des monstres aléatoirement
function generateMonsters(numMonsters) {
  for (let i = 0; i < numMonsters; i++) {
    const monster = document.createElement("div");
    monster.classList.add("monster");

    // Position aléatoire pour le monstre
    const monsterX = getRandomNumber(10, gameWidth - monster.clientWidth);
    const monsterY = getRandomNumber(10, gameHeight - monster.clientHeight);
    monster.style.left = monsterX + "px";
    monster.style.top = monsterY + "px";

    // Ajoutez les attributs PV et force d'attaque au monstre
    const monsterHP = getRandomNumber(10, 50); // Points de vie du monstre (10 à 50)
    const monsterAttack = getRandomNumber(1, 50); // Force d'attaque du monstre (1 à 50)
    monster.setAttribute("data-hp", monsterHP);
    monster.setAttribute("data-attack", monsterAttack);

    gameContainer.appendChild(monster);
  }
}

// Appelez la fonction pour générer les monstres (par exemple, 5 monstres)
generateMonsters(9);

function checkCollisions() {
  const monsters = document.querySelectorAll(".monster");

  monsters.forEach((monster) => {
    const monsterRect = monster.getBoundingClientRect();
    const playerRect = player.getBoundingClientRect();

    if (
      monsterRect.left < playerRect.right &&
      monsterRect.right > playerRect.left &&
      monsterRect.top < playerRect.bottom &&
      monsterRect.bottom > playerRect.top
    ) {
      // Récupérez les PV et la force d'attaque du monstre
      const monsterHP = parseInt(monster.getAttribute("data-hp"));
      const monsterAttack = parseInt(monster.getAttribute("data-attack"));

      // Combat entre le joueur et le monstre
      if (playerAttack >= monsterHP) {
        // Le joueur a une force d'attaque suffisante pour vaincre le monstre
        monster.remove(); // Supprimer le monstre
        addToCombatLog("Le joueur attaque et inflige des dégâts.");
        addToCombatLog("Le monstre a été vaincu !");
      } else {
        // Le joueur n'a pas une force d'attaque suffisante, le monstre attaque
        playerHP -= monsterAttack; // Le joueur perd des PV

        // Ajoutez les actions au journal de combat
        addToCombatLog("Le joueur attaque et inflige des dégâts.");
        addToCombatLog(
          `Le monstre attaque et inflige ${monsterAttack} points de dégâts au joueur.`
        );
        addToCombatLog(`Le joueur a maintenant ${playerHP} points de vie.`);

        // Vérifiez si le joueur a été vaincu
        if (playerHP <= 0) {
          addToCombatLog("Vous avez été vaincu !");
          alert("Vous avez été vaincu !");
          // Réinitialisez la partie ici si nécessaire
          resetGame();
          return; // Sortez de la fonction pour arrêter le combat
        }

        // Vérifiez si le monstre a été vaincu
        if (monsterHP <= playerAttack) {
          monster.remove(); // Supprimer le monstre
          addToCombatLog("Le monstre a été vaincu !");
        }
      }
    }
  });
}

// Créez une référence à l'élément de journal de combat
const combatLog = document.getElementById("combat-log");

// Fonction pour ajouter une entrée dans le journal de combat
function addToCombatLog(message) {
  const logEntry = document.createElement("li");
  logEntry.textContent = message;
  combatLog.appendChild(logEntry);

  // Vérifier si le nombre d'entrées dépasse 10
  if (combatLog.childElementCount > 10) {
    combatLog.removeChild(combatLog.firstElementChild);
  }
}
