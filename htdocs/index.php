<?php include_once('functions.php'); ?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Krieger Cam</title>

	<script src="js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

	<link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/default.css" rel="stylesheet">

	<link rel="shortcut icon" href="favicon.ico" type="image/x-ico; charset=binary">
	<link rel="icon" href="favicon.ico" type="image/x-ico; charset=binary">
</head>
<body>
<?php

if (!isset($_GET['j']) || empty($_GET['j']))
{
	$_GET['j'] = date('Y');
	$_GET['m'] = date('m');
}
elseif (!isset($_GET['m']) || empty($_GET['m']))
{
	$_GET['m'] = date('m');
}

$_GET['j'] = intval($_GET['j']);
$_GET['m'] = intval($_GET['m']);

$bilder = scanCamDir();

?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Krieger Cam</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php foreach ($bilder as $j => $v1) : ?>
					<li class="dropdown <?php echo($j == $_GET['j'] ? 'active' : ''); ?>">
						<a href="?j=<?php echo $j; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $j; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<?php
							$monate = $v1;
							krsort($monate);
							?>
							<?php foreach ($monate as $m => $v2) : ?>
								<?php $date = new DateTime($j.'-'.$m.'-01'); ?>
								<li class="<?php echo(($m == $_GET['m']) && ($j == $_GET['j']) ? 'active' : ''); ?>">
									<a href="?j=<?php echo $j; ?>&amp;m=<?php echo $m; ?>"><?php echo $date->format('M'); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>
<div id="pics" class="container">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<?php
		if (isset($bilder[$_GET['j']][$_GET['m']]))
		{
			$bilderDesMonats = $bilder[$_GET['j']][$_GET['m']];
			krsort($bilderDesMonats);

			foreach ($bilderDesMonats as $tag => $cam_bilder)
			{
				$css_id = sprintf('%02d%02d%04d', $tag, $_GET['m'], $_GET['j']);
				?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading<?php echo $css_id; ?>">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $css_id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $css_id; ?>" cam-date="<?php echo $css_id; ?>">
								<?php echo sprintf('%02d.%02d.', $tag, $_GET['m']); ?>
							</a>
						</h4>
					</div>
					<div id="collapse<?php echo $css_id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $css_id; ?>">
						<div class="panel-body"></div>
					</div>
				</div>
			<?php
			}
		}
		?>
	</div>
</div>
<script src="js/bootstrap.min.js"></script>
<script src="js/function.js"></script>
</body>
</html>