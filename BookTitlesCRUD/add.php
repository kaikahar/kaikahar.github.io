<?php
    //get the info from the db when requested
    require_once "dbUtility.php";


    $newBookID = isset($_POST['newBookID']) ? $_POST['newBookID'] : null;
    $newBookTitle = isset($_POST['newBookTitle']) ? $_POST['newBookTitle'] : null;
    $newBookType = isset($_POST['newBookType']) ? $_POST['newBookType'] : null;
    $newBookPrice = isset($_POST['newBookPrice']) ? $_POST['newBookPrice'] : null;
    $newBookAuthors = isset($_POST['newBookAuthors']) ? $_POST['newBookAuthors'] : null;


    AddNewBookToDB($newBookID, $newBookTitle, $newBookType, $newBookPrice, $newBookAuthors);

    function AddNewBookToDB($idOfNewBook, $TitleOfNewBook, $TypeOfNewBook, $PriceOfNewBook, $AuthorsOfNewBook)
    {
         //comes from dbUtility!
        global $mysql_connection;

        $idOfNewBook = mysqli_real_escape_string($mysql_connection, $idOfNewBook);
        $TitleOfNewBook = mysqli_real_escape_string($mysql_connection, $TitleOfNewBook);
        $TypeOfNewBook = mysqli_real_escape_string($mysql_connection, $TypeOfNewBook);
        $PriceOfNewBook = mysqli_real_escape_string($mysql_connection, $PriceOfNewBook);
        $PriceOfNewBookInt = intval($PriceOfNewBook);

        $authorCount = count($AuthorsOfNewBook);
        $royaltyPerAuthor = (int) (100 / $authorCount);
       

       $addTitleQuery = "INSERT INTO titles (title_id, title, type, price)";
       $addTitleQuery .= " VALUES ('$idOfNewBook', '$TitleOfNewBook', '$TypeOfNewBook', '$PriceOfNewBookInt')";
       mysqlQuery($addTitleQuery);


        // Get author ID based on last name
       foreach ($AuthorsOfNewBook as $key => $value) // loop through the authors array and assign a variable for each one i.e "author0", "author1" ...
       {
        $getAuthorIdQuery = "SELECT au_id FROM authors WHERE au_lname = '$value';";
        ${"author" . $key} = mysqlQuery($getAuthorIdQuery);

        if (mysqli_num_rows(${"author" . $key}) > 0) {
            // Insert new record into titleauthor table
            $row = mysqli_fetch_assoc(${"author" . $key});
            $author_id = $row["au_id"];
            
            $sql = "INSERT INTO titleauthor (au_id, title_id, au_ord, royaltyper)
            VALUES ('$author_id', '$idOfNewBook', 1, $royaltyPerAuthor)";
          
            if (mysqlQuery($sql)) 
            {
              echo "New record created successfully in titleauthor table";
            }
          } 
       }
    }