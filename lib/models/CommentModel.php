<?php

require_once 'AbstractModel.php';

class CommentModel extends AbstractModel
{
    protected $fillable = array(
        'comment',
        'movie_id'
        );

    public static function findByMovieId($id)
    {
        $query = "SELECT * FROM comments WHERE movie_id = :movie_id";

        $result = self::query($query,array('movie_id' => $id));

        return $result[0];
    }//findByMovieId()
}