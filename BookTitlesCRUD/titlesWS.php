<?php
    //get the info from the db when requested
    require_once "dbUtility.php";

    $showOnLoad = ExecuteQuery();

    $displayBooktype = getGenre();
    $displayBookAuthor = getAuthor();

    // extract all the POST global variables
    $authorID = isset($_POST['authorID']) ? $_POST['authorID'] : null;
    $titleID = isset($_POST['titleID']) ? $_POST['titleID'] : null;
    
    // sanitize the authorID value
    if (isset($authorID) && strlen($authorID) > 0)
    {
        $authorIDGiven = strip_tags($authorID);
        
    }
    else
    {
        $authorIDGiven = "";
    }

    // sanitize the titleID value
    if (isset($titleID) && strlen($titleID) > 0)
    {
        $tagToDelete = strip_tags($titleID);
    }
    else
    {
        $tagToDelete = "";
    }

    // call the ExecuteQuery() function everytime(display the table on load)
    
    // when the author ID is provided(the retrieve button is clicked), call the RetrieveJoinData with the author ID as a parameter
    $joinData = RetrieveJoinData($authorIDGiven);

    function RetrieveJoinData($whereparam)
    {
        global $mysql_connection; //comes from dbUtility!

        //remove security risks in the input!
        $whereparam = mysqli_real_escape_string($mysql_connection, $whereparam);

        $query = "SELECT *";
        $query .= " FROM authors";
        $query .= " JOIN titleauthor ON authors.au_id = titleauthor.au_id";
        $query .= " JOIN titles ON titleauthor.title_id = titles.title_id";
        $query .= " where authors.au_id like '$whereparam'";

        //form output in html format
        
        if ($results = mysqlQuery($query))
        {
            if (mysqli_num_rows($results) > 0) 
            {
                $rows_sum = mysqli_num_rows($results);
                $JoinDataOutput = "<form method='post'>";
                $JoinDataOutput .= "<table><tr><th>Action</th><th>Title Id</th><th>Title</th><th>Type</th><th>Price</th></tr>";
                //we have valid data coming back
                while ($row = $results->fetch_assoc())
                {
                    $JoinDataOutput .= "<tr>";
                    $JoinDataOutput .= "<td class='modButtons'><button type='button' class='DelBTN' name='delBTN' id='" . $row['title_id'] ."')'>Delete</button>
                                <button type='button' class='EditBTN' name='editBTN' id='" . $row['title_id'] ."'>Edit</button>
                    </td>";  // title id
                    $JoinDataOutput .= "<td id='{$row['title_id']}'>" . $row['title_id'] . "</td>";  // title id
                    $JoinDataOutput .= "<td id='{$row['title']}'>" . $row['title'] . "</td>"; // Title
                    $JoinDataOutput .= "<td id='{$row['type']}'>" . $row['type'] . "</td>";     // Type
                    $JoinDataOutput .= "<td id='{$row['price']}'>" . $row['price'] . "</td>";     // Type
                    $JoinDataOutput .= "</tr>";
                }
                $JoinDataOutput .= "</table>";
                $JoinDataOutput .= "<p> Retrieved: $rows_sum title records.</p>";
                $JoinDataOutput .= "</form>";
            }
            else
            {
                $JoinDataOutput = "";
                $JoinDataOutput .= "No records found.";
                $JoinDataOutput .= "</form>";
            }
        }
        else
            return "Query Error: $query";
        
        return $JoinDataOutput;
    }

    function getGenre()
    {
        global $mysql_connection; //comes from dbUtility
        $getbookTypesQuery = "SELECT DISTINCT type FROM titles";

        $booktypeOutput = "<select name='bookGenre' id='bookGenre'><option disabled selected>Choose a Book Genre</option>";

        if ($results1 = mysqlQuery($getbookTypesQuery))
        {
            while ($row1 = mysqli_fetch_assoc($results1))
            {
                $bookTypeArray[] = $row1['type'];
                
            }
        }
        foreach ($bookTypeArray as $value) {
            $booktypeOutput .= "<option>$value</option>";
        }
        $booktypeOutput .= "</select>";
        return $booktypeOutput;
    }

    function getAuthor()
    {
        global $mysql_connection; //comes from dbUtility
        $getAuthorQuery = "SELECT DISTINCT au_lname FROM authors";
        $bookAuthorOutput = "<select name='bookAuthors' id='bookAuthors' multiple='multiple' ><option disabled selected>Choose the Book's Author(s)</option>";
        
        if ($results2 = mysqlQuery($getAuthorQuery))
        {
            while ($row2 = mysqli_fetch_assoc($results2))
            {
                $authorArray[] = $row2['au_lname'];
            }
        }
        foreach ($authorArray as $value) {
            $bookAuthorOutput .= "<option>$value</option>";
        }
        $bookAuthorOutput .= "</select>";
        return $bookAuthorOutput;
    }

    // this is the function that displays the big table that shows on load
    function ExecuteQuery()
    {
        global $mysql_connection; //comes from dbUtility

        $query = "SELECT au_id, au_lname, au_fname, phone";
        $query .= " from authors";

        //form output in html format
        $TableOutput = "<table><tr><th>Get Books</th><th>Author ID</th><th>Last Name</th><th>First Name</th><th>Phone</th></tr>";

        if ($results = mysqlQuery($query))
        {
            $rows_sum = mysqli_num_rows($results);
            //we have valid data coming back
            while ($row = $results->fetch_assoc())
            {
                $TableOutput .= "<tr>";
                $TableOutput .= "<td><button type='button' class='retrieve-btn' name='" .$row['au_id'] . "' id='" . $row['au_id'] . "'>Retrieve</button></td>";
                $TableOutput .= "<td>" . $row['au_id'] . "</td>";        // author ID
                $TableOutput .= "<td>" . $row['au_lname'] . "</td>";     // last name
                $TableOutput .= "<td>" . $row['au_fname'] . "</td>";     // first name
                $TableOutput .= "<td>" . $row['phone'] . "</td>";        // phone
                $TableOutput .= "</tr>";
            }
        }
        else
            return "Query Error: $query";
        $TableOutput .= "</table>";
        $TableOutput .= "<p> Retrieved: $rows_sum user records.</p>";
        return $TableOutput;
    }

    // finally, put all the output messages in an array and echo it out after json_encoding it
    $messages = array(
        "message1" => $showOnLoad,
        "message2" => $joinData,
        "message3" => $displayBooktype,
        "message4" => $displayBookAuthor
    );
    
    echo json_encode($messages);