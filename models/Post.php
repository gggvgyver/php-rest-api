<?php 
  class Post {
    // 테이블 설정
    private $conn;
    private $table = 'posts';

    // 테이블 컬럼 설정
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    // 컨스트럭트 
    public function __construct($db) {
      $this->conn = $db;
    }

    // 테이블에서 값 가져오기
    public function read() {
      // 쿼리 생성
      $query = 'SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.created_at
                                FROM ' . $this->table . ' p
                                LEFT JOIN
                                  categories c ON p.category_id = c.id
                                ORDER BY
                                  p.created_at DESC';
      
      // 가져온 데이타 변수에 담기
      $stmt = $this->conn->prepare($query);

      // 쿼리 실행
      $stmt->execute();

      return $stmt;
    }

    // 테이블에서 단일 값 가져오기
    public function read_single() {
          // 쿼리 생성
          $query = 'SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.created_at
                                    FROM ' . $this->table . ' p
                                    LEFT JOIN
                                      categories c ON p.category_id = c.id
                                    WHERE
                                      p.id = ?
                                    LIMIT 0,1';

          // 가져온 데이타 변수에 담기
          $stmt = $this->conn->prepare($query);

          // 아이디 바인드
          $stmt->bindParam(1, $this->id);

          // 쿼리 실행
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // 해당값 변수에 담기
          $this->title = $row['title'];
          $this->body = $row['body'];
          $this->author = $row['author'];
          $this->category_id = $row['category_id'];
          $this->category_name = $row['category_name'];
    }

    // 테이블에 값 쓰기
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author, category_id = :category_id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->title = htmlspecialchars(strip_tags($this->title));
          $this->body = htmlspecialchars(strip_tags($this->body));
          $this->author = htmlspecialchars(strip_tags($this->author));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));

          // Bind data
          $stmt->bindParam(':title', $this->title);
          $stmt->bindParam(':body', $this->body);
          $stmt->bindParam(':author', $this->author);
          $stmt->bindParam(':category_id', $this->category_id);

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // 업데이트
    public function update() {
          // 쿼리설정
          $query = 'UPDATE ' . $this->table . '
                                SET title = :title, body = :body, author = :author, category_id = :category_id
                                WHERE id = :id';

          // 적용값 변수에 저장
          $stmt = $this->conn->prepare($query);

          // 데이터 가공
          $this->title = htmlspecialchars(strip_tags($this->title));
          $this->body = htmlspecialchars(strip_tags($this->body));
          $this->author = htmlspecialchars(strip_tags($this->author));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));

          // 데이터 바인드
          $stmt->bindParam(':title', $this->title);
          $stmt->bindParam(':body', $this->body);
          $stmt->bindParam(':author', $this->author);
          $stmt->bindParam(':category_id', $this->category_id);
          $stmt->bindParam(':id', $this->id);

          // 쿼리실행
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    // 삭제
    public function delete() {
          // 삭제쿼리
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // 적용값 변수에 담기
          $stmt = $this->conn->prepare($query);

          // 데이터 가공
          $this->id = htmlspecialchars(strip_tags($this->id));

          // 바인드
          $stmt->bindParam(':id', $this->id);

          // 쿼리실행
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }