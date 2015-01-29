<?php
/*
 * @Author Rory Standley <rorystandley@gmail.com>
 * @Version 1.3
 * @Package Database
 */
class Database
{
    /*
     * Create variables for credentials to MySQL database
     * The variables have been declared as private. This
     * means that they will only be available with the
     * Database class
     */
    private $db_host = "localhost"; // Change as required
    private $db_user = "root"; // Change as required
    private $db_pass = ""; // Change as required
    private $db_name = "live_database"; // Change as required
    /*
     * Extra variables that are required by other function such as boolean con variable
     */
    private $con = false; // Check to see if the connection is active
    private $result = array(); // Any results from a query will be stored here
    private $myQuery = ""; // used for debugging process with SQL return
    private $numResults = ""; // used for returning the number of rows
    
	
	// Function to make connection to database
    public function connect()
    {
        if (!$this->con) {
            $myconn = @mysql_connect($this->db_host, $this->db_user, $this->db_pass); // mysql_connect() with variables defined at the start of Database class
            if ($myconn) {
                $seldb = @mysql_select_db($this->db_name, $myconn); // Credentials have been pass through mysql_connect() now select the database
                if ($seldb) {
                    $this->con = true;
                    return true; // Connection has been made return TRUE
                } else {
                    array_push($this->result, mysql_error());
                    return false; // Problem selecting database return FALSE
                }
            } else {
                array_push($this->result, mysql_error());
                return false; // Problem connecting return FALSE
            }
        } else {
            return true; // Connection has already been made return TRUE
        }
    }
    // Function to disconnect from the database
    public function disconnect()
    {
        // If there is a connection to the database
        if ($this->con) {
            // We have found a connection, try to close it
            if (@mysql_close()) {
                // We have successfully closed the connection, set the connection variable to false
                $this->con = false;
                // Return true tjat we have closed the connection
                return true;
            } else {
                // We could not close the connection, return false
                return false;
            }
        }
    }
	function __construct() {
       //
       
       
        "In the constructor";
	   //$this->connect();
   }
   
   function __destruct() {
      // echo "In the distructor";
	   $this->disconnect();
   }
	
    public function sql($sql)
    {
        //echo"Inside sql function";
		//echo $sql;
		#exit();
		$query         = @mysql_query($sql);
        #exit();#echo $query;
        $this->myQuery = $sql; // Pass back the SQL
        #exit();
        if ($query) {
            // If the query returns >= 1 assign the number of rows to numResults
            #usage reasoning: update doctor appointment statistics issue-> and it is a solution
			
			if(is_int($query))
			{
			$this->numResults = mysql_num_rows($query);
            // Loop through the query results by the number of rows returned
            for ($i = 0; $i < $this->numResults; $i++) {
                $r   = mysql_fetch_array($query);
                $key = array_keys($r);
                for ($x = 0; $x < count($key); $x++) {
                    // Sanitizes keys so only alphavalues are allowed
                    if (!is_int($key[$x])) {
                        if (mysql_num_rows($query) >= 1) {
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        } else {
                            $this->result = null;
                        }
                    }
                }
            }
			}
            return true; // Query was successful
        } else {
            array_push($this->result, mysql_error());
            return false; // No rows where returned
        }
    }
    // Function to SELECT from the database
    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
    {
        $this->connect();
		// Create query from the variables passed to the function
        //echo $where;
        $q = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($join != null) {
            $q .= ' JOIN ' . $join;
        }
        //echo $where ;
        
        if ($where != null) {
            $q .= ' WHERE ' . $where;
        }
        if ($order != null) {
            $q .= ' ORDER BY ' . $order;
        }
        if ($limit != null) {
            $q .= ' LIMIT ' . $limit;
        }
        #echo $q;
        //exit();
        //echo"<br>";
        $this->myQuery = $q; // Pass back the SQL
        // Check to see if the table exists
        if ($this->tableExists($table)) {
            // The table exists, run the query
            $query = @mysql_query($q);
            if ($query) {
                // If the query returns >= 1 assign the number of rows to numResults
                $this->numResults = mysql_num_rows($query);
                // Loop through the query results by the number of rows returned
                for ($i = 0; $i < $this->numResults; $i++) {
                    $r   = mysql_fetch_array($query);
                    $key = array_keys($r);
                    //print_r($key);
                    //echo"<br>";
                    for ($x = 0; $x < count($key); $x++) {
                        // Sanitizes keys so only alphavalues are allowed
                        if (!is_int($key[$x])) {
                            if (mysql_num_rows($query) >= 1) {
                                $this->result[$i][$key[$x]] = $r[$key[$x]];
                            } else {
                                $this->result = null;
                            }
                        }
                    }
                }
				$this->disconnect();
                return true; // Query was successful
            } else {
                array_push($this->result, mysql_error());
				$this->disconnect();
                return false; // No rows where returned
            }
        } else {
		    $this->disconnect();
            return false; // Table does not exist
        }
    }
    // Function to insert into the database
    public function insert($table, $params = array())
    {
        //echo "inside insert command";
        //echo $table;
        // Check to see if the table exists
        #if ($this->tableExists($table)) {
		if (1==1) {
			//echo"table exists";
			$this->connect();
            $sql           = 'INSERT INTO `' . $table . '` (`' . implode('`, `', array_keys($params)) . '`) VALUES ("' . implode('", "', $params) . '")';
            //echo $sql;
            $this->myQuery = $sql; // Pass back the SQL
            // Make the query to insert to the database
			//echo $sql;
            //exit();
            if ($ins = @mysql_query($sql)) {
                array_push($this->result, mysql_insert_id());
				$this->disconnect();
                return true; // The data has been inserted
            } else {
                array_push($this->result, mysql_error());
				$this->disconnect();
                return false; // The data has not been inserted
            }
        } else {
            echo"table dont exits";
			$this->disconnect();
			return false; // Table does not exist
        }
    }
    //Function to delete table or row(s) from database
    public function delete($table, $where = null)
    {
        // Check to see if table exists
        if ($this->tableExists($table)) {
			$this->connect();
            // The table exists check to see if we are deleting rows or table
            if ($where == null) {
                $delete = 'DELETE ' . $table; // Create query to delete table
            } else {
                $delete = 'DELETE FROM ' . $table . ' WHERE ' . $where; // Create query to delete rows
            }
            // Submit query to database
            if ($del = @mysql_query($delete)) {
                array_push($this->result, mysql_affected_rows());
                $this->myQuery = $delete; // Pass back the SQL
				$this->disconnect();
                return true; // The query exectued correctly
            } else {
                array_push($this->result, mysql_error());
				$this->disconnect();
                return false; // The query did not execute correctly
            }
        } else {
			$this->disconnect();
            return false; // The table does not exist
        }
    }
    // Function to update row in database
    public function update($table, $params = array(), $where)
    {
        //echo "Update command--";
		//echo $table;
		//print_r($params);echo $where; exit();
		// Check to see if table exists
       # if ($this->tableExists($table)) {
			if(1==1){
			//echo "Table Exists---";
			$this->connect();
            // Create Array to hold all the columns to update
            $args = array();
            foreach ($params as $field => $value) {
                // Seperate each column out with it's corresponding value
                $args[] = $field . '="' . $value . '"';
            }
            // Create the query
            $sql           = 'UPDATE ' . $table . ' SET ' . implode(',', $args) . ' WHERE ' . $where;
            // Make query to database
			#echo $sql;#exit();
            $this->myQuery = $sql; // Pass back the SQL
            if ($query = @mysql_query($sql)) {
                array_push($this->result, mysql_affected_rows());
				$this->disconnect();
                return true; // Update has been successful
            } else {
                array_push($this->result, mysql_error());
				$this->disconnect();
                return false; // Update has not been successful
            }
        } else {
		    //echo "Table DONT  Exists";
			$this->disconnect();
            return false; // The table does not exist
        }
    }
    // Private function to check if table exists for use with queries
    private function tableExists($table)
    {
        $tablesInDb = @mysql_query('SHOW TABLES FROM ' . $this->db_name . ' LIKE "' . $table . '"');
        if ($tablesInDb) {
            if (mysql_num_rows($tablesInDb) == 1) {
                return true; // The table exists
            } else {
                array_push($this->result, $table . " does not exist in this database");
                return false; // The table does not exist
            }
        }
    }
    // Public function to return the data to the user
    public function getResult()
    {
        $val          = $this->result;
        $this->result = array();
        return $val;
    }
    //Pass the SQL back for debugging
    public function getSql()
    {
        $val           = $this->myQuery;
        $this->myQuery = array();
        return $val;
    }
    //Pass the number of rows back
    public function numRows()
    {
        $val              = $this->numResults;
        $this->numResults = array();
        return $val;
    }
    // Escape your string
    public function escapeString($data)
    {
        return mysql_real_escape_string($data);
    }
}
