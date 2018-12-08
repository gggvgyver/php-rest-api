<?php
class Database {
    private $host = 'localhost';
    private $db = 'myblog';
    private $user = 'root';
    private $pwd = '';
    private $charset = 'utf8mb4';
    private $conn;
    

    public function connect() {
        $this->conn = null;
  
        try { 
          $this->conn = new PDO('mysql:host=' . $this->host . ';charset=' . $this->charset . ';dbname=' . $this->db, $this->user, $this->pwd);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
          echo '접속에러: ' . $e->getMessage();
        }
  
        return $this->conn;
      }
}