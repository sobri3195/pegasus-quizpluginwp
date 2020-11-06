<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

An uncaught Exception was encountered

Type:        <?php echo get_class($exception), "\n"; ?>
Message:     <?php echo xss_clean($message), "\n"; ?>
Filename:    <?php echo $exception->getFile(), "\n"; ?>
Line Number: <?php echo $exception->getLine(); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	Backtrace:
	<?php	foreach ($exception->getTrace() as $error): ?>
		<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
		File: <?php echo xss_clean($error['file']), "\n"; ?>
		Line: <?php echo xss_clean($error['line']), "\n"; ?>
		Function: <?php echo xss_clean($error['function']), "\n\n"; ?>
		<?php		endif ?>
	<?php	endforeach ?>

<?php endif ?>
