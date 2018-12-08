<?php 
  // 헤더설정
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/config.php';
  include_once '../../models/Post.php';

  // 데이타베이스 설정
  $database = new Database();
  $db = $database->connect();

  // 인스턴스 설정
  $post = new Post($db);

  // 쿼리
  $result = $post->read();
  // 실행된값 얻기
  $num = $result->rowCount();

  // 실행된값 분기
  if($num > 0) {
    // 배열 설정
    $posts_arr = array();
    // $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $post_item = array(
        'id' => $id,
        'title' => $title,
        'body' => html_entity_decode($body),
        'author' => $author,
        'category_id' => $category_id,
        'category_name' => $category_name
      );

      // 데이터를 배열에 푸시
      array_push($posts_arr, $post_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // 제이슨으로 변환
    echo json_encode($posts_arr);

  } else {
    // 값이 없다면
    echo json_encode(
      array('message' => '값이 없음!')
    );
  }
