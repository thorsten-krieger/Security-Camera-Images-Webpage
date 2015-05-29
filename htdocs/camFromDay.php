<?php
if (isset($_GET) && is_array($_GET))
{
	preg_match('/^([0-9]{2})([0-9]{2})([0-9]{4})$/', $_GET['date'], $_r);

	include_once('functions.php');

	$_GET['j']  = intval($_r[3]);
	$_GET['m']  = intval($_r[2]);
	$tag        = intval($_r[1]);
	$bilder     = scanCamDir($_GET['j'], $_GET['m']);
	$cam_bilder = $bilder[intval($_r[3])][intval($_r[2])][intval($_r[1])];
	$c          = 0;

	usort($cam_bilder, 'sortCamPics');

	foreach ($cam_bilder as $cam)
	{
		genThumb($cam);

		$mtime     = filemtime(CAM_DIR.'/'.$cam);
		$alt_title = date('d.m.Y H:i:s', $mtime);

		?>
		<div class="col-md-2">
			<a href="cam.php?c=<?php echo urlencode($cam); ?>" class="fancybox" title="<?php echo $alt_title; ?>" rel="<?php echo sprintf('%02d.%02d.', $tag, $_GET['m']); ?>">
				<figure>
					<img src="<?php echo CACHE_DIR.$cam; ?>" alt="<?php echo $alt_title; ?>" title="<?php echo $alt_title; ?>" width="165" height="124"/>
					<figcaption><?php echo $alt_title; ?></figcaption>
				</figure>
			</a>
		</div>
	<?php
	}
}