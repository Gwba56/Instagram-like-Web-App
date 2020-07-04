<?php

class Page {
    private $db;
    protected $nb_pub;

    public function __construct() {
        if (file_exists('config/database.php')) {
            include 'config/database.php';
        } elseif (file_exists('../config/database.php')){
            include '../config/database.php';
        }
        try {
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function define_total_pages() {
        try {
            $request = $this->db->prepare('SELECT * FROM pictures_id');
            $request->execute();
            $this->nb_pub = $request->rowCount();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $page_max = ceil($this->nb_pub / 9);
        return $page_max;
    }

    function define_total_user_pages($author_id) {
        try {
            $request = $this->db->prepare('SELECT * FROM pictures_id WHERE author_id = :author_id');
            $request->bindParam(':author_id', $author_id);
            $request->execute();
            $this->nb_pub = $request->rowCount();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $page_max = ceil($this->nb_pub / 9);
        return $page_max;
    }

    function define_current_page($get, $max) {
        if ($get > $max) {
            return $max;
        } else {
            return $get;
        }
    }
    
    function define_end_of_page($page, $max) {
        if ($page == $max) {
            $end = $this->nb_pub;
        } else {
            $end = $page * 9;
        }
        return $end;
    }
    
    function define_start_of_page($page) {
        $start = ($page - 1) * 9;
        return $start;
    }
}

