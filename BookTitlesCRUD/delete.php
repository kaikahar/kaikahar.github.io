<?php
    //get the info from the db when requested
    require_once "dbUtility.php";

    $authorID = isset($_POST['authorID']) ? $_POST['authorID'] : null;
    $titleID = isset($_POST['titleID']) ? $_POST['titleID'] : null;


    // sanitize the titleID value
    if (isset($titleID) && strlen($titleID) > 0)
    {
        $tagToDelete = strip_tags($titleID);
    }
    else
    {
        $tagToDelete = "";
    }
    
    if (isset($authorID) && strlen($authorID) > 0)
    {
        $authorIDGiven = strip_tags($authorID);
        
    }
    else
    {
        $authorIDGiven = "";
    }


    echo DeleteRecord($tagToDelete, $authorIDGiven);


    function DeleteRecord($tagToDelete, $authorIDToShow)
    {
        global $mysql_connection; //comes from dbUtility!
        $titleID = mysqli_real_escape_string($mysql_connection, $tagToDelete);
        $authorIDToShow = mysqli_real_escape_string($mysql_connection, $authorIDToShow);

        $TitleDeletequery = "DELETE FROM titles where title_id LIKE '$titleID'";
        $TitleAuthorDeletequery = "DELETE FROM titleauthor where titleauthor.title_id LIKE '$titleID'";

        $displayTitles = "SELECT *";
        $displayTitles .= " FROM authors";
        $displayTitles .= " JOIN titleauthor ON authors.au_id = titleauthor.au_id";
        $displayTitles .= " JOIN titles ON titleauthor.title_id = titles.title_id";
        $displayTitles .= " where authors.au_id like '$authorIDToShow'";


        mysqlQuery($TitleDeletequery);
        mysqlQuery($TitleAuthorDeletequery);

        if ($results = mysqlQuery($displayTitles))
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
            return "Query Error: $displayTitles";

        return $JoinDataOutput;
    }