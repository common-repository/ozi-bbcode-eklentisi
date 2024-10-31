<?php
/*
Plugin Name: Ozi BBcode
Plugin URI: http://www.oguzhansahin.net
Description: BBcode Rahatlığını Sizde Yaşayın. Bana Yapacağınız Bağışlar Sizlere Eklenti,PSD to CSS Çalışması,Script ve PHP Makalesi Olarak Geri Dönecektir.
Author: Oğuzhan Şahin
Version: 1.0
Author URI: http://www.oguzhansahin.net
*/

	add_action('admin_menu', 'bbcode_ekle_menu');
	add_action('the_content','bbcode_calistir');
	
	function bbcode_ekle_menu(){
	add_submenu_page('options-general.php', 'Ozi BBcode', 'Ozi BBCode', 10, __FILE__, 'ozi_bbcode');
	}
	function ozi_bbcode(){
	global $wpdb;
	$site=get_option('siteurl');
	$islem=$_GET['islem'];
	$kodid=$_GET['kodid'];
	?>
	<style>
	.form {float:left;width:350px;}
	.input {float:left;width:250px;}
	.input input {float:left;width:200px;margin-top:5px;margin-left:50px;}
	.input label {float:left;margin-left:45px;width:240px;}
	.text {float:left;width:300px;margin-top:20px;}
	.text label {float:left;width:260px;margin-left:25px;}
	.submit {float:left;margin-left:100px;}
	.olanlar {float:left;width:450px;margin-left:50px;}
	.olan {float:left;width:200px;margin-left:25px;margin-top:5px;}
	.olan a {float:right;font-weight:bold;text-decoration:none;}
	.sonuc {float:left;width:800px;height:30px;font-weight:bold;}
	</style>
	<center><h3>Ozi BBcode Eklentisine Hoşgeldiniz.</h3></center><br />
		<?PHP 	if($islem=='ekle'){
	$kod=stripslashes($_POST['kod']);
	$embed=stripslashes($_POST['embed']);
	if(empty($kod) || empty($embed)){$sonuc="Hiç Bir Alan Boş Bırakılamaz.";}else {
	$varmi=mysql_num_rows(mysql_query("SELECT * FROM ozi_bbcode WHERE kul_kod='$kod'"));
	if($varmi!='0'){$sonuc="Bu Kod Zaten Kullanılıyor.";}else{
	$islem=mysql_query("INSERT INTO ozi_bbcode (id,kul_kod,kod) values ('','$kod','$embed')");
	if($islem){$sonuc="BBcode Eklendi.";}else {$sonuc="BBcode Eklenemedi.";} } } }
	
	if(isset($kodid)){
	$kodyaz=mysql_fetch_array(mysql_query("SELECT * FROM ozi_bbcode WHERE id='$kodid'"));
	$kodx=$kodyaz[kul_kod];
	$embedx=$kodyaz[kod];
	}
	if($islem=='guncelle'){
	$kodx=stripslashes($_POST['kod']);
	$embedx=stripslashes($_POST['embed']);
	$kodidx=stripslashes($_POST['kodid']);
	$islem=mysql_query("UPDATE ozi_bbcode set kul_kod='$kodx',kod='$embedx' WHERE id='$kodidx'");
	if($islem){$sonuc="Başarıyla Güncellendi.";}else {$sonuc="Güncellenemedi.";}
	}
	?>
	
	<div class="form">
	<form action="<?PHP echo $site; ?>/wp-admin/options-general.php?page=ozi-bbcode/index.php&islem=<?PHP if(isset($kodid)){ ?>guncelle<?PHP }else { ?>ekle <?PHP } ?>" method="post">
	<div class="input"><label>Kullanılacak Kodu Buraya Giriniz</label><input type="text" name="kod" value="<?PHP if(isset($kodid)){ echo $kodx; }?>" /></div>
	<div class="text"><label>Kullanılacak İşlev Kodunu Buraya Giriniz</label><textarea name="embed" cols="40" rows="5"><?PHP if(isset($kodid)){ echo $embedx; }?></textarea></div>	
	<?PHP if(isset($kodid)){ ?><input type="hidden" name="kodid" value="<?PHP echo $kodid; ?>"  /><?PHP } ?>
	<div class="submit"><input id="publish" class="button-primary" type="submit" value="<?PHP if(empty($kodid)){ ?>Ekle<?PHP }else { ?> Güncelle <?PHP } ?>"/></div>
	</form>
	</div>
	<div class="olanlar">
	<?PHP $sor=mysql_query("SELECT * FROM ozi_bbcode"); while($yaz=mysql_fetch_array($sor)): ?>
	<div class="olan"><?PHP echo $yaz[kul_kod]; ?> <a href="<?PHP echo $site; ?>/wp-admin/options-general.php?page=ozi-bbcode/index.php&kodid=<?PHP echo $yaz[id]; ?>">Düzenle</a></div>
	<?PHP endwhile; ?>
	</div>
	<div class="sonuc"><?PHP echo $sonuc; ?></div>
	<?PHP
	
	}
	
	function bbcode_calistir($icerik){
	global $wpdb;
	$sor=mysql_query("SELECT * FROM ozi_bbcode");
	while($yaz=mysql_fetch_array($sor)):
	$kodumuz=str_replace("{degisken}","$1",$yaz[kod]);
	$icerik=preg_replace('|\['.$yaz[kul_kod].'="(.*)"\]|siU',$kodumuz,$icerik);
	endwhile;
	return $icerik;
	}

	 register_activation_hook(__FILE__,'ozi_bbcode_install');
	 register_deactivation_hook(__FILE__,'ozi_bbocde_uninstall');
	 
	function ozi_bbcode_install() {
	global $wpdb;
	$x="CREATE TABLE IF NOT EXISTS `ozi_bbcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kul_kod` text NOT NULL,
  `kod` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	mysql_query($x);
	}

	function ozi_bbocde_uninstall() {
	global $wpdb;
	$x="DROP TABLE `ozi_bbcode` ";
	mysql_query($x);
	}
	
?>