<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php minifyBegin($this->options); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php _e('什么也没有'); ?> | <?php $this->options->title(); ?></title>
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/modern-normalize.min.css'); ?>">
	<style>
		@keyframes up-down {
			0% { transform: translateY(-2px) }
			50% { transform: translateY(2px) }
			100% { transform: translateY(-2px) }
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
		.prog-bar--init {
			background: repeating-linear-gradient(-45deg, #bbb, #bbb 10px, #eee 10px, #eee 15px);
			height: 5px;
			width: 100%;
			transition: width 5s linear;
		}
		.prog-bar--down { width: 0% }
		#box-1 {
			color: #bbb;
			display: flex;
			font-size: 5em;
			font-weight: bold;
		}
		#box-2 {
			display: flex;
			flex-direction: column;
			font-size: .5em;
		}
		#prog-side {
			border: 1px solid #bbb;
			border-radius: 3px;
			height: 5px;
			margin: .3em 0;
			overflow: hidden;
			width: 200px;
		}
		#back-btn {
			animation: up-down 1s infinite;
			color: #bbb;
			cursor: pointer;
			font-weight: bold;
			transition: color .2s;
		}
		#back-btn:hover { color: #888 }
	</style>
	<script>
		window.onload = function () {
			let el = document.querySelector("#back-btn")
			el.onclick = function() { window.location.href = "<?php $this->options->siteUrl(); ?>" }
			el = document.querySelector("#prog-bar")
			el.addEventListener("transitionend", function() { window.location.href = "<?php $this->options->siteUrl(); ?>" })
			el.classList.add("prog-bar--down")
		}
	</script>
</head>
<body>
	<div id="box-1">
		<div>404</div>
		<div id="box-2">
			<div>Not</div>
			<div>Found</div>
		</div>
	</div>
	<div id="prog-side"><div class="prog-bar--init" id="prog-bar"></div></div>
	<div id="back-btn"><?php _e('回到主页'); ?></div>
</body>
</html>

<?php minifyEnd($this->options); ?>
