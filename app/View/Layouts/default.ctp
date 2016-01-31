<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon', 'img/favicon.png');
		
		echo $this->Html->css('bootstrap/bootstrap.min');
		echo $this->Html->css('bootstrap-select/bootstrap-select.min');
		echo $this->Html->css('bootstrap-datetimepicker/bootstrap-datetimepicker.min');
		echo $this->Html->css('styles');
		
		echo $this->Html->script('jquery/jquery-1.12.0.min');
		echo $this->Html->script('bootstrap/bootstrap.min');
		echo $this->Html->script('bootstrap-select/bootstrap-select.min');
//		echo $this->Html->script('bootstrap-select/i18n/defaults-es_CL.min');
		echo $this->Html->script('moment/moment-with-locales');
		echo $this->Html->script('bootstrap-datetimepicker/bootstrap-datetimepicker.min');
		echo $this->Html->script('common');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class="container">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>