<?php

    require_once __DIR__.'/../../domain/entities/User.php';
    require_once __DIR__.'/../../application/interfaces/UserRepository.php';
    require_once __DIR__.'/../DatabaseConnection.php';

    class UserMysqlRepository implements UserRepository {

        private DatabaseConnection $db;

        public function __construct()
        {
            
        }

        public function getById(int $id): User
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $user = new User();

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.users WHERE id = ?;");
                $statement->execute(array($id));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                   $user = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the user by id in the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $user;
        }

        public function getByUsername(string $username): User
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $user = new User();
            $user->setId(-1);

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.users WHERE username = ?;");
                $statement->execute(array($username));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                   $user = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the user by username in the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $user;
        }

        public function getByEmail(string $email): User {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $user = new User();
            $user->setId(-1);

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.users WHERE email = ?;");
                $statement->execute(array($email));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                   $user = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the user by email in the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $user;
        }

        public function getByUsernameAndId(int $id, string $username): User
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $user = new User();
            $user->setId(-1);

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.users WHERE id = ? and username = ?;");
                $statement->execute(array($id, $username));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                   $user = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the user by username and id in the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $user;
        }

        public function add(User $user): User
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $user->setId(-1);
            
            try {
                $statement = $conn->prepare("INSERT INTO viculturadb.users Values(0, ?, ?, ?, ?);");
                $statement->execute(array(
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getPassword(),
                    $user->getDateCreated()->format('Y-m-d H:i:s'),
                ));

                $user->setId($conn->lastInsertId());
            }catch(Exception $ex){
                echo "It wasn't possible to insert the user to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $user;
        }

        public function update(User $user): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();

            try {
                $statement = $conn->prepare("UPDATE viculturadb.users SET username = ?, email = ?, password = ? where id = ? ;");
                $statement->execute(array(
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getPassword(),
                    $user->getId()
                ));
            }catch(Exception $ex){
                echo "It wasn't possible to update the user to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
        }

        private function create(array $response): User {
            $user = new User();
            $user->setId($response["id"]);
            $user->setUsername($response["username"]);
            $user->setEmail($response["email"]);
            $user->setDateCreated(date_create_from_format('Y-m-d H:i:s', $response["date_created"]));
            $user->setPassword($response["password"]);
            return $user;
        }
    }
?>