<?php

require_once __DIR__ . '/../../application/interfaces/SessionRepository.php';
require_once __DIR__ . '/../../domain/entities/Session.php';
require_once __DIR__.'/../DatabaseConnection.php';

class SessionMysqlRepository implements SessionRepository {

    private DatabaseConnection $db;

    public function __construct()
    {
        
    }

    public function getToken(int $idUser): Session
    {
        $this->db = DatabaseConnection::getInstance();
        $conn = $this->db->getConnection();
        $session = new Session();

        try {
            $statement = $conn->prepare("SELECT * FROM viculturadb.sessions WHERE id_user = ? ORDER BY id DESC LIMIT 1;");
            $statement->execute(array($idUser));
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($response); $i++){
                $session = $this->create($response[$i]);
            }
        }catch(Exception $ex){
            echo "It wasn't possible to get the session from the user, more details: " . $ex->getMessage();
        }

        $this->db->closeConnection();
        return $session;
    }

    public function add(Session $session): void
    {
        $this->db = DatabaseConnection::getInstance();
        $conn = $this->db->getConnection();

        try {
            $statement = $conn->prepare("INSERT INTO viculturadb.sessions VALUES(0, ?, ?, ?, ?);");
            $statement->execute(array(
                $session->getToken(),
                $session->getDateCreated()->format('Y-m-d H:i:s'),
                $session->getDateExpiration()->format('Y-m-d H:i:s'),
                $session->getIdUser()
            ));
        }catch(Exception $ex){
            echo "It wasn't possible to insert the session to the database, more details: " . $ex->getMessage();
        }
        $this->db->closeConnection();
    }

    private function create(array $response): Session {
        $session = new Session();
        $session->setId($response["id"]);
        $session->setToken($response["token"]);
        $session->setDateCreated(date_create_from_format('Y-m-d H:i:s', $response["date_created"]));
        $session->setDateExpiration(date_create_from_format('Y-m-d H:i:s', $response["date_expiration"]));
        $session->setIdUser($response["id_user"]);
        return $session;
    }
}

?>