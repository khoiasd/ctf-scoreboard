<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Lab Online" />
		<meta name="author" content="" />
		<title>Trang Quản trị</title>
		<?php foreach ($css_files as $file): ?>
			<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
		<?php foreach ($js_files as $file): ?>
			<script src="<?php echo $file; ?>"></script>
		<?php endforeach; ?>
	</head>
	<body>
		<h2>Trang quản trị!</h2>
		<a href="<?php echo base_url('admin/index'); ?>"><button id="b1">Submition</button></a>
		<a href="<?php echo base_url('admin/logs'); ?>"><button id="b2">Logs</button></a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('admin/organization'); ?>"><button id="b3">Đơn vị</button></a>
		<a href="<?php echo base_url('admin/user'); ?>"><button id="b4">Người dùng</button></a>
		<a href="<?php echo base_url('admin/category'); ?>"><button id="b6">Chủ đề</button></a>
		<a href="<?php echo base_url('admin/challenge'); ?>"><button id="b7">Thử thách</button></a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('admin/notify'); ?>"><button id="b8">Thông báo</button></a>
		<a href="<?php echo base_url('admin/hint'); ?>"><button id="b9">Gợi ý</button></a>&nbsp;|&nbsp;
		&nbsp;<b><?php echo $title; ?></b>&nbsp;&nbsp;<a href="<?php echo base_url('auth/logout')?>">Logout</a>
		<hr/>
		<div>
			<?php echo $output; ?>
		</div>
		<script>
			$(function () {
				$("#b1").button({
					icons: {
						primary: "ui-icon-home"
					}
				});
				$("#b2").button({
					icons: {
						primary: "ui-icon-search"
					}
				});
				$("#b3").button({
					icons: {
						primary: "ui-icon-bookmark"
					}
				});
				$("#b4").button({
					icons: {
						primary: "ui-icon-person"
					}
				});
				$("#b6").button({
					icons: {
						primary: "ui-icon-note"
					}
				});
				$("#b7").button({
					icons: {
						primary: "ui-icon-flag"
					}
				});
				$("#b8").button({
					icons: {
						primary: "ui-icon-signal-diag"
					}
				});
				$("#b9").button({
					icons: {
						primary: "ui-icon-check"
					}
				});
				$("#b10").button({
					icons: {
						primary: "ui-icon-signal"
					}
				});

			});
	
		</script>
	</body>
</html>
