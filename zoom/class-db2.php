<?php
	class DB2 {
        private $dbHost     = "localhost";
        private $dbUsername = "root";
        private $dbPassword = "principe406!";
        private $dbName     = "booking";
        
        public function __construct(){
            if (!isset($this->db)){
                // Connect to the database
                $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
                if ($conn->connect_error){
                    die("Failed to connect with MySQL: ". $conn->connect_error);
                } else {
                    $this->db = $conn;
                }
            }
        }
        
        public function insertMeeting($username, $url){
            $this->db->query("INSERT INTO citas (username, url) VALUES ('$username', '$url')");
        }
        /*        
        public function update_access_token($token) {
            if ($this->is_table_empty()) {
                $this->db->query("INSERT INTO token(access_token) VALUES('$token')");
            } else {
                $this->db->query("UPDATE token SET access_token = '$token' WHERE id = (SELECT id FROM token)");
            }
        }*/
    }