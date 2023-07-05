<?php 


class DatabaseQuery
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }
}