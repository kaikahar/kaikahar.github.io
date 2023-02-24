<?php
    // this pages is called with every click of the grid
    // start sessions and initialize soem variables
    session_start();
    $displayGrid = "";
    $player1Score = 0;
    $player2Score = 0;

    // check the id values of the textbox clicked, and store them in the rowNum and colNum variables
    if (isset($_POST["id"]) && strlen($_POST["id"]) > 0)
    {
        $rowNum = strip_tags($_POST["id"][0]);
        $colNum = strip_tags($_POST["id"][2]);
    }

    // taking turn for the user
    if($_SESSION['currentColor'] == "tan")
    {
        $_SESSION['currentColor'] = "DarkRed";
        $_SESSION['gameboard'][$rowNum][$colNum] = 1;
    }
    else if($_SESSION['currentColor'] == "DarkRed")
    {
        $_SESSION['currentColor'] = "tan";
        $_SESSION['gameboard'][$rowNum][$colNum] = 2;
    }


    if($_SESSION['currentPlayer'] == $_SESSION['player1'])
    {
        $_SESSION['currentPlayer'] = $_SESSION['player2'];
    }
    else if($_SESSION['currentPlayer'] == $_SESSION['player2'])
    {
        $_SESSION['currentPlayer'] = $_SESSION['player1'];
    }


    // ---------------- check for cells to flip horizontally ----------------
    $currentColor = $_SESSION['currentColor'];
    $playerNum = $currentColor == "tan" ? 2 : 1;
    $rowsToFlip = array();
    $colsToFlip = array();
    $flipped = false;

    // check for cells to flip horizontally to the left
    for($c = $colNum - 1; $c >= 0; $c--) {
        if($_SESSION['gameboard'][$rowNum][$c] == 0) {
            break;      // If an empty cell is found (0), the loop breaks.
        }
        if($_SESSION['gameboard'][$rowNum][$c] == $playerNum) {
            $flipped = true;    // If a cell with the same value as the current player is found, the loop sets $flipped to true.
            break;
        }
        $colsToFlip[] = $c;     // If a cell with the opposite player's value is found, the column number is stored in $colsToFlip.
    }

    if($flipped) {              // If $flipped is true, the loop goes through each column in $colsToFlip and sets their values to the current player's value.
        foreach($colsToFlip as $c) {
            $_SESSION['gameboard'][$rowNum][$c] = $playerNum;
        }
    }
    $rowsToFlip = array();
    $colsToFlip = array();
    $flipped = false;

    // check for cells to flip horizontally to the right
    for($c = $colNum + 1; $c <= 7; $c++) {
        if($_SESSION['gameboard'][$rowNum][$c] == 0) {
            break;
        }
        if($_SESSION['gameboard'][$rowNum][$c] == $playerNum) {
            $flipped = true;
            break;
        }
        $colsToFlip[] = $c;
    }

    if($flipped) {
        foreach($colsToFlip as $c) {
            $_SESSION['gameboard'][$rowNum][$c] = $playerNum;
        }
    }
    $colsToFlip = array();
    $rowsToFlip = array();
    $flipped = false;

// ---------------- check for cells to flip vertically ----------------
    for($r = $rowNum - 1; $r >= 0; $r--) {
        if($_SESSION['gameboard'][$r][$colNum] == 0) {
            break;
        }
        if($_SESSION['gameboard'][$r][$colNum] == $playerNum) {
            $flipped = true;
            break;
        }
        $rowsToFlip[] = $r;
    }

    if($flipped) {
        foreach($rowsToFlip as $r) {
            $_SESSION['gameboard'][$r][$colNum] = $playerNum;
        }
    }
    $colsToFlip = array();
    $rowsToFlip = array();
    $flipped = false;

    // check for cells to flip vertically
    for($r = $rowNum + 1; $r <= 7; $r++) {
        if($_SESSION['gameboard'][$r][$colNum] == 0) {
            break;
        }
        if($_SESSION['gameboard'][$r][$colNum] == $playerNum) {
            $flipped = true;
            break;
        }
        $rowsToFlip[] = $r;
    }

    if($flipped) {
        foreach($rowsToFlip as $r) {
            $_SESSION['gameboard'][$r][$colNum] = $playerNum;
        }
    }
    $colsToFlip = array();
    $rowsToFlip = array();
    $flipped = false;


    // ---------------- display the updated gameboard ---------------- 
   if(isset($_SESSION['gameboard']))
    {
        $gameboard = $_SESSION['gameboard'];
        for ($row = 0; $row < 8; $row++) {
        for ($col = 0; $col < 8; $col++) {
            $value = $gameboard[$row][$col];

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

    // ---------------- update score of each player ----------------
    for ($row = 0; $row < 7; $row++) {
        for ($col = 0; $col < 7; $col++) {
            if ($_SESSION['gameboard'][$row][$col] == 2) {
                $player1Score+=1;
                error_log($_SESSION['gameboard'][$row][$col]); 
            }
            if ($_SESSION['gameboard'][$row][$col] == 1) {
                $player2Score+=1;
              //  error_log($player2Score); 
            }
        }
    }

    

    // ---------------- encode the information into a json string ----------------
    $messages = array(
        "message1" => $_SESSION['currentPlayer'],
        "message2" => $_SESSION['currentColor'],
        "message3" => $player1Score,
        "message4" => $player2Score,
        "message5" => $displayGrid,
        "message6" => $_SESSION['player1'],
        "message7" => $_SESSION['player2']
    );
    echo json_encode($messages);