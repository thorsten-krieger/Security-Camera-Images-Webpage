<?php
define('CACHE_DIR', 'cache/');
define('CAM_DIR', '/home/instar/cam');
define('CAM_CACHE', CACHE_DIR.'cam%s.json');
define('REG_MATCH_CAM', '/^10D1DC002B4D\(Krieger\)_1_([0-9]{4})([0-9]{4})([0-9]{4})[0-9]{2}_[0-9]+\.jpg$/');

if (!is_dir(__DIR__.'/'.CACHE_DIR))
	mkdir(__DIR__.'/'.CACHE_DIR);


set_time_limit(0);


/**
 * @param $j null|int
 * @param $m null|int
 * @return array|mixed
 */
function scanCamDir($j = null, $m = null)
{
	$bilder = array();

	# existiert eine Cache Datei?
	$jahrMonatCamCacheFile = sprintf(CAM_CACHE, $j.$m);
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') $j'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export($j, true)."</pre><br />\r\n";
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') $m'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export($m, true)."</pre><br />\r\n";
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') $jahrMonatCamCacheFile'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export($jahrMonatCamCacheFile, true)."</pre><br />\r\n";
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') file_exists($jahrMonatCamCacheFile)'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export(file_exists($jahrMonatCamCacheFile), true)."</pre><br />\r\n";
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') filemtime($jahrMonatCamCacheFile)'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export(date('d.m.Y H:i:s', filemtime($jahrMonatCamCacheFile)), true)."</pre><br />\r\n";
//	echo '<strong>'.basename(__FILE__).'('.__LINE__.') (time() - 60 * 15)'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export(date('d.m.Y H:i:s', (time() - 60 * 15)), true)."</pre><br />\r\n";
	if (file_exists($jahrMonatCamCacheFile))
		# Wie alt ist die Cache Datei?
		# maximal 15 Minuten
		if (filemtime($jahrMonatCamCacheFile) < (time() - 60 * 15))
			unlink($jahrMonatCamCacheFile);

	# DEBUG
	unlink($jahrMonatCamCacheFile);

	if (!file_exists($jahrMonatCamCacheFile))
	{
		# 1. Ebene = Jahr
		$files = scandir(CAM_DIR);
//		echo '<strong>'.basename(__FILE__).'('.__LINE__.') $files'.'</strong><pre style="font-size: 11pt; text-align:left;">'."\r\n".var_export($files, true)."</pre><br />\r\n";
		if (is_array($files))
		{
			foreach ($files as $jahr)
			{
				if (is_dir(CAM_DIR.'/'.$jahr) && ($jahr != '.') && ($jahr != '..'))
				{
					$bilder[$jahr] = array();

					# 2. Ebene = Monat + Tag
					$files2 = scandir(CAM_DIR.'/'.$jahr);
					if (is_array($files2))
					{
						foreach ($files2 as $monat_tag)
						{
							if (is_dir(CAM_DIR.'/'.$jahr.'/'.$monat_tag) && ($monat_tag != '.') && ($monat_tag != '..'))
							{
								$monat = intval(substr($monat_tag, 0, 2));
								$tag   = intval(substr($monat_tag, 2, 2));

								if (!isset($bilder[$jahr][$monat]))
									$bilder[$jahr][$monat] = array();
								if (!isset($bilder[$jahr][$monat][$tag]))
									$bilder[$jahr][$monat][$tag] = array();

								# Bilder aus dem aktuellen Monat laden
								if (($jahr == $j) && ($monat == $m))
								{
									# 3. Ebene = Stunde + Minute
									$files3 = scandir(CAM_DIR.'/'.$jahr.'/'.$monat_tag);
									if (is_array($files3))
									{
										foreach ($files3 as $std_min)
										{
											if (is_dir(CAM_DIR.'/'.$jahr.'/'.$monat_tag.'/'.$std_min) && ($std_min != '.') && ($std_min != '..'))
											{
												# 4. Ebene = Bild
												$files4 = scandir(CAM_DIR.'/'.$jahr.'/'.$monat_tag.'/'.$std_min);
												if (is_array($files4))
												{
													foreach ($files4 as $cam)
													{
														if (($cam != '.') && ($cam != '..'))
														{
															$bilder[$jahr][$monat][$tag][] = $jahr.'/'.$monat_tag.'/'.$std_min.'/'.$cam;
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		krsort($bilder);

		file_put_contents($jahrMonatCamCacheFile, json_encode($bilder));
	}
	else
		$bilder = json_decode(file_get_contents($jahrMonatCamCacheFile), true);

	return $bilder;
}


/**
 * @param string $cam
 */
function genThumb($cam)
{
	if (!file_exists(__DIR__.'/'.CACHE_DIR.$cam))
	{
		# Thumbnail des Bildes erstellen
		try
		{
			if (preg_match(REG_MATCH_CAM, basename($cam), $r))
			{
//				echo __LINE__.': $r: '; var_dump($r); echo "\r\n";
//				echo __LINE__.': $r: '; var_dump(!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'); echo "\r\n";
//				echo __LINE__.': $r: '; var_dump(!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/'); echo "\r\n";
//				echo __LINE__.': $r: '; var_dump(!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/'.$r[3].'/'); echo "\r\n";
//				if (!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'))
					@mkdir(__DIR__.'/'.CACHE_DIR.$r[1].'/');

//				if (!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/'))
					@mkdir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/');

//				if (!is_dir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/'.$r[3].'/'))
					@mkdir(__DIR__.'/'.CACHE_DIR.$r[1].'/'.$r[2].'/'.$r[3].'/');

				$im = new Imagick(CAM_DIR.'/'.$cam);
				$im->thumbnailImage(165, 124);
				$im->writeImage(__DIR__.'/'.CACHE_DIR.$cam);
				unset($im);
			}
		}
		catch (ImagickException $e)
		{
			echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
//		exit;
	}
}


/**
 * @param $a
 * @param $b
 * @return int
 */
function sortCamPics($a, $b)
{
	$a = filemtime(CAM_DIR.'/'.$a);
	$b = filemtime(CAM_DIR.'/'.$b);

	if ($a == $b)
		return 0;

	return ($a > $b) ? -1 : 1;
}
