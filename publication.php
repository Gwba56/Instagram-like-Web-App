<?php
require_once 'model/Publication.Class.php';
$pub = new Publication;
$data = $pub->display_selected_publication($_GET['pid']);
$comments = $pub->display_comments($_GET['pid']);
require_once 'include/header.php';
require_once 'include/navbar.php';
if(!$data['path'] || empty($data) OR !file_exists($data['path'])) {
    $_SESSION['error'] = "Cette publication n'existe pas.";
    header('Location:home.php');
}
if(isset($_SESSION['error'])) {
    include 'include/error.php';
    unset($_SESSION['error']);
} else if(isset($_SESSION['success'])) {
    include 'include/success.php';
    unset($_SESSION['success']);
}
?>
<div class="container">
    <div class="row">
        <div class="col s12 m10 l8 offset-m1 offset-l2">
        <div class="card">
            <div class="card-image">
            <img src="<?= $data['path']; ?>">
            <?php if($data['author_id'] == $_SESSION['userid']) { ?>
                <a href="controller/publication.php?pid=<?php echo $_GET['pid'];?>&del_pub" class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">delete</i></a>
            <?php } ?>
            </div>
            
                <div class="card-content center-align">
                <p class="left-align">
                <?php if(isset($_SESSION['userid'])) {  if(!$pub->has_liked($_SESSION['userid'], $data['picture_id'])) { ?>
                    <a href="controller/publication.php?pid=<?php echo $_GET['pid'];?>&like" class="btn-flat red-text"><i class="large material-icons">favorite_border</i></a>
                <?php } else { ?>
                    <a href="controller/publication.php?pid=<?php echo $_GET['pid'];?>&unlike" class="btn-flat red-text"><i class="large material-icons">favorite</i></a>
                <?php } }
                echo $pub->count_likes($data['picture_id']); ?> J'aime - <?= $data['username']; ?>
                </p>
            </div>
            <?php if(!empty($comments)) {?>
    
        <div class="card-content">
        <?php foreach ($comments as $comment) {?>
            <p>
                <span class="blue-text"><?= $comment['author_username']; ?></span>
                - <?= $comment['comment']; ?>
                <?php if($comment['author_id'] == $_SESSION['userid']) { ?>
                <span>
                    <form class="right-align" action="controller/publication.php?del_com" method="POST">
                        <input type="hidden" name="pid" value="<?= $_GET['pid'] ?>">
                        <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                        <button class="new badge red white-text"><i class="tiny material-icons">clear</i></button>
                    </form>
                </span>
                <?php } ?>
            </p>
        <?php } ?>
        </div>
        <?php } ?>
        <?php
        
         if(isset($_SESSION['userid'])) { ?>

            <div class="card-action right-align">
                <form action="controller/publication.php?new_comment" method="POST">
                    <textarea name="comment" placeholder="Ecrivez ici pour commenter" rows="5" cols="33" required></textarea>
                    <input type="hidden" name="author" value="<?= $data['author_username'] ?>">
                    <input type="hidden" name="email" value="<?= $data['email'] ?>">
                    <input type="hidden" name="notif" value="<?= $data['notifications'] ?>">
                    <input type="hidden" name="pid" value="<?= $_GET['pid'] ?>">
                    <button class="btn">Commenter<i class="material-icons right">send</i></button>
                </form> 
            </div>
            <?php } ?>
        </div>
        </div>
    </div>
    </div>
</div>
<?php
require_once 'include/footer.php';
?>