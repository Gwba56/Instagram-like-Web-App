<?php
class Montage {
    private $db;

    public function __construct() {
        if (file_exists('config/database.php')) {
            include 'config/database.php';
        } elseif (file_exists('../config/database.php')){
            include '../config/database.php';
        }
        if (!file_exists('../public/upload') AND !file_exists('public/upload')) {
            mkdir('../public/upload');
        }
        try {
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(exception $e) {
            die('Erreur :'.$e->getMessage());
        }
    }

    function treat_picture($photo, $sticker) {
        $photo = explode(',', $photo);
        $data = base64_decode($photo[1]);
        $filePath = 'public/upload/'.date("YmdHis").'.png';
        file_put_contents('../'.$filePath, $data);
        $photoCopy = imagecreatefrompng('../'.$filePath);
        $stickerCopy = imagecreatefrompng($sticker);
        $resized_filter = imagecreatetruecolor(640, 480);
        $trans_color = imagecolorallocatealpha($resized_filter, 0, 0, 0, 127);
        imagefill($resized_filter, 0, 0, $trans_color);
        imagealphablending($stickerCopy, true);
        imagesavealpha($stickerCopy, true);
        $src_x = imagesx($stickerCopy);
        $src_y = imagesy($stickerCopy);
        imagecopyresampled($resized_filter, $stickerCopy, 0, 0, 0, 0, 640, 480, $src_x, $src_y);
        imagecopy($photoCopy, $resized_filter, 0, 0, 0, 0, 640, 480);
        imagepng($photoCopy, '../'.$filePath);
        imagedestroy($photoCopy);
        return $filePath;
    }
    
    function store_picture($path, $author_id, $author_username){
        try {
            $request = $this->db->prepare("INSERT INTO pictures_id (path, author_id, author_username) VALUE (:path,:author_id,:author_username)");
            $request->bindParam(':path', $path);
            $request->bindParam(':author_id', $author_id);
            $request->bindParam(':author_username', $author_username);
            $request->execute();
        } catch (Exception $e) {
            die('Erreur :'.$e->getMessage());
        }

    }
}