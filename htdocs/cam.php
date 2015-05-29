<?php
include_once('functions.php');

# Bild laden und ausgeben
$cam = CAM_DIR.'/'.$_GET['c'];
if (file_exists($cam))
{
	$finfo = new finfo(FILEINFO_MIME);
	$type = $finfo->file($cam);

	header('Content-type: '.$type);
	echo file_get_contents($cam);
}
