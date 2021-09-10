<?php 

    include_once __DIR__.'/../../domain/entities/Material.php';
    include_once __DIR__.'/../../application/interfaces/MaterialRepository.php';
    include_once __DIR__.'/../DatabaseConnection.php';

    class MaterialMysqlRepository implements MaterialRepository {

        private DatabaseConnection $db;

        public function __construct()
        {
            
        }

        public function getTotal(): int
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $total = 0; 

            try {
                $statement = $conn->prepare("SELECT count(*) as total from materials;");
                $statement->execute();
                $total = intval($statement->fetch(PDO::FETCH_ASSOC)["total"]);
            }catch(Exception $ex){
                echo "It wasn 't possible to get the total materials from the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $total;
        }

        public function getAll(int $start, int $limit): array
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $materials = array();

            try {
                $statement = $conn->prepare("SELECT * FROM materials LIMIT " . $start . ", " . $limit . ";");
                $statement->execute();
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    array_push($materials, $this->create($response[$i]));
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the materials from the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $materials;
        }

        public function getById(int $id): Material
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $material = new Material();
            $material->setId(-1);

            try {
                $statement = $conn->prepare("SELECT * FROM materials WHERE id = ? ;");
                $statement->execute(array($id));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    $material = $this->create($response[$i]);
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the materials from the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $material;
        }

        public function getByName(string $name): array
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $materials = array();

            try {
                $statement = $conn->prepare("SELECT * FROM materials WHERE LOWER(name) = ?;");
                $statement->execute(array(trim(strtolower($name))));
                $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($response); $i++){
                    array_push($materials, $this->create($response[$i]));
                }
            }catch(Exception $ex){
                echo "It wasn't possible to get the materials by name, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
            return $materials;
        }

        public function add(Material $material): Material
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            $material->setId(-1);

            try {
                $statement = $conn->prepare("INSERT INTO materials VALUES(0, ?, ?, ?, ?, ?);");
                $statement->execute(array(
                    $material->getName(),
                    $material->getYear(),
                    $material->getDateCreated()->format("Y-m-d H:i:s"),
                    $material->getUrlImage(),
                    $material->getUrlDetails()
                ));

                $material->setId($conn->lastInsertId());
            }catch(Exception $ex){
                echo "It wasn't possible to add the material to the database, more details: " . $ex->getMessage();
            }   

            $this->db->closeConnection();
            return $material;
        }

        public function update(Material $material): void
        {
            $this->db = DatabaseConnection::getInstance();
            $conn = $this->db->getConnection();
            
            try {
                $statement = $conn->prepare("UPDATE viculturadb.materials SET name = ?, year = ?, url_image = ?, url_details = ? where id = ?;");
                $statement->execute(array(
                    $material->getName(),
                    $material->getYear(),
                    $material->getUrlImage(),
                    $material->getUrlDetails(),
                    $material->getId()
                ));
            }catch(Exception $ex){
                echo "It wasn't possible to update the material to the database, more details: " . $ex->getMessage();
            }

            $this->db->closeConnection();
        }

        private function create(array $response): Material {
            $material = new Material();
            $material->setId($response["id"]);
            $material->setName($response["name"]);
            $material->setYear($response["year"]);
            $material->setDateCreated(date_create_from_format('Y-m-d H:i:s', $response["date_created"]));
            $material->setUrlDetails($response["url_details"]);
            $material->setUrlImage($response["url_image"]);
            return $material;
        }
    }
?>