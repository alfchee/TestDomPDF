<?php

require_once 'PropGeneratorTrait.php';

abstract class AbstractModel 
{
    use PropGeneratorTrait;

    protected $fillable = array();

    private $db;
    // private $dbDir = '../data/comments.sqlite3';

    public function __construct($data = array())
    {
        if(!array_key_exists('id',$data))
            $data['id'] = null;
        
        $this->data = $data;
    }//__construct()

    public function toArray()
    {
        return $this->data;
    }//toArray()

    public function save()
    {
        $dataToSave = $this->checkArrayToSave();
        $this->db = self::ConnectDB();

        if(array_key_exists('id', $this->data) && $this->data['id']) {
            // prepare the update
            $update = "UPDATE comments SET comment = :comment WHERE movie_id = :movie_id";
            $stmt = $this->db->prepare($update);
            $stmt->execute($dataToSave);
        } else {
            // prepare the insert
            $insert = "INSERT INTO comments (comment, movie_id) VALUES (:comment, :movie_id)";
            $stmt = $this->db->prepare($insert);
            // bind parameters
            // $stmt->bindParam(':comment',$dataToSave['comment']);
            // $stmt->bindParam(':movie_id',$dataToSave['movie_id']);
            // Execute statement
            if($stmt->execute($dataToSave)) {
                $this->id = $this->db->lastInsertId();
            } else {
                $this->id = null;
            }
        }

        $this->db = null;
    }//save()

    public function delete()
    {
        $this->db = self::ConnectDB();

        if($this->id) {
            try {
                // prepare the update
                $delete = "DELETE FROM comments WHERE id = :id";
                $stmt = $this->db->prepare($delete);
                $stmt->execute(array('id' => $this->id));
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }

        $this->db = null;
        return true;
    }//delete()

    protected function checkArrayToSave()
    {
        $arr = array();

        foreach($this->fillable as $key) {
            if(array_key_exists($key, $this->data)) {
                $arr[$key] = $this->data[$key];
            }
        }
        return $arr;
    }//checkArrayToSave()

    private static function ConnectDB()
    {
        try {
            // open or create connection
            $db = new PDO('sqlite:./data/comments.sqlite3');
            // Set errormode to exceptions
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
        } catch (Exception $e) {
            throw new Exception($e);
            
        }

        return $db;
    }//ConnectDB()

    public static function find($id) 
    {
        $db = self::ConnectDB();
        $class = get_called_class();

        try {
                // prepare the query
                $query = "SELECT * FROM comments WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute(array('id' => $id));
                $obj = new $class($stmt->fetch());

                // close the connection
                $db = null;

                return $obj;
            } catch (Exception $e) {
                throw new Exception($e);
            }
    }//find()

    public static function findAll()
    {
        $db = self::ConnectDB();
        $class = get_called_class();

        // array to hold all the objects of the result
        $coll = array();

        try {
            // the query
            $query = "SELECT * FROM comments";

            // fetch the data as array
            foreach($db->query($query) as $row) {
                $coll[] = new $class($row);
            }
        } catch(Exception $e) {
            throw new Exception($e);            
        }

        // close the connection
        $db = null;

        return $coll;
    }//findAll()

    public static function query($query, $params)
    {
        $db = self::ConnectDB();
        $class = get_called_class();
        // array to hold all the objects of the result
        $coll = array();

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            // fetch the data as array
            foreach($stmt->fetchAll() as $row) {
                $coll[] = new $class($row);
            }
        } catch(Exception $e) {
            throw new Exception($e);
            
        }
        // close the connection
        $db = null;

        return $coll ? $coll : array(new $class());
    }//query()
}