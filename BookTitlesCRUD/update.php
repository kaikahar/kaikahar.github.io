<?php
    //get the info from the db when requested
    require_once "dbUtility.php";

    $authorID = isset($_POST['authorID']) ? $_POST['authorID'] : null;
    $titleID = isset($_POST['titleID']) ? $_POST['titleID'] : null;
    $newTitle = isset($_POST['titleName']) ? $_POST['titleName'] : null;
    $newType = isset($_POST['typeName']) ? $_POST['typeName'] : null;
    $newPrice = isset($_POST['price']) ? $_POST['price'] : null;


    echo updateWithNewData($authorID, $titleID, $newTitle, $newType, $newPrice);

    function updateWithNewData($IdOfAuthor, $IdOftitle, $nameOfTitle, $nameOfType, $numOfPrice)
    {
        //comes from dbUtility!
        global $mysql_connection;

        //remove security risks in the input!
        $IdOftitle = mysqli_real_escape_string($mysql_connection, $IdOftitle);
        $nameOfTitle = mysqli_real_escape_string($mysql_connection, $nameOfTitle);
        $nameOfType = mysqli_real_escape_string($mysql_connection, $nameOfType);
        $numOfPrice = mysqli_real_escape_string($mysql_connection, $numOfPrice);
        $IdOfAuthor = mysqli_real_escape_string($mysql_connection, $IdOfAuthor);

        $updateQuery = "UPDATE titles";
        $updateQuery .= " SET title = '$nameOfTitle', type = '$nameOfType', price = '$numOfPrice'";
        $updateQuery .= " WHERE titles.title_id = '$IdOftitle'";

        $displayChangedResults = "SELECT *";
        $displayChangedResults .= " FROM authors";
        $displayChangedResults .= " JOIN titleauthor ON authors.au_id = titleauthor.au_id";
        $displayChangedResults .= " JOIN titles ON titleauthor.title_id = titles.title_id";
        $displayChangedResults .= " where authors.au_id like '$IdOfAuthor'";

        mysqlQuery($updateQuery);

        if ($results = mysqlQuery($displayChangedResults))
        {
            if (mysqli_num_rows($results) > 0) 
            {
                $rows_sum = mysqli_num_rows($results);
                $UpdateDataOutput = "<form method='post'>";
                $UpdateDataOutput .= "<table><tr><th>Action</th><th>Title Id</th><th>Title</th><th>Type</th><th>Price</th></tr>";
                //we have valid data coming back
                while ($row = $results->fetch_assoc())
                {
                    $UpdateDataOutput .= "<tr>";
                    $UpdateDataOutput .= "<td class='modButtons'><button type='button' class='DelBTN' name='delBTN' id='" . $row['title_id'] ."')'>Delete</button>
                                <button type='button' class='EditBTN' name='editBTN' id='" . $row['title_id'] ."'>Edit</button>
                    </td>";  // title id
                    $UpdateDataOutput .= "<td id='{$row['title_id']}'>" . $row['title_id'] . "</td>";  // title id
                    $UpdateDataOutput .= "<td id='{$row['title']}'>" . $row['title'] . "</td>"; // Title
                    $UpdateDataOutput .= "<td id='{$row['type']}'>" . $row['type'] . "</td>";     // Type
                    $UpdateDataOutput .= "<td id='{$row['price']}'>" . $row['price'] . "</td>";     // Type
                    $UpdateDataOutput .= "</tr>";
                }
                $UpdateDataOutput .= "</table>";
                $UpdateDataOutput .= "<p> Retrieved: $rows_sum title records.</p>";
                $UpdateDataOutput .= "</form>";
            }
            else
            {
                $UpdateDataOutput = "";
                $UpdateDataOutput .= "No records found.";
                $UpdateDataOutput .= "</form>";
            }
        }
        else
            return "Query Error: $displayChangedResults";
        
        return $UpdateDataOutput;
    }