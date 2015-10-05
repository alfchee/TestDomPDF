<?php

function CheckOrCreateDB() 
{

    if(file_exists('./data/comments.sqlite3'))
        return;

    try {
        // open or create connection
        $file_db = new PDO('sqlite:./data/comments.sqlite3');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table comments
        $file_db->exec("CREATE TABLE IF NOT EXISTS comments (
                        id INTEGER PRIMARY KEY, 
                        comment TEXT, 
                        movie_id INTEGER)");    
    } catch(Exception $e) {
        throw new Exception($e);
    }
    
}//CheckOrCreateDB()