<?php
include_once('htdocs/functions.php');

$files = scandir(CAM_DIR);
if (is_array($files))
{
	echo count($files)."\n";
	foreach ($files as $file)
	{
		if (preg_match(REG_MATCH_CAM, $file, $r))
		{
//			echo __LINE__.': $file: '; var_dump($file); echo "\r\n";
			echo $file."\n";

			if (!is_dir(CAM_DIR.'/'.$r[1].'/'))
				mkdir(CAM_DIR.'/'.$r[1].'/');

			if (!is_dir(CAM_DIR.'/'.$r[1].'/'.$r[2].'/'))
				mkdir(CAM_DIR.'/'.$r[1].'/'.$r[2].'/');

			if (!is_dir(CAM_DIR.'/'.$r[1].'/'.$r[2].'/'.$r[3].'/'))
				mkdir(CAM_DIR.'/'.$r[1].'/'.$r[2].'/'.$r[3].'/');

			$destFile = $r[1].'/'.$r[2].'/'.$r[3].'/'.$file;

			# Verschieben
			if (!rename(CAM_DIR.'/'.$file, CAM_DIR.'/'.$destFile))
				exit;

			# Rechte setzen
			chmod(CAM_DIR.'/'.$destFile, 0644);
			# Thumbnail generieren
			genThumb($destFile);
		}
	}
}
