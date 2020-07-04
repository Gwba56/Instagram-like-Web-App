<?php
require_once 'model/Publication.Class.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
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
if(isset($_GET['s']) AND $_GET['s'] > 0 AND $_GET['s'] < 6) {
	$img = $_GET['s'];
} else {
	$img = 1;
}
$pub = new Publication;
$rows = $pub->display_user_publications($_SESSION['userid']); 
?>
<div class="row">
</div>
<div id="main" class="center-align">
	<div id="camera">
		<div id="camera_section">
				<div class="grey lighten-2" id="sticker_div">
					<a href="upload.php?s=1"><img src="public/stickers/1.png" class="stickerImg grey <?php if($img == 1) { echo "active"; } ?>"></a>
					<a href="upload.php?s=2"><img src="public/stickers/2.png" class="stickerImg grey <?php if($img == 2) { echo "active"; } ?>"></a>
					<a href="upload.php?s=3"><img src="public/stickers/3.png" class="stickerImg grey <?php if($img == 3) { echo "active"; } ?>"></a>
					<a href="upload.php?s=4"><img src="public/stickers/4.png" class="stickerImg grey <?php if($img == 4) { echo "active"; } ?>"></a>
					<a href="upload.php?s=5"><img src="public/stickers/5.png" class="stickerImg grey <?php if($img == 5) { echo "active"; } ?>"></a>
				</div>
				<div class="center-align">
				<?php if(isset($_GET['s']) AND $_GET['s'] > 0 AND $_GET['s'] < 6) {?>
					<form action="controller/camera.php?uploadpic" method="POST" enctype="multipart/form-data" onsubmit="uploadPhoto()">
						<input type="file" name="uploadPic" id="uploadPic">
						<input id="photo" name="photo" type="hidden" value="">
						<input id="sticker" name="sticker" type="hidden" value="">
						<input class="btn center-align teal accent-4" id="uploadBtt" type="submit" value="Enregister">
        			</form>
			<?php } else { ?>
				<p>Sélectionnez un filtre pour pouvoir prendre votre photo</p>
			<?php } ?>
			</div>
			<div><p id="text-camera"><a style="color: #174873; font-weight: bold" href="camera.php" hover="underline">Ou prenez une photo</a></p></div>
			<div class="responsive-img center-align">
			<div id="video_div">
				<div id="live_video">
					<img src="public/stickers/<?= $img ?>.png" id="overlay">
				</div>
				<img id='upload_img' width=640px height=480px />
			</div>
				<canvas style="display:none" id="canvas" width=640 height=480></canvas>
		</div>
	</div>
	</div>
	<div class="gallery grey lighten-4">
		<div class="row">
			<h3 class="center-align green-text text-darken-3">Votre gallerie</h3>
	<?php if($rows) { 
		foreach ($rows as $row) {
			if(file_exists($row['path'])) {?>
		<div class="col s12 m12 l4">
			<div class="card">
				<div class="card-image">
					<a href="publication.php?pid=<?php echo $row['picture_id'];?>"><img src="<?= $row['path'] ?>"></a>
					<a href="controller/publication.php?pid=<?= $row['picture_id'] ?>&del_pub=upload" class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">delete</i></a>
				</div>
			</div>
		</div>
    
 	<?php } } } else {?>
	 <h5>Créez votre premier montage pour voir votre gallerie !</h5>
	<?php } ?>	
 </div>
 </div>
 </div>
<?php
require_once 'include/camera_footer.php';
?>