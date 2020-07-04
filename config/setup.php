<?php

require_once('database.php');
try {
    $db= new PDO($DB_HOST, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql= "
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET AUTOCOMMIT = 0;
    START TRANSACTION;
    SET time_zone = '+00:00';

    /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
    /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
    /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
    /*!40101 SET NAMES utf8mb4 */;

    CREATE DATABASE IF NOT EXISTS `camagru_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
    USE `camagru_db`;

    CREATE TABLE `comments_tb` (
    `comment_id` int(11) NOT NULL,
    `picture_id` int(11) NOT NULL,
    `author_id` int(11) NOT NULL,
    `author_username` varchar(21) COLLATE utf8mb4_general_ci NOT NULL,
    `comment` varchar(500) COLLATE utf8mb4_general_ci NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE `likes_tb` (
    `picture_id` int(11) NOT NULL,
    `author_id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE `pictures_id` (
    `picture_id` int NOT NULL,
    `path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `author_id` int NOT NULL,
    `author_username` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE `users_tb` (
    `userid` int NOT NULL,
    `username` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `token` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `activated` tinyint DEFAULT NULL,
    `notifications` tinyint NOT NULL DEFAULT '1'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


    ALTER TABLE `comments_tb`
    ADD PRIMARY KEY (`comment_id`);

    ALTER TABLE `pictures_id`
    ADD PRIMARY KEY (`picture_id`);

    ALTER TABLE `users_tb`
    ADD PRIMARY KEY (`userid`);

    ALTER TABLE `comments_tb`
    MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

    ALTER TABLE `pictures_id`
    MODIFY `picture_id` int(11) NOT NULL AUTO_INCREMENT;

    ALTER TABLE `users_tb`
    MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;
    COMMIT;

    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    ";
    $query= $db->prepare($sql);
    if ($query->execute())
        echo "Bravo! Base de données créée";
    } catch(exception $e) {
    die('Erreur'.$e->getMessage());
}