<?php
    //get the info from the db when requested
    require_once "dbUtility.php";



    $authorID = isset($_POST['authorID']) ? $_POST['authorID'] : null;
    $titleID = isset($_POST['titleID']) ? $_POST['titleID'] : null;


    // sanitize the titleID value
    if (isset($titleID) && strlen($titleID) > 0)
    {
        $tagToEdit = strip_tags($titleID);
    }
    else
    {
        $tagToEdit = "";
    }
    
    if (isset($authorID) && strlen($authorID) > 0)
    {
        $authorIDGiven = strip_tags($authorID);
        
    }
    else
    {
        $authorIDGiven = "";
    }


    echo EditMenu($authorIDGiven, $tagToEdit);

    
    function EditMenu($idOfAuthor, $idOfTitle)
    {
        global $mysql_connection; //comes from dbUtility!
        //remove security risks in the input!
        $idOfAuthor = mysqli_real_escape_string($mysql_connection, $idOfAuthor);
        $query = "SELECT *";
        $query .= " FROM authors";
        $query .= " JOIN titleauthor ON authors.au_id = titleauthor.au_id";
        $query .= " JOIN titles ON titleauthor.title_id = titles.title_id";
        $query .= " where authors.au_id like '$idOfAuthor'";
        $query2 = "SELECT DISTINCT type FROM titles";
        
        //form output in html format

        if ($results2 = mysqlQuery($query2))
        {
            while ($row2 = mysqli_fetch_assoc($results2))
            {
                $bookTypeArray[] = $row2['type'];
            }
        }
        
        if ($results = mysqlQuery($query))
        {
            if (mysqli_num_rows($results) > 0) 
            {
                $rows_sum = mysqli_num_rows($results);
                $EditDataOutput = "<form method='post'>";
                $EditDataOutput .= "<table><tr><th>Action</th><th>Title Id</th><th>Title</th><th>Type</th><th>Price</th></tr>";
                //we have valid data coming back
                while ($row = $results->fetch_assoc())
                {
                    if($idOfTitle == $row['title_id'])
                    {
                        $EditDataOutput .= "<tr>";
                        $EditDataOutput .= "<td class='modButtons'><button type='button' class='udtBTN' name='udtBTN' id='" . $row['title_id'] ."')'>Update</button>
                                    <button type='button' class='cnlBTN' name='cnlBTN' id='" . $row['title_id'] ."'>Cancel</button>
                        </td>";  // title id
                        $EditDataOutput .= "<td id='{$row['title_id']}'>" . $row['title_id'] . "</td>";  // title id
                        $EditDataOutput .= "<td><input type='text' name='title' id='title' value='{$row['title']}'></td>";
                        $EditDataOutput .= "<td><select name='type' id='type'>";
                        foreach ($bookTypeArray as $value) {
                            $selected = ($row['type'] == $value ? 'selected' : '');
                            $EditDataOutput .= "<option value='$value' $selected>$value</option>";
                        }
                        $EditDataOutput .= "</select></td>";
                        $EditDataOutput .= "<td><input type='text' name='price' id='price' value='{$row['price']}'></td>";
    
                        $EditDataOutput .= "</tr>";
                    }
                    else
                    {
                        $EditDataOutput .= "<tr>";
                        $EditDataOutput .= "<td class='modButtons'><button type='button' class='DelBTN' name='delBTN' id='" . $row['title_id'] ."')'>Delete</button>
                                    <button type='button' class='EditBTN' name='editBTN' id='" . $row['title_id'] ."'>Edit</button>
                        </td>";  // title id
                        $EditDataOutput .= "<td id='{$row['title_id']}'>" . $row['title_id'] . "</td>";  // title id
                        $EditDataOutput .= "<td id='{$row['title']}'>" . $row['title'] . "</td>"; // Title
                        $EditDataOutput .= "<td id='{$row['type']}'>" . $row['type'] . "</td>";     // Type
                        $EditDataOutput .= "<td id='{$row['price']}'>" . $row['price'] . "</td>";     // Type
                        $EditDataOutput .= "</tr>";
                    }
                }
                $EditDataOutput .= "</table>";
                $EditDataOutput .= "<p> Retrieved: $rows_sum title records.</p>";
                $EditDataOutput .= "</form>";
            }
            else
            {
                $EditDataOutput = "";
                $EditDataOutput .= "No records found.";
                $EditDataOutput .= "</form>";
            }
        }
        else
            return "Query Error: $query";
        
        return $EditDataOutput;
    }