<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once 'var/TinyHtmlMinifier.php';
require_once 'var/ThemeView.php';

function themeConfig($form)
{
	$maintainMode = new \Typecho\Widget\Helper\Form\Element\Radio(
		'maintainMode',
		[
			'on'	=> _t('开启'),
			'off'	=> _t('关闭')
		],
		'off',
		_t('【全局】维护模式'),
		_t('启动维护模式后，只有登录的管理员才能进入站点')
	);
	$form->addInput($maintainMode);

	$minify = new \Typecho\Widget\Helper\Form\Element\Radio(
		'minify',
		[
			'on'	=> _t('开启'),
			'off'	=> _t('关闭')
		],
		'off',
		_t('【全局】HTML 压缩'),
		_t('启动 HTML 压缩以减少服务器传输压力<br>注 1：使用 URL 引用的文件不会参与压缩，请使用对应的 min 版本<br>注 2：如果网页出现了意料外的表现，请检查是否有不规范的 HTML 代码，或关闭压缩<br>注 3：已知在 HTML 中嵌入 JS 代码时，使用箭头函数会导致压缩错误并破坏脚本完整性，请使用 URL 引用该类脚本或使用 function 替代箭头函数')
	);
	$form->addInput($minify);

	$ccLicense = new \Typecho\Widget\Helper\Form\Element\Select(
		'ccLicense',
		[
			'zero'		=> _t('CC0 公共领域'),
			'by'		=> _t('BY 署名'),
			'by-sa'		=> _t('BY-SA 署名-相同方式共享'),
			'by-nc'		=> _t('BY-NC 署名-非商业使用'),
			'by-nd' 	=> _t('BY-ND 署名-禁止演绎'),
			'by-nc-sa'	=> _t('BY-NC-SA 署名-非商业使用-相同方式共享'),
			'by-nc-nd'	=> _t('BY-NC-ND 署名-非商业使用-禁止演绎')
		],
		'by-sa',
		_t('【全局】默认知识共享许可协议'),
		_t('推荐且默认使用 BY-SA 署名-相同方式共享<br>您可以在 <a href="https://creativecommons.org/licenses/" target="_blank">https://creativecommons.org/licenses/</a> 查看各种协议的规定<br>如果您想为某一篇文章或页面指定特定的知识共享许可协议，请在该文章或页面中添加自定义字段“cc”，并填写如下对应的协议字符串（不包括引号）：<br>&emsp;“zero”：CC0 公共领域<br>&emsp;“by”：BY 署名<br>&emsp;“by-sa”：BY-SA 署名-相同方式共享<br>&emsp;“by-nc”：BY-NC 署名-非商业使用<br>&emsp;“by-nd”：BY-ND 署名-禁止演绎<br>&emsp;“by-nc-sa”：BY-NC-SA 署名-非商业使用-相同方式共享<br>&emsp;“by-nc-nd”：BY-NC-ND 署名-非商业使用-禁止演绎<br>若“cc”字段无法匹配上面的协议字符串，则该文章或页面不会显示知识共享许可协议（可能会为您的维权带来一定麻烦）')
	);
	$form->addInput($ccLicense);

	$favicon = new \Typecho\Widget\Helper\Form\Element\Text(
		'favicon',
		null,
		null,
		_t('【全局】网站图标链接'),
		_t('留空则根据浏览器规则自动调用 /favicon.ico，可能会产生恼人的 404 错误')
	);
	$form->addInput($favicon);

	$_gravatarServiceWarning = '';
	if (defined('__TYPECHO_GRAVATAR_PREFIX__'))
	{
		$_gravatarServiceWarning = _t('<br><span style="color:red">警告：检测到 PHP 常量 __TYPECHO_GRAVATAR_PREFIX__ 已被定义为 "') . __TYPECHO_GRAVATAR_PREFIX__ . _t('"，导致镜像替换无法进行，请注释或删除相关代码</span>');
	}
	$gravatarService = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarService',
		null,
		'https://gravatar.loli.net/avatar/',
		_t('【全局】Gravatar 服务镜像替换'),
		_t('推荐且默认使用 https://gravatar.loli.net/avatar/，当然你可以换成其他 Gravatar 镜像') . $_gravatarServiceWarning
	);
	$form->addInput($gravatarService);

	$modernNormalize = new \Typecho\Widget\Helper\Form\Element\Text(
		'modernNormalize',
		null,
		null,
		_t('【全局】Modern Normalize 镜像替换'),
		_t('留空则使用主题自带的 modern-normalize.min.css（v1.1.0）<br>您可以在 <a href="https://github.com/sindresorhus/modern-normalize" target="_blank">https://github.com/sindresorhus/modern-normalize</a> 查看该样式表详情')
	);
	$form->addInput($modernNormalize);

	$pjaxService = new \Typecho\Widget\Helper\Form\Element\Text(
		'pjaxService',
		null,
		null,
		_t('【全局】Pjax 镜像替换'),
		_t('留空则使用主题自带的 pjax.min.js（v0.2.8）<br>注：该 Pjax 不是 jQuery 插件，是独立模块')
	);
	$form->addInput($pjaxService);

	$hljsScript = new \Typecho\Widget\Helper\Form\Element\Text(
		'hljsScript',
		null,
		null,
		_t('【全局】【Highlight.js】脚本镜像替换'),
		_t('留空则使用主题自带的 highlight.min.js（v11.7.0）')
	);
	$form->addInput($hljsScript);

	$hljsNumber = new \Typecho\Widget\Helper\Form\Element\Text(
		'hljsNumber',
		null,
		null,
		_t('【全局】【Highlight.js】行号插件镜像替换'),
		_t('留空则使用主题自带的 highlightjs-line-numbers.min.js（v2.8.0）<br>您可以在 <a href="https://github.com/wcoder/highlightjs-line-numbers.js" target="_blank">https://github.com/wcoder/highlightjs-line-numbers.js</a> 查看该插件的相关详情')
	);
	$form->addInput($hljsNumber);

	$katexCss = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexCss',
		null,
		null,
		_t('【全局】【Katex】样式表镜像替换'),
		_t('留空则使用主题自带的 katex.min.css（v0.16.4）')
	);
	$form->addInput($katexCss);

	$katexScript = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexScript',
		null,
		null,
		_t('【全局】【Katex】脚本镜像替换'),
		_t('留空则使用主题自带的 katex.min.js（v0.16.4）')
	);
	$form->addInput($katexScript);

	$katexAuto = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexAuto',
		null,
		null,
		_t('【全局】【Katex】自动渲染脚本镜像替换'),
		_t('留空则使用主题自带的 auto-render.min.js（v0.16.4）<style>hr{border:0;border-top:2px dashed #aaa;heigh:0;margin:2em 0}</style><hr/>')
	);
	$form->addInput($katexAuto);

	$customGravatar = new \Typecho\Widget\Helper\Form\Element\Text(
		'customGravatar',
		null,
		null,
		_t('【侧边栏】头像图片链接'),
		_t('留空则使用 Typecho 默认定义的头像链接（邮箱 + Gravatar 服务）')
	);
	$form->addInput($customGravatar);

	$gravatarMail = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarMail',
		null,
		null,
		_t('【侧边栏】【Gravatar】博主邮箱'),
		_t('由于 Typecho 是多用户系统，而 Blank 主题是单用户主题，所以调用 Typecho 自带的 Gravatar 生成会有指向不明确的问题，因此请手动设置')
	);
	$form->addInput($gravatarMail);

	$gravatarName = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarName',
		null,
		null,
		_t('【侧边栏】【Gravatar】博主名称'),
		_t('由于 Typecho 是多用户系统，而 Blank 主题是单用户主题，所以调用 Typecho 自带的显示名称会有指向不明确的问题，因此请手动设置')
	);
	$form->addInput($gravatarName);

	$biliUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'biliUrl',
		null,
		null,
		_t('【侧边栏】【链接】哔哩哔哩')
	);
	$form->addInput($biliUrl);

	$giteeUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'giteeUrl',
		null,
		null,
		_t('【侧边栏】【链接】码云')
	);
	$form->addInput($giteeUrl);

	$githubUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'githubUrl',
		null,
		null,
		_t('【侧边栏】【链接】Github')
	);
	$form->addInput($githubUrl);

	$mailUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'mailUrl',
		null,
		null,
		_t('【侧边栏】【链接】邮箱')
	);
	$form->addInput($mailUrl);

	$telegramUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'telegramUrl',
		null,
		null,
		_t('【侧边栏】【链接】电报')
	);
	$form->addInput($telegramUrl);

	$twitterUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'twitterUrl',
		null,
		null,
		_t('【侧边栏】【链接】推特')
	);
	$form->addInput($twitterUrl);

	$weiboUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'weiboUrl',
		null,
		null,
		_t('【侧边栏】【链接】微博'),
		'<hr/>'
	);
	$form->addInput($weiboUrl);

	$icp = new \Typecho\Widget\Helper\Form\Element\Text(
		'icp',
		null,
		null,
		_t('【页脚】ICP 备案信息'),
		'<hr/>'
	);
	$form->addInput($icp);

	$customHead = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'customHead',
		null,
		'',
		_t('【自定义】头部标签附加代码'),
		_t('在 &lt;head&gt;&lt;/head&gt; 标签中追加代码')
	);
	$form->addInput($customHead);

	$customFooter = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'customFooter',
		null,
		'',
		_t('【自定义】页脚附加代码'),
		_t('在 &lt;footer&gt;&lt;/footer&gt; 标签中追加代码')
	);
	$form->addInput($customFooter);
}

function minifyBegin($options): void
{
	ob_start('ob_gzhandler');
	if ($options->minify === 'on')
	{
		ob_start();
	}
}

function minifyEnd($options): void
{
	if ($options->minify === 'on')
	{
		$html = ob_get_clean();
		$minifier = new \Minifier\TinyHtmlMinifier([]);
		$html = $minifier->minify($html) . '<!-- Minified -->';
		echo $html;
	}
	ob_end_flush();
}

function removeBeforeMore(string $contents): string
{
	$matches = [];
	if (preg_match('/<!--more-->/', $contents, $matches, PREG_OFFSET_CAPTURE) === 1)
	{
		return substr($contents, $matches[0][1] + 11);
	}
	return $contents;
}
