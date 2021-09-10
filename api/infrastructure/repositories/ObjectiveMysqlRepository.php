<?php

    include_once __DIR__.'/../../domain/entities/Objective.php';
    include_once __DIR__.'/../../application/interfaces/ObjectiveRepository.php';
    include_once __DIR__.'/../DatabaseConnection.php';

    class ObjectiveMysqlRepository implements ObjectiveRepository {

        private DatabaseConnection $db;

        public function __construct()
        {
            
        }

        public function getAllByIdGroup(int $idGroup, int $start, int $limit): array
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $objectives = array();

            try {
                $statement = $conn->prepare("SELECT m.name, m.year, m.url_image, m.url_details, min_progress, o.max_progress, o.current_progress, o.date_created, o.id_group "
                    . "FROM objectives as o, materials as m "
                    . "WHERE group_id = ? and o.id_material = m.id "
                    . "LIMIT ?, ?;");

                $statement->execute(array($idGroup, $start, $limit));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    array_push($objectives, $this->create($response[$i]));
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get all objectives by id group in database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $objectives;
        }

        public function getTotalByIdGroup(int $idGroup): int
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $total = 0;

            try {
                $statement = $conn->prepare("SELECT count(*) as total from objectives WHERE id_group = ?;");
                $statement->execute(array($idGroup));
                $total = intval($statement->fetch(PDO::FETCH_ASSOC)["total"]);
            }catch(Exception $ex){
                echo "It wasn't possible to get the total objectives from the specific group, more details: " . $ex->getMessage();
            }
            
            $this->db->closeConnection();
            return $total;
        }

        public function add(Objective $objective): Objective
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $objective->setId(-1);

            try {
                $statement = $conn->prepare("INSERT INTO viculturadb.objectives Values(0, ?, ?, ?, ?, ?, ?);");
                $statement->execute(array(
                    $objective->getMinProgress(), 
                    $objective->getMaxProgress(), 
                    $objective->getDateCreated()->format("Y-m-d H:i:s"),
                    $objective->getIdGroup(),
                    $objective->getMaterial()->getId(),
                    $objective->getCurrentProgress()
                ));

                $objective->setId($conn->lastInsertId());
            }catch(Exception $ex){  
                echo "It wasn't possible to insert the objective to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $objective;
        }

        public function updateProgress(Objective $objective): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            
            try {
                $statement = $conn->prepare("UPDATE viculturadb.objectives SET min_progress = ?, max_progress = ?, current_progress = ? where id = ?;");
                $statement->execute(array(
                    $objective->getMinProgress(), 
                    $objective->getMaxProgress(), 
                    $objective->getCurrentProgress(),
                    $objective->getId()
                ));
            }catch(Exception $ex){  
                echo "It wasn't possible to update the objective to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
        }

        public function delete(int $id): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();

            try {
                $statement = $conn->prepare("SELECT * FROM viculturadb.objectives where id = ?;");
                $statement->execute(array($id));
            }catch(Exception $ex){
                echo "It wasn't possible to delete the objective in the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
        }

        private function create(array $response): Objective{
            $objective = new Objective();
            $material = new Material();

            $material->setName($response["name"]);
            $material->setYear($response["year"]);
            $material->setUrlImage($response["url_image"]);
            $material->setUrlDetails($response["url_details"]);
            
            $objective->setId($response["id"]);
            $objective->setMinProgress($response["min_progress"]);
            $objective->setMaxProgress($response["max_progress"]);
            $objective->setCurrentProgress($response["current_progress"]);
            $objective->setDateCreated($response["date_created"]);
            $objective->setIdGroup($response["id_group"]);
            $objective->setMaterial($material);

            return $objective;
        }
    }
?>