<?php

class Publication {
    private $db;

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

    function display_all_publications() {
        try {
            $request = $this->db->prepare('SELECT pictures_id.*, users_tb.username FROM pictures_id JOIN users_tb ON pictures_id.author_id = users_tb.userid ORDER BY picture_id DESC');
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetchAll(PDO::FETCH_ASSOC);
        return($result);
    }

    function display_user_publications($author_id) {
        try {
            $request = $this->db->prepare('SELECT pictures_id.* FROM pictures_id JOIN users_tb ON pictures_id.author_id = users_tb.userid WHERE author_id = :author_id ORDER BY picture_id DESC');
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetchAll(PDO::FETCH_ASSOC);
        return($result);
    }

    function display_selected_publication($picture_id) {
        try {
            $request = $this->db->prepare('SELECT pictures_id.*, users_tb.userid, users_tb.username, users_tb.email, users_tb.notifications FROM pictures_id JOIN users_tb ON pictures_id.author_id = users_tb.userid WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetch(PDO::FETCH_ASSOC);
        return($result);
    } 

    function delete_publication($author_id,$picture_id) {
        if($this->is_author($author_id,$picture_id) < 1) {
            $_SESSION['error'] = "Vous ne pouvez pas effacer une publication dont vous n'êtes pas l'auteur";
            return ;
        }
        $this->delete_all_publication_comments($picture_id);
        $this->delete_all_publication_likes($picture_id);
        $this->delete_publication_file($picture_id);
        try {
            $request = $this->db->prepare('DELETE FROM pictures_id WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        return ;
    }

    function is_author($author_id,$picture_id) {
        try {
            $request = $this->db->prepare('SELECT * FROM pictures_id WHERE picture_id = :picture_id AND author_id = :author_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->rowCount();
        return $result;
    }

    function display_comments($picture_id) {
        try {
            $request = $this->db->prepare('SELECT * FROM comments_tb WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetchAll(PDO::FETCH_ASSOC);
        return($result);
    }

    function comment_publication($picture_id, $author_id, $author_username, $comment) {
        try {
            $request = $this->db->prepare('INSERT INTO comments_tb(picture_id, author_id, author_username, comment) VALUES (:picture_id, :author_id, :author_username, :comment)');
            $request->bindParam(':picture_id', $picture_id);
            $request->bindParam(':author_id', $author_id);
            $request->bindParam(':author_username', $author_username);
            $request->bindParam(':comment', $comment);
            $request->execute(); 
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function send_notification($picture_id, $author, $email, $notifications, $username) {
        if($notifications == 1) {
            $email = $email;
            $header = 'MIME-Version: 1.0' . "\r\n";   
            $header .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
            $header .= 'From:camagru@camagru.fr' . "\r\n";
            $header .= 'X-Mailer: PHP/' . phpversion();
            $subject = "Quelqu'un a commenté votre publication" ;
            $content = 'Bonjour '. $username . " !\r\n"
                        . $author .' a commenté votre publication. Veuillez cliquer sur le lien ci-dessous pour lire son commentaire.
                        http://localhost:8080/publication.php?pid='.urlencode($picture_id).'
            
                        ---------------
                        Ceci est un mail automatique, Merci de ne pas y répondre.';
        
            mail($email, $subject, $content, $header);
        }
    }


    function delete_selected_comment($comment_id) {
        try {
            $request = $this->db->prepare('DELETE FROM comments_tb WHERE comment_id = :comment_id');
            $request->bindParam(':comment_id', $comment_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function delete_all_user_comments($author_id) {
        try {
            $request = $this->db->prepare('DELETE FROM comments_tb WHERE author_id = :author_id');
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function delete_all_publication_comments($picture_id) {
        try {
            $request = $this->db->prepare('DELETE FROM comments_tb WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function delete_publication_file($picture_id) {
        try {
            $request = $this->db->prepare('SELECT `path` FROM pictures_id WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->fetch(PDO::FETCH_ASSOC);
        unlink('../'.$result['path']);
    }

    function count_likes($picture_id) {
        try {
            $request = $this->db->prepare('SELECT * FROM likes_tb WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->rowCount();
        return($result);

    }

    function has_liked($author_id, $picture_id) {
        try {
            $request = $this->db->prepare('SELECT * FROM likes_tb WHERE picture_id = :picture_id AND author_id = :author_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
        $result = $request->rowCount();
        return $result;
    }

    function like($author_id, $picture_id) {
        try {
            $request = $this->db->prepare('INSERT INTO likes_tb(author_id, picture_id) VALUES (:author_id, :picture_id)');
            $request->bindParam(':picture_id', $picture_id);
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function unlike($author_id, $picture_id) {
        try {
            $request = $this->db->prepare('DELETE FROM likes_tb WHERE author_id = :author_id AND picture_id = :picture_id');
            $request->bindParam(':author_id', $author_id);
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function delete_all_user_likes($author_id) {
        try {
            $request = $this->db->prepare('DELETE FROM likes_tb WHERE author_id = :author_id');
            $request->bindParam(':author_id', $author_id);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function delete_all_publication_likes($picture_id) {
        try {
            $request = $this->db->prepare('DELETE FROM likes_tb WHERE picture_id = :picture_id');
            $request->bindParam(':picture_id', $picture_id);
            $request->execute();  
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }

    }

}
