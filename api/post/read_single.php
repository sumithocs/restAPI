<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$database = new Database();
$db = $database->connect();

$post = new Post($db);

if (isset($_GET['id'])) {

    // SET ID
    $post->id = $_GET['id'];
    $result = $post->read_single();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $num = $result->rowCount();
    if ($num > 0) {
        extract($row);
        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_id' => $category_name
        );

        echo json_encode($post_item);
    } else {
        echo json_encode(array('msg' => 'No record for ID : ' . $_GET['id']));
    }
} else {
    echo json_encode(array('msg' => 'bad request'));
}
