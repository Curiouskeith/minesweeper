<?php
session_start();

// Initialize game variables
$attempts = 3;
$player_turn = 'player1';
$player1_bomb_number = [];
$player2_bomb_number = [];
$winner = false;

// Check if the game has started
if (!isset($_SESSION['player1_bomb_number']) || !isset($_SESSION['player2_bomb_number'])) {
    $_SESSION['player1_bomb_number'] = [];
    $_SESSION['player2_bomb_number'] = [];
}

// Check if a player is setting the bomb number
if (isset($_POST['set_bomb'])) {
    $bomb_number = $_POST['bomb_number'];
    
    if ($player_turn == 'player1') {
        array_push($_SESSION['player1_bomb_number'], $bomb_number);
    } else {
        array_push($_SESSION['player2_bomb_number'], $bomb_number);
    }
    
    echo "Bomb number has been set. Let the guessing begin!";
}

// Check if a player is guessing
if (isset($_POST['guess'])) {
    $guess = $_POST['guess'];

    // Check if the guess is correct
    if (($player_turn == 'player1' && in_array($guess, $_SESSION['player2_bomb_number'])) ||
        ($player_turn == 'player2' && in_array($guess, $_SESSION['player1_bomb_number']))) {
        $winner = true;
        echo "Congratulations! You guessed the bomb number.";
        unset($_SESSION['player1_bomb_number']);
        unset($_SESSION['player2_bomb_number']); // Reset the game
    } else {
        $attempts--;
        if ($attempts > 0) {
            echo "Incorrect guess. You have " . $attempts . " attempts left.";
        } else {
            echo "Game over. The bomb numbers were: Player 1 - " . implode(', ', $_SESSION['player1_bomb_number']) . ", Player 2 - " . implode(', ', $_SESSION['player2_bomb_number']);
            unset($_SESSION['player1_bomb_number']);
            unset($_SESSION['player2_bomb_number']); // Reset the game
            echo "It's a tie!";
        }
    }
    
    // Switch player turn after each guess
    $player_turn = ($player_turn == 'player1') ? 'player2' : 'player1';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Two-Player Minesweeper Game</title>
</head>
<body>
    
    <?php if (!$winner) { ?>
        <h2><?php echo ucfirst($player_turn); ?>: Set the Bomb Number</h2>
        <form method="post">
            <label for="bomb_number">Enter the bomb number (1-10):</label>
            <input type="number" name="bomb_number" min="1" max="10" required>
            <button type="submit" name="set_bomb">Set Bomb Number</button>
        </form>
        
        <h2><?php echo ucfirst(($player_turn == 'player1') ? 'Player 2' : 'Player 1'); ?>: Guess the Bomb Number</h2>
        <form method="post">
            <label for="guess">Enter your guess (1-10):</label>
            <input type="number" name="guess" min="1" max="10" required>
            <button type="submit">Submit Guess</button>
        </form>
    <?php } ?>
</body>
</html>