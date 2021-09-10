<?php

    require_once __DIR__.'/Keys.php';

    class DatabaseConnection {

        private $dbConnection = NULL;
        private static ?DatabaseConnection $database = NULL;

        public function __construct()
        {
            if($this->dbConnection == null){
                try {
                    $this->dbConnection = new PDO(
                        "mysql:host=localhost;port=3306;dbname=". Keys::$dbName,
                        Keys::$username,
                        Keys::$password
                    );
                }catch(PDOException $ex){
                    echo $ex->getMessage();
                }
            }

            return $this->dbConnection;
        }

        public static function getInstance(): DatabaseConnection {
            if(NULL == self::$database){
                self::$database = new DatabaseConnection();
            }

            return self::$database;
        } 

        public function getConnection(){
            return $this->dbConnection;
        } 

        public function closeConnection(): void {
            self::$database = null;
            $this->dbConnection = null;
        }
    }

?>