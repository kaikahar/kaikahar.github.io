<? 
    //database connection
    $mysql_connection = null;

    //response data from database
    $mysql_response = array();

    //status string
    $mysql_status = "";

    //automatically try to connect to DB
    mysqlConnect();

    function mysqlConnect()
    {
        global $mysql_connection, $mysql_response;

        //attempt connection (SERVER, USER, PWD, DB)
        $mysql_connection = new mysqli("localhost", "kkaiwusaer1_user1", "76146049k.k",
            "kkaiwusaer1_ica4TestDB");


        if ($mysql_connection->connect_error)
        {
            $mysql_response[] = 'Connect error (' .
                $mysql_connection->connect_errno .
                ') ' . $mysql_connection->connect_error;
            echo json_encode($mysql_response);
            die();
        }
    }

    //executes the provided select statement on the db
    //and returns the values
    function mysqlQuery($query)
    {
        global $mysql_connection, $mysql_response, $mysql_status;

        $results = false;

        if ($mysql_connection == null)
        {
            echo "no connection!";
            $mysql_status = "No active DB connection";
            return $results;
        }

        if (!($results = $mysql_connection->query($query)))
        {
            //if there were no results, return an error
            $mysql_response[] = "Query Error {$mysql_connection->errno} : " .
                "{$mysql_connection->error}";
            echo json_encode($mysql_response);
            die();
        }

        //if the query didn't error, send the results
        return $results;
    }
?>