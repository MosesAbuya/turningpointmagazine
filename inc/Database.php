<?php


class Database
{
        public $servername;
        public $username;
        public $password;
        public $dbname;
        public $tablename;
        public $con;


        // class constructor
    public function __construct(
        $dbname = "turning2_turningpoint1",
        $tablename = "products",
        $servername = "localhost",
        $username = "turning2_turningpoint2",
        $password = "Amo20.03"
    )
    {
      $this->dbname = $dbname;
      $this->tablename = $tablename;
      $this->servername = $servername;
      $this->username = $username;
      $this->password = $password;

      // create connection
        $this->conn = new mysqli($servername, $username, $password);

        // Check connection
        if (!$this->conn){
            die("Connection failed : " . $this->conn->error);
        }

        // query
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

        // execute query
        if($this->conn->query($sql)){

            $this->conn = new mysqli($servername, $username, $password, $dbname);

            // sql to create new table
            $sql = " CREATE TABLE IF NOT EXISTS `products` (
                `id` int(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `code` varchar(50) NOT NULL,
                `name` text NOT NULL,
                `description` text NOT NULL,
                `prev_price` float(12,2) NOT NULL DEFAULT 0.00,
                `current_price` float(12,2) NOT NULL DEFAULT 0.00,
                `img_path` text NOT NULL,
                `date_created` datetime NOT NULL DEFAULT current_timestamp(),
                `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
              );";

            $this->conn->query($sql);
            if ($this->conn->error){
                echo "Error creating table : " . $this->conn->error;
            }

            // Insert Default Products if db is empty:
            $check_db_data = $this->conn->query("SELECT `id` FROM `{$this->tablename}` ")->num_rows;
            if($check_db_data <= 0){
               $insert_sql = "INSERT INTO `{$this->tablename}` (`code`, `name`, `description`, `prev_price`, `current_price`, `img_path`) VALUES
                            ('123456', 'Product 101', 'This is a sample Product 101 description only', 0, 145.23, 'upload/1.jpg'),
                            ('123457', 'Product 102', 'This is a sample Product 102 description only', 520, 399, 'upload/2.jpg'),
                            ('123458', 'Product 103', 'This is a sample Product 103 description only', 0, 1299, 'upload/3.jpg'),
                            ('123459', 'Product 104', 'This is a sample Product 104 description only', 799, 599, 'upload/4.jpg'),
                            ('123450', 'Product 105', 'This is a sample Product 105 description only', 1999, 1599, 'upload/5.jpg')";
                $this->conn->query($insert_sql);
            }

        }else{
            return false;
        }
    }

    // get product from the database
    public function getData($pids = []){
        $where = "";
        if(count($pids)) {
            $pids = implode(",", $pids);
            $where = " where id in ({$pids})";
        }
        $sql = "SELECT * FROM {$this->tablename} $where";

        $result = $this->conn->query($sql);

        if($result->num_rows > 0){
            return $result;
        }
    }
}






