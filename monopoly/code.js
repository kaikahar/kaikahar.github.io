
const cells = ["go","mediterranean","cc1","baltic","incometax","readingrr","oriental","chance1","vermont","connecticut","jail","stcharles","electric","states","virginia","pennsylvaniarr","stjames","cc2","tennessee","newyork","freeparking","kentucky","chance2","indiana","illinois","borr","atlantic","ventnor","water","marvin","gotojail","pacific","northcarolina","cc3","pennsylvania","shortlinerr","chance3","parkplace","luxurytax","boardwalk"]
//put all unique user IDs in an array, to identify which cell the user is in and to access it's value
const takeAChanceText = ["Second Place in Beauty Contest: $10", "Bank Pays You Dividend of $50", "Repair your properties. You owe $250", "Speeding Fine: $15", "Holiday Fund Matures: Recieve $100", "Pay Hispital Fees: 100"];
// array for random events 
const takeAChanceMoney = [10, 50, -250, -15, 100, -100];

let MyPos = 0;              // current location of player 1
let MyPos2 = 0;             // current location of player 2
let DiceAccumulate = 0;     // accumulated sum of the diece which indicate player 1's destinaiton
let DiceAccumulate2 = 0;    // same variable for player 2
let player1turn = true;     // if = true, player1's turn to move, if = false, player2's turn to move. default to player1 when starting the game
let player1Balance = 3000;  // initial balance for both players
let player2Balance = 3000;

const player1Property = []; // empty array to store the colored properties that each player will purchase
const player2Property = [];

let player1Rr = 0;          // number of railroad owned by player1
let player2Rr = 0;

function playerShow()
{
  const allStops = document.querySelectorAll('section');
  for (const element of allStops) {
    element.innerHTML += "\t" + "$"+element.attributes.val.nodeValue;                                         // display the values of each property when the game starts
  }
  document.getElementById("go").innerHTML += '<img id="player1Avatar" src="images/player1.png">';             // display all images when starting the game
  document.getElementById("go").innerHTML += '<img id="player2Avatar" src="images/player2.png">';
  document.getElementById("player1").innerHTML += '<img id="player1Center" src="images/player1.png">';
  document.getElementById("player2").innerHTML += '<img id="player2Center" src="images/player2.png">';
  document.getElementById("player1Center").style.border = "3px dashed red";                                   // put red borders on player 1 when starting the game
  document.getElementById("player2Center").style.border = "";
}

function rollDice()
{

  let diceNum1 = Math.floor((Math.random() * 6) + 1);                             // random number between 1 to 6
  let diceNum2 = Math.floor((Math.random() * 6) + 1);                             // random number between 1 to 6
  document.querySelector("#dieID1").src = "images/dice" + diceNum1 + ".png";      // show the correct dice picture
  document.querySelector("#dieID2").src = "images/dice" + diceNum2 + ".png";      // show the correct dice picture
  let diceNumTotal = diceNum1 + diceNum2;                                         // total sum of 2 dices shown on screen
  

  if(player1turn)
  {

    if(DiceAccumulate + diceNumTotal > 40)        // for when the player makes a full circle and jumps pass last cell
    {
      DiceAccumulate += diceNumTotal;             // subtract 40 from the accumulated sum of all the dices we've throwed, which will restart the dice number from 0 
      DiceAccumulate -= 40;
      player1Balance += 200;                      // add 200$ to player1's balance for passing the "Go" point
      document.getElementById("player1amt").innerHTML = "$" + player1Balance;                            // display the balance
      timerID = window.setInterval(function() {jumping(DiceAccumulate, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval

    }
    else if(DiceAccumulate + diceNumTotal == 40)    // if player 1 lands exactly on the "Go" then restarts from 0
    {
      DiceAccumulate = 0;
      timerID = window.setInterval(function() {jumping(DiceAccumulate, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval
    }
    else
    {
      DiceAccumulate += diceNumTotal;               // for normal jumping
      timerID = window.setInterval(function() {jumping(DiceAccumulate, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval
    }
  }
  else  // player1turn = false, player2 turn same logic applies for different player variables
  {
    document.getElementById("player2Center").style.border = "3px dashed red";
    document.getElementById("player1Center").style.border = "";
    if(DiceAccumulate2 + diceNumTotal > 40)
    {
      DiceAccumulate2 += diceNumTotal;
      DiceAccumulate2 -= 40;
      player2Balance += 200;
      document.getElementById("player2amt").innerHTML = "$" + player2Balance;
      timerID = window.setInterval(function() {jumping(DiceAccumulate2, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval

    }
    else if(DiceAccumulate2 + diceNumTotal == 40)
    {
      DiceAccumulate2 = 0;
      timerID = window.setInterval(function() {jumping(DiceAccumulate2, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval
      
    }
    else
    {
      DiceAccumulate2 += diceNumTotal;
      timerID = window.setInterval(function() {jumping(DiceAccumulate2, diceNum1, diceNum2)}, 300);       // call the jumping function with 300ms interval
    }
  }
}


function jumping(whereToBe, diceNum1, diceNum2)
{
  if(player1turn == true)
  {
    document.getElementById("player1Avatar").remove();      // when moving cells, first remove the image from it's original location 
    if(whereToBe != MyPos)                                // if player position doesn't equal the destination, then check if the palyer is at the last cell, if not then increment one position
    {
      if(MyPos == 39)
      {
        MyPos = 0;
      }
      else
      {
        MyPos +=1;
      }
      document.getElementById("RollDice").disabled = true;    // disable the button while the player is moving
      document.getElementById(cells[MyPos]).innerHTML += '<img id="player1Avatar" src="images/player1.png">';   // display the image in the new cell
    }
    else
    {
      document.getElementById("RollDice").disabled = false;       // if the destination is reached, enable the button again, show the iamge and clear the timer
      document.getElementById(cells[MyPos]).innerHTML += '<img id="player1Avatar" src="images/player1.png">';
      window.clearInterval(timerID);  // stop and clear the timerID
      document.getElementById("player2Center").style.border = "3px dashed red";     // put the border on player 2
      document.getElementById("player1Center").style.border = "";
      Actions(MyPos, diceNum1, diceNum2);     // call the action function
      if(diceNum1 == diceNum2)
      {
        player1turn = true;       // if dice1 and dice2 shows the same number, then player1 runs again
        document.getElementById("player1Center").style.border = "3px dashed red";
        document.getElementById("player2Center").style.border = "";
      }
      else
      {
        player1turn = false;
      }
    }
  }
  else
  {
    document.getElementById("player2Avatar").remove();
    if(whereToBe != MyPos2)
    {
      if(MyPos2 == 39)
      {
        MyPos2 = 0;
      }
      else
      {
        MyPos2 +=1;
      }
      document.getElementById("RollDice").disabled = true;
      document.getElementById(cells[MyPos2]).innerHTML += '<img id="player2Avatar" src="images/player2.png">';
    }
    else
    {
      document.getElementById("RollDice").disabled = false;
      document.getElementById(cells[MyPos2]).innerHTML += '<img id="player2Avatar" src="images/player2.png">';
      window.clearInterval(timerID);  // stop and clear the timerID
      document.getElementById("player1Center").style.border = "3px dashed red";
      document.getElementById("player2Center").style.border = "";
      Actions(MyPos2, diceNum1, diceNum2);
      if(diceNum1 == diceNum2)
      {
        player1turn = false;
        document.getElementById("player2Center").style.border = "3px dashed red";
        document.getElementById("player1Center").style.border = "";
      }
      else
      {
        player1turn = true;
      }
    }
  }
}

// the Actions() function takes in the current position and 2 dice numbers all as integers
function Actions(myPosition, Dice1Num, Dice2Num)
{
  let currentCellName = cells[myPosition];                          // access the name of the cell(ID) via the array from top
  let action = document.getElementById(currentCellName).className;  // access the class Name of that cell
  let diceSumm = Dice1Num + Dice2Num; // add the dices

  if(player1Balance <= 0)
  {
    window.alert("player 1 lost :(");
    document.getElementById("RollDice").disabled = true;          // if either player's balance dips below 0, indicate the player is lost and disable the button
  }
  else if(player2Balance <= 0)
  {
    window.alert("player 2 lost :(");
    document.getElementById("RollDice").disabled = true;
  }

  if(player1turn)    // player1 scenario
  {
    if(action == "corner go")   // when player1 lands on "go" add 200 to their balance and display it
    {
      player1Balance += 200;
      document.getElementById("player1amt").innerHTML = "$" + player1Balance;
    }
    if(action == "reg cc" | action == "reg chance") // for random events, create a random number between 0 to 5 and chose the action from the array at top of page
    {
      let surprice = Math.floor((Math.random() * 5) + 0);
      player1Balance += takeAChanceMoney[surprice];         // reward or punish the user based on the random choice and display the new balance 
      document.getElementById("player1amt").innerHTML = "$" + player1Balance;
      window.alert(takeAChanceText[surprice]);
    }
    if(action == "reg tax")
    {
      let taxAmount = document.getElementById(currentCellName).attributes.val.nodeValue;  // for tax, subtract the tax amount
      player1Balance -= taxAmount;
      document.getElementById("player1amt").innerHTML = "$" + player1Balance;
    }
    if(action == "corner jail")
    {
      player1Balance -= 50;
      document.getElementById("player1amt").innerHTML = "$" + player1Balance;     // for jail subtract $50 
    }
    if(action == "corner goToJail")                                               // if the player lands on "Go to Jail", redirect them to the "jail" cell by changing the position value
    {
      window.alert("go to conner jail");
      document.getElementById("player1Avatar").remove();
      document.getElementById(cells[10]).innerHTML += '<img id="player1Avatar" src="images/player1.png">';
      MyPos = 10;
      DiceAccumulate = 10;
    }

    //*************************rail roads************************
    if(action == "reg rr")
    {
      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;      // if player1 lands on railroads, first check if it is owned by player2 
      let propertyValInt = parseInt(propertyValue);

      if(player2Property.includes(currentCellName))
      {

        player1Balance -= 25 * player2Rr;
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;                 // if it is, multiply 25 by the amount of railroad player 2 owns and subtract that amout from player1's balance
      }
      else if (player1Property.includes(currentCellName) == false)
      {
        player1Balance -= propertyValInt;
        player1Property.push(currentCellName);                                                  // if not, then buy that rail road, and push it into the array of properties player 1 owns, update the amount of rail road owned, and display new balance
        player1Rr +=1;
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;
        document.getElementById(currentCellName).style.backgroundColor = "rgb(149, 216, 158)";  // change the background color of the rail road to indicate the owner
      }
    }

    //*************************utilities************************


    if(action == "reg utility")
    {
      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;  // if player 1 lands on utilities, again check if it's owned by player 2
      let propertyValInt = parseInt(propertyValue);


      if(player2Property.includes(currentCellName))
      {
        player1Balance -= 5 * diceSumm;                                                       // if they do, then multiply the sum of the dice numbers by 5 and subtract that from the balance
        
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;
      }
      else if (player1Property.includes(currentCellName) == false)
      {
        player1Balance -= propertyValInt;
        player1Property.push(currentCellName);
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;               // it it's not, then buy the property using the same procedure as rail roads
        document.getElementById(currentCellName).style.backgroundColor = "rgb(149, 216, 158)";
      }

    }

    //*************************coloured properties************************
    let player1Visits = [{"property": "reg brown",    "NoOfVisit": 0},                      // create an array of objects that shows each property and how many times it was visited
    {"property": "reg lightblue","NoOfVisit": 0},
    {"property": "reg purple",   "NoOfVisit": 0},
    {"property": "reg orange",   "NoOfVisit": 0},
    {"property": "reg red",      "NoOfVisit": 0},
    {"property": "reg yellow",   "NoOfVisit": 0},
    {"property": "reg green",    "NoOfVisit": 0},
    {"property": "reg blue",     "NoOfVisit": 0}];

    if(action == "reg brown" | action == "reg lightblue" | action == "reg purple" | action == "reg orange" | action == "reg red" | action == "reg yellow" | action == "reg green" | action == "reg blue")
    {
      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;    // if player1 lands on any colored properties, check if player 2 owns that property
      if(player2Property.includes(currentCellName))
      {
        let currentVisit = player1Visits.find(places => places.property === action);          // if it is, then find how many times player1 has already visited those proeprties
        let numOfCurrentVisit = currentVisit.NoOfVisit;
        let tax = propertyValue * 0.1 * (1.2 **numOfCurrentVisit);                            // calculate the rent amount as 10% of property value with 20% increment with each visit
        numOfCurrentVisit +=1;
        player1Balance -= tax;
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;             // update the visited time, and display the new balance after deducting the rent
      }
      else if(player1Property.includes(currentCellName) == false)                           // if player2 does not own it, then purchase the property usign the same methods
      { 
        player1Balance -= propertyValue;
        player1Property.push(currentCellName);
        document.getElementById("player1amt").innerHTML = "$" + player1Balance;
        document.getElementById(currentCellName).style.backgroundColor = "rgb(149, 216, 158)";    // display the new balance and marked the property with background color change
      }

    }
  }

  else    // player 2 scenario, same logic applies with different variable names
  {
    if(action == "corner go")
    {
      player2Balance += 200;
      document.getElementById("player2amt").innerHTML = "$" + player2Balance;
    }
    if(action == "reg cc" | action == "reg chance")
    {
      let surprice = Math.floor((Math.random() * 5) + 0);
      player2Balance += takeAChanceMoney[surprice];
      document.getElementById("player2amt").innerHTML = "$" + player2Balance;
      window.alert(takeAChanceText[surprice]);
    }
    if(action == "reg tax")
    {
      let taxAmount = document.getElementById(currentCellName).attributes.val.nodeValue;
      player2Balance -= taxAmount;
      document.getElementById("player2amt").innerHTML = "$" + player2Balance;
    }
    if(action == "corner jail")
    {
      player2Balance -= 50;
      document.getElementById("player2amt").innerHTML = "$" + player2Balance;
    }
    if(action == "corner goToJail")
    {
      window.alert("go to conner jail");
      document.getElementById("player2Avatar").remove();
      document.getElementById(cells[10]).innerHTML += '<img id="player2Avatar" src="images/player2.png">';
      MyPos2 = 10;
      DiceAccumulate2 = 10;
    }

    //*************************rail roads************************
    if(action == "reg rr")
    {
      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;
      let propertyValInt = parseInt(propertyValue);

      if(player1Property.includes(currentCellName))
      {
        player2Balance -= 25 * player1Rr;
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
      }
      else if (player2Property.includes(currentCellName) == false)
      {
        player2Balance -= propertyValInt;
        player2Property.push(currentCellName);
        player2Rr +=1;
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
        document.getElementById(currentCellName).style.backgroundColor = "rgb(236, 173, 231)";
      }
    }

    //*************************utilities************************


    if(action == "reg utility")
    {
      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;
      let propertyValInt = parseInt(propertyValue);


      if(player1Property.includes(currentCellName))
      {
        player2Balance -=  5 * diceSumm;
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
      }
      else if (player2Property.includes(currentCellName) == false)
      {
        player2Balance -= propertyValInt;
        player2Property.push(currentCellName);
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
        document.getElementById(currentCellName).style.backgroundColor = "rgb(236, 173, 231)";
      }

    }


    //*************************coloured properties************************
    let player2Visits = [{"property": "reg brown",    "NoOfVisit": 0},
                         {"property": "reg lightblue","NoOfVisit": 0},
                         {"property": "reg purple",   "NoOfVisit": 0},
                         {"property": "reg orange",   "NoOfVisit": 0},
                         {"property": "reg red",      "NoOfVisit": 0},
                         {"property": "reg yellow",   "NoOfVisit": 0},
                         {"property": "reg green",    "NoOfVisit": 0},
                         {"property": "reg blue",     "NoOfVisit": 0}];

    if(action == "reg brown" | action == "reg lightblue" | action == "reg purple" | action == "reg orange" | action == "reg red" | action == "reg yellow" | action == "reg green" | action == "reg blue")
    {

      let propertyValue = document.getElementById(currentCellName).attributes.val.nodeValue;
      if(player1Property.includes(currentCellName))
      {
        let currentVisit2 = player2Visits.find(places => places.property === action);
        let numOfCurrentVisit2 = currentVisit2.NoOfVisit;
        let tax2 = propertyValue * 0.1 * (1.2 **numOfCurrentVisit2);
        numOfCurrentVisit2 +=1;
        player2Balance -= tax2;
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
      }
      else if (player2Property.includes(currentCellName) == false)
      {
        player2Balance -= propertyValue;
        player2Property.push(currentCellName);
        document.getElementById("player2amt").innerHTML = "$" + player2Balance;
        document.getElementById(currentCellName).style.backgroundColor = "rgb(236, 173, 231)";
      }
    }
  }
}



window.onload = () =>
{
  playerShow();                                             // show the player when the program loads
  document.getElementById("RollDice").onclick = () => {     // when the button is clicked, call the rollDice function
    rollDice();
  }
}