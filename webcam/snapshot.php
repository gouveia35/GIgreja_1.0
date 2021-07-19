<?php
if (!is_dir("../fotosmembros")) {
  mkdir("../fotosmembros");
}
$ft =  $_POST['foto'];
$id_membro = $_POST['id'];
$data = base64_decode($ft);
$filename = "foto".$id_membro.".png";
file_put_contents('../fotosmembros/'.$filename, $data);
echo $filename;

?>
