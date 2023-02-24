<?php
session_start();
$displayGrid = "";

$message = "Enter your names below";
if(isset($_POST['newGame'])){
    // Check if both player names are filled out
    if(isset($_POST['player1']) && isset($_POST['player2']) && !empty($_POST['player1']) && !empty($_POST['player2'])){
        // Set session variables for player names

        $player1 = $_POST['player1'];
        $player2 = $_POST['player2'];

        $startingPlayer = rand(0, 1) == 0 ? $player1 : $player2;
        $startingColor = rand(0, 1) == 0 ? "tan" : "DarkRed";

        $_SESSION['player1'] = $player1;
        $_SESSION['player2'] = $player2;
        $_SESSION['currentPlayer'] = $startingPlayer;
        $_SESSION['currentColor'] = $startingColor;
        $_SESSION['gameboard'] = array(
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 1, 2, 0, 0, 0),
            array(0, 0, 0, 2, 1, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0)
        );
        $message = "$startingPlayer plays first with the $startingColor stones";


        if(isset($_SESSION['gameboard']))
        {
            for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                $value = $_SESSION['gameboard'][$row][$col];

                $displayGrid .= "<input type='text' class='boxes' id='$row-$col' ";

                if ($value == 1) {
                    $displayGrid .= "style='color:tan;' value='&#9679;";
                } else if ($value == 2) {
                    $displayGrid .= "style='color:DarkRed;' value='&#9679;";
                } else {
                    $displayGrid .= "";
                }
                $displayGrid .= "' readonly>";
            }
            $displayGrid .= "<br/>";
            }
        }
    }
    else{
        // Display error message
        $message = "Both player names are required!";
    }
}

if(isset($_POST['QuitGame']))
{
    // End the session
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab1 - Othello</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="index.js"></script>
</head>
<body>
    <header><h1>CMPE2550 - Lab1 - Othello</h1></header>

    <section id="bigSec">

        <section id="midSec">
            <h3 id="prompt"> <?php echo $message; ?></h3>
            <form method="post">
                <input type="text" name="player1" id="player1" placeholder="player 1 Name here" value="<?php echo isset($_SESSION['player1']) ? $_SESSION['player1'] : '' ?>">
                <input type="text" name="player2" id="player2" placeholder="player 2 Name here" value="<?php echo isset($_SESSION['player2']) ? $_SESSION['player2'] : '' ?>">
                <button id="newGame" name="newGame" value="GameStart" type="submit">New Game</button>
                <button name="QuitGame" type="submit">Quit Game</button>
            
        </section>
        
        <hr>

        <div id="player1Score"></div>
        <div id="player2Score"></div>

        <section id="smallSec">
            <?php echo $displayGrid;?>
        </section>
    </section>
    <footer>2023 KahaerMadeItLLC</footer>
</body>
</html>