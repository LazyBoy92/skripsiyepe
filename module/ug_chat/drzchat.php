<?php
	/*
	if(count(get_included_files())==1){
	  echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
	  exit("Direct access not permitted.");
	  }
	error_reporting(0);
	*/
	session_start();
	//include"timeout.php";

	include"../../config/koneksi.php";
	
	$id_siswa = $_GET['id'];
	$user=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * from user where id_user ='$_SESSION[id_user]'"));
	$dari=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * from user where id_user ='$id_siswa'"));

	//echo "SELECT * from user where id_user ='$id_siswa'";



	$read=mysqli_query($koneksi,"UPDATE chat set status='R' where dari='$id_siswa' and ke='$_SESSION[id_user]'");

	//foto 1
	if($user['level']=="user_guru") {
	$dir="module/foto_pengajar";
	} else {
	$dir="module/foto_siswa";
	}

	//foto 2
	if($dari['level']=="user_siswa"){
	$dir2="module/foto_siswa";
	}else{
	$dir2="module/foto_pengajar";
	}

	//ambil nama
	if($dari['level']=="user_guru") {
	$nama= mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap FROM guru WHERE id='$id_siswa'"));
	} else {
	$nama= mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap FROM siswa WHERE id='$id_siswa'"));
	//echo "SELECT nama_lengkap FROM siswa WHERE id='$user[id_user]'";
	}


	if (empty($_SESSION['id_user'])) {
				 header('Location:index.php?module=home');
	}
	else{

?>
<script> 
  var ajaxku = buatAjax(); 
  var tnama = 0; 
  var pesanakhir = 0; 
  var timer;
  
  function taruhNama(){ 
      if(tnama==0){ 
          document.getElementById("nama").disabled = "true"; 
          tnama = 1; 
      }else{ 
          document.getElementById("nama").disabled = ""; 
          tnama = 0; 
      } 
      ambilPesan(); 
  } 
  function buatAjax(){ 
      if(window.XMLHttpRequest){ 
          return new XMLHttpRequest(); 
    }else if(window.ActiveXObject){ 
          return new ActiveXObject("Microsoft.XMLHTTP"); 
      } 
  } 
  
 
  function ambilPesan(){ 
      namaku = document.getElementById("nama").value 
      if(ajaxku.readyState == 4 || ajaxku.readyState == 0){ 
          ajaxku.open("GET","ambilchat.php?akhir="+pesanakhir+"&nama="+namaku+"&sid="+Math.random(),true); 
          ajaxku.onreadystatechange = aturAmbilPesan; 
          ajaxku.send(null); 
      } 
  } 

  function aturAmbilPesan(){ 
      if(ajaxku.readyState == 4){ 
          var chat_div = document.getElementById("div_chat"); 
          var data = eval("("+ajaxku.responseText+")"); 
          for(i=0;i<data.messages.pesan.length;i++){ 
        
        if(data.messages.pesan[i].ke == <?php echo $_SESSION[id_user] ?>){
        $(function(){  
        $('<audio id="chatAudio"><source src="notifikasi.ogg" type="audio/ogg"><source src="notifikasi.mp3" type="audio/mpeg"><source src="notifikasi.wav" type="audio/wav">').appendTo('body');
  
        $('#chatAudio')[0].play();
        });
        
        }
        
        //chat_div.innerHTML += "<audio autoplay=autoplay controls=controls><source src=notifikasi.mp3 type=audio/mpeg></audio>";
        
        //var audio = new Audio('notifikasi.mp3');
//audio.play();
        if(data.messages.pesan[i].waktu != ""){
        chat_div.innerHTML += "<center><h5><small>&nbsp;&nbsp;&nbsp;&nbsp;"+data.messages.pesan[i].waktu+"</small></a></h5></center>";
        }
      
      if(data.messages.pesan[i].ke == <?php echo $_SESSION[id_user] ?>){
          if(data.messages.pesan[i].status == 'D'){
            chat_div.innerHTML += "<div class=me><div class=foto><a href=?module=siswa&act=detailsiswa&id="+data.messages.pesan[i].nama+"><img src='<?php echo"$dir"; ?>/medium_<?php echo"$dari[foto]"; ?>' class='foto'></a></div><table class=me><tr><td><div class=tgl>"+data.messages.pesan[i].jam+"</div><div class=chat-you><img src='images/icons/bullet_yellow.png'> "+data.messages.pesan[i].teks+"</div></td></tr></table></div><div class=clear></div>";
          }else{
            chat_div.innerHTML += "<div class=me><div class=foto><a href=?module=siswa&act=detailsiswa&id="+data.messages.pesan[i].nama+"><img src='<?php echo"$dir"; ?>/medium_<?php echo"$dari[foto]"; ?>' class='foto'></a></div><table class=me><tr><td><div class=tgl>"+data.messages.pesan[i].jam+"</div><div class=chat-you><img src='images/icons/bullet_gray.png'> "+data.messages.pesan[i].teks+"</div></td></tr></table></div><div class=clear></div>";
          }
        }else{
          if(data.messages.pesan[i].status == 'D'){
            chat_div.innerHTML += "<div class=you><div class=foto2><a href=?module=siswa&act=detailsiswa&id=<?php echo"$user[id_user]"; ?>><img src='<?php echo"$dir2"; ?>/medium_<?php echo"$user[foto]"; ?>' class='foto'></a></div><table class=you><tr><td><div class=tgl>"+data.messages.pesan[i].jam+"</div><div class=chat-me>"+data.messages.pesan[i].teks+" <img src='images/icons/delivery.png'></div></td></tr></table></div><div class=clear></div>";
          }else{
            chat_div.innerHTML += "<div class=you><div class=foto2><a href=?module=siswa&act=detailsiswa&id=<?php echo"$user[id_user]"; ?>><img src='<?php echo"$dir2"; ?>/medium_<?php echo"$user[foto]"; ?>' class='foto'></a></div><table class=you><tr><td><div class=tgl>"+data.messages.pesan[i].jam+"</div><div class=chat-me>"+data.messages.pesan[i].teks+" <img src='images/icons/read.png'></div></td></tr></table></div><div class=clear></div>";
          }
        }
      
        
        
             chat_div.scrollTop = chat_div.scrollHeight; 
             pesanakhir = data.messages.pesan[i].id; 
        
          } 
      } 
      timer = setTimeout("ambilPesan()",1000); 
  } 
  
  function kirimPesan(){ 
  
      pesannya = document.getElementById("pesan").value 
      namaku = document.getElementById("nama").value
    var filenya = document.getElementById("file");
    var fileName = filenya.value;
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
      
      if(pesannya != "" && fileName == ""){ 
          ajaxku.open("GET","ambilchat.php?"+pesanakhir+"&nama="+namaku+"&pesan="+pesannya+"&sid="+Math.random(),true); 
          ajaxku.onreadystatechange = aturAmbilPesan; 
          ajaxku.send(null); 
          document.getElementById("pesan").value = "";  
      document.getElementById("file").value = ""; 
      } 
  } 
  
  function aturKirimPesan(){ 
      clearInterval(timer); 
      ambilPesan(); 
  } 
  function blockSubmit() { 
      
    pesannya = document.getElementById("pesan").value 
      namaku = document.getElementById("nama").value
    var filenya = document.getElementById("file");
    var fileName = filenya.value;
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    
    if(fileName == "" && pesannya != ""){
    kirimPesan(); 
      return false;
    }else if(pesannya =="" && fileName!=""){
      fileSize = filenya.files[0].size;
      if(fileSize <= 20000000){
        if(ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png" || ext == "mp4" ||ext == "MP4" || ext == "3gp" || ext == "rar" || ext == "flv" || ext == "zip" || ext == "avi" || ext == "mp3" || ext == "wav" || ext == "oog" || ext == "pdf" || ext == "txt" || ext == "doc" || ext == "docx" || ext == "xlsx" || ext == "ppt"){
          return true;
        }else{
          alert("file tidak di izinkan,gunakan file gambar/suara/video/office");
          return false;
        }
      }else{
      alert("file terlalu besar maksimal 20 Mb"); 
        return false;
      }     
    }else if(pesannya !="" && fileName!=""){
      fileSize = filenya.files[0].size;
      if(fileSize <= 20000000){
        if(ext == "jpg" || ext == "jpeg" || ext == "bmp" || ext == "gif" || ext == "png" || ext == "mp4" || ext == "MP4" || ext == "3gp" || ext == "rar" || ext == "flv" || ext == "zip" || ext == "avi" || ext == "mp3" || ext == "wav" || ext == "oog" || ext == "pdf" || ext == "txt" || ext == "doc" || ext == "docx" || ext == "xlsx" || ext == "ppt"){
          return true;
        }else{
          alert("file tidak di izinkan,gunakan file gambar/suara/video/office");
          return false;
        }
      }else{
      alert("file terlalu besar maksimal 20 Mb"); 
        return false;
      }
    }else{
     alert("pesan masih kosong");
     return false;
    }
  } 
  
</script> 


<div class="row">
  	<div class="col-md-10">
    	<div class="card shadow" >
      		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          		<h6 class="m-0 font-weight-bold text-primary">Chat Dari : <?= $nama['nama_lengkap'];?> <i class="text-info">==Siswa==</i></h6>
          		<div class="dropdown no-arrow">
                    <a href="?module=ug_chat" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
			  	</div>
      		</div>
      		<div class="card-body" >
      			<div id="div_chat" onload="ambilPesan()">
      			<?php

					$query2 = mysqli_query($koneksi,"SELECT a.*, b.* FROM chat a, user b WHERE a.ke = b.id_user AND ((a.ke='$_SESSION[id_user]' AND a.dari ='$id_siswa') OR (a.ke='$id_siswa' AND a.dari='$_SESSION[id_user]')) ORDER BY idchat"); 
					//echo "SELECT a.*, b.* FROM chat a, siswa b WHERE a.ke = b.id AND ((a.ke='$_SESSION[id_user]' AND a.dari ='$id_siswa') OR (a.ke='$id_siswa' AND a.dari='$_SESSION[id_user]')) ORDER BY idchat";
						$r = mysqli_fetch_array($query2);
						
					$ke=mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$id_siswa'");
					if((mysqli_num_rows($ke))<1){
						echo"<center><h5><small>&nbsp;&nbsp;&nbsp;&nbsp;tidak ada user</small></a></h5></center>";
					}else{
					
						if((mysqli_num_rows($query2))<1){
						echo"<center><h5><small>&nbsp;&nbsp;&nbsp;&nbsp;tidak ada percakapan</small></a></h5></center>";
						}else{
							$date 		= substr("$r[tgl_pesan]",0,10);
							$th			= substr("$date",0,4);
							$bln		= substr("$date",5,2);
							$d 			= substr("$date",8,2);
							$tgl_atas	= $d.'-'.$bln.'-'.$th;
						echo"<center><h5><small>&nbsp;&nbsp;&nbsp;&nbsp;$tgl_atas</small></a></h5></center>";
						}
					}
				?>	
				</div>
      		</div>
      		<div class="card-footer">
      			<?php 
      				if((mysqli_num_rows($ke))<1) {
					echo"<center><h5>
						<small>&nbsp;&nbsp;&nbsp;&nbsp;tidak ada user</small></a>
						</h5></center>";
					}
					else { 
				?>
						<form onSubmit="return blockSubmit();" action="kirimchat.php" method="post" enctype="multipart/form-data"> 
						<div class="form-group">
							<input type="hidden" name="nama" id="nama" value="<?= $id_siswa; ?>">
							<div class="row">
								<input type="text" style="width:90%;" name="pesan" id="pesan" class="form-control"/> &nbsp;
								<button type="submit" class="btn btn-primary" id="kirim">Kirim</button>
							</div> 
						</div>
						<div class="form-group">
							<input type="file" name="file" id="file" class="btn" />
						</div>
					</form>
				<?php } ?> 
      		</div>
  		</div>
	</div>
</div>
	
<?php
}

include "ambilchat.php";
//include "ada.php";
?>