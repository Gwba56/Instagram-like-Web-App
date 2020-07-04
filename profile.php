<?php
require_once 'model/Publication.Class.php';
require_once 'model/Page.Class.php';
include 'include/header.php';
include 'include/navbar.php';
if(!isset($_SESSION['userid'])) {
  $_SESSION['error'] = "Vous devez être connecté pour voir cette page";
  header('Location:login.php');
}
if(isset($_SESSION['error'])) {
  include 'include/error.php';
  unset($_SESSION['error']);
} else if(isset($_SESSION['success'])) {
  include 'include/success.php';
  unset($_SESSION['success']);
}
$page = new Page;
$getpage = !isset($_GET['page']) ? 1 : $_GET['page'];
$page_max = $page->define_total_user_pages($_SESSION['userid']);
$cur_page = $page->define_current_page($getpage, $page_max);
$prev = $cur_page - 1;
$next = $cur_page + 1;
$end = $page->define_end_of_page($cur_page, $page_max);
$start = $page->define_start_of_page($cur_page);
$pub = new Publication;
$rows = $pub->display_user_publications($_SESSION['userid']); 
?>
<?php if($rows) {?>
<div class="container">
<div class="row">
<?php for($i = $start; $i < $end; $i++) {
  if(file_exists($rows[$i]['path'])) {?>
    <div class="col s12 m6 l4">
      <div class="card center-align">
        <div class="card-image">
        <a href="publication.php?pid=<?= $rows[$i]['picture_id'] ?>"><img src="<?= $rows[$i]['path'] ?>"></a>
    </div>
      </div>
    </div>
    <?php } }?>
  </div>
</div>
<ul class="pagination center">
    <?php if($prev > 0) { ?>
    <li class="waves-effect"><a href="profile.php?page=<?= $prev ?>"><i class="material-icons">chevron_left</i></a></li>
    <?php } ?>
    <li class="teal"><a href="#!"><?= $cur_page ?></a></li>
    <?php if($cur_page < $page_max) { ?>
    <li class="waves-effect"><a href="profile.php?page=<?= $next ?>"><i class="material-icons">chevron_right</i></a></li>
    <?php } ?>
</ul>
<?php } else { ?>
<div class="container center-align">
<h4 class="teal-text text-darken-3">Bienvenue !</h4>
<div class="row">
    <div class="col s12 m8 l6 offset-m2 offset-l3">
      <div class="card">
        <div class="card-image">
          <img src="../public/upload/example.png">
        </div>
        <div class="card-content">
        <p>Rendez-vous <a href="camera.php">ici</a> pour créer le premier montage de Camagru</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } 
include 'include/footer.php';