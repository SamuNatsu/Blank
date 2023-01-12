<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php minifyBegin($this->options); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php _e('维护中'); ?> | <?php $this->options->title(); ?></title>
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/modern-normalize.min.css'); ?>">
	<style>
		@keyframes spin {
			0% { transform: rotate(0) }
			50% { transform: rotate(180deg) }
			100% { transform: rotate(360deg) }
		}
		a {
			color: inherit;
			text-decoration: none;
		}
		body {
			align-items: center;
			background-color: #eee;
			display: flex;
			flex-direction: column;
			height: 100%;
			justify-content: center;
			position: fixed;
			user-select: none;
			width: 100%;
		}
		footer {
			align-items: center;
			color: #bbb;
			display: flex;
			flex-direction: column;
			font-size: .8em;
			justify-content: center;
		}
		footer div { margin-bottom: .5em }
		.ani-uline { position: relative }
		.ani-uline::after {
			background-color: currentColor;
			content: '';
			height: 2px;
			left: 0;
			position: absolute;
			transform: scaleX(0);
			transform-origin: right;
			transition: transform .2s;
			top: 100%;
			width: 100%;
		}
		.ani-uline:hover::after {
			transform: scaleX(1);
			transform-origin: left;
		}
		#box-1 {
			align-items: center;
			color: #bbb;
			display: flex;
			font-size: 2em;
			font-weight: bold;
			justify-content: center;
			position: relative;
			margin-bottom: 3em;
			width: 100%;
		}
		#box-2 {
			background-color: #eee;
			z-index: 100;
			text-align: center;
			width: 100%;
		}
		#box-3 {
			animation: spin 5s linear infinite;
			border: .5em solid #ddd;
			border-right-color: transparent;
			border-radius: 100%;
			height: 4em;
			position: absolute;
			width: 4em;
		}
		#box-4 {
			animation: spin 10s linear infinite;
			border: .2em solid #ddd;
			border-left-color: transparent;
			border-radius: 100%;
			height: 5em;
			position: absolute;
			width: 5em;
		}
	</style>
</head>
<body>
	<div id="box-1">
		<div id="box-2"><?php _e('站点维护中'); ?></div>
		<div id="box-3"></div>
		<div id="box-4"></div>
	</div>
	<footer>
		<div>© <?= date('Y') ?> <a href="<?php $this->options->siteUrl(); ?>" class="ani-uline"><?php $this->options->title(); ?></a></div>
		<div>Powered by <a class="ani-uline" href="https://typecho.org" target="_blank">Typecho</a> | Theme <a class="ani-uline" href="https://github.com/SamuNatsu/Blank" target="_blank">Blank</a></div>
		<?php if ($this->options->icp): ?>
		<div><a class="ani-uline" href="https://beian.miit.gov.cn/" target="_blank"><?php $this->options->icp(); ?></a></div>
		<?php endif; ?>
		</footer>
</body>
</html>

<?php minifyEnd($this->options); ?>
