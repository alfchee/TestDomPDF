<?php

require_once 'lib/models/CommentModel.php';
require_once 'lib/CheckSqlite.php';

// definition of variables
$commentId = null;
$comment = '';
$method = '';
$movieId = '';
$delete = false;

// validation function 
function validateInput($data) 
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}//validateInput()

// obtain the data from POST
if($_POST) 
{
    if(isset($_POST['method']) && $_POST['method'] == "DELETE") {
        $commentId = validateInput($_POST['commentId']);
        $delete = true;
    } else {
        $movieId = validateInput($_POST['movieId']);
        $comment = validateInput($_POST['comment']);

        if(array_key_exists('commentId', $_POST))
            $commentId = $_POST['commentId'];
    }
} else {
    throw new Exception("Error Processing Request", 1);
}

// Check if database is available
CheckOrCreateDB();

if(!$delete) {
    // populating the data
    $co = new CommentModel(array('movie_id' => $movieId, 'comment' => $comment, 'id' => $commentId));
    $co->save();

    $response = array('id' => $co->id, 'message' => 'success!!');
} else if($delete) {
    // Actions for delete
    $co = CommentModel::find($commentId);
    if($co->delete())
        $response = array('message' => 'success');
    else 
        $response = array('message' => 'some problems');
}

$encoded = json_encode($response);
header('Content-type: application/json');
exit($encoded);