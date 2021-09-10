<?php

    include_once __DIR__.'/../../domain/entities/Group.php';
    include_once __DIR__.'/../../application/interfaces/GroupRepository.php';
    include_once __DIR__.'/../DatabaseConnection.php';

    class GroupMysqlRepository implements GroupRepository {

        private DatabaseConnection $db;

        public function __construct()
        {
            
        }

        public function getAllByUserId(int $idUser): array
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $groups = array();
            
            try { 
                $statement = $conn->prepare("SELECT * FROM viculturadb.groups WHERE id_user = ?;");
                $statement->execute(array($idUser));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    array_push($groups, $this->create($response[$i]));
                }
            }catch(Exception $ex){
                echo "Something was wrong when trying to get all groups by user id, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();

            return $groups;
        }

        public function getGroupById(int $id, int $idUser): Group
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $group = new Group();
            $group->setId(-1);

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.groups WHERE id_user = ? and id = ? ;");
                $statement->execute(array($idUser, $id));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    $group = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "Something was wrong when trying to get the group by id, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $group;
        }

        public function add(Group $group): Group
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $group->setId(-1);

            try {
                $statement = $conn->prepare("INSERT INTO viculturadb.groups Values(0, ?, ?, ?, ?, ?);");
                $date_end = intval($group->getDateEnd()->format("Y")) == 1 ? NULL : $group->getDateEnd()->format('Y-m-d H:i:s');
                $statement->execute(array(
                    $group->getName(), 
                    $group->getDescription(), 
                    $group->getDateCreated()->format("Y-m-d H:i:s"),
                    $date_end,
                    $group->getIdUser()
                ));
                $group->setId($conn->lastInsertId());
            }catch(Exception $ex){
                echo "It wasn't possible to insert the group to the database, more details: " . $ex->getMessage();
            }
            
            $this->db->closeConnection();
            return $group;
        }

        public function update(Group $group): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            
            try {
                $statement = $conn->prepare('UPDATE viculturadb.groups SET name = ?, description = ?, date_end = ? where id = ?;');
                $date_end = intval($group->getDateEnd()->format("Y")) == 1 ? NULL : $group->getDateEnd()->format('Y-m-d H:i:s');
                $statement->execute(array(
                    $group->getName(),
                    $group->getDescription(),
                    $date_end,
                    $group->getId()
                ));
            }catch(Exception $ex){
                echo "It wasn't possible to update the group to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
        }

        public function delete(int $id): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            
            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.groups where id = ?;");
                $statement->execute(array($id));
            }catch(Exception $ex){
                echo ("Something was wrong when trying to delete by id, more details: " . $ex->getMessage());
            }

            $this->db->closeConnection();
        }

        private function create(array $response): Group {
            $result = new Group();
            $date_end = !isset($response["date_end"]) ? new DateTime('0001-01-01 00:00:00') : date_create_from_format('Y-m-d H:i:s', $response["date_end"]);
            $result->setId($response["id"]);
            $result->setName($response["name"]);
            $result->setDescription($response["description"]);
            $result->setDateCreated(date_create_from_format('Y-m-d H:i:s', $response["date_created"]));
            $result->setDateEnd($date_end);
            $result->setIdUser($response["id_user"]);
            return $result;
        }
    }
?>