<?php
/**
 * Nuropa Template
 *
 * @link     http://dokuwiki.org/template
 * @author   Sascha Leib <ad@hominem.info>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */

require_once('my_template.php');

define('TPL_TAB', ( tpl_getConf('neatnik') == 'true' ? chr(9) : '' ));
define('TPL_NL', ( tpl_getConf('neatnik') == 'true' ? chr(10) : '' ));

?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>"><head>
<meta charset="utf-8">
<title><?php tpl_pagetitle() ?> - <?php echo strip_tags($conf['title']) ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php tpl_metaheaders() ?>
<?php echo tpl_favicon(array('favicon', 'mobile')) ?>
<?php tpl_includeFile('meta.html') ?>
</head>
<body class="site <?php echo my_bodyclasses(); ?>" data-pageid="<?php echo htmlentities($ID); ?>">
	<div id="skip__link">
		<a href="#main__content"><?php echo htmlentities($lang['skip_to_content']); ?></a>
	</div>
<?php include('tpl_toolbar.php') ?>
<?php include('tpl_header.php') ?>
	<div id="main__layout">
<?php $hasSidebar = page_findnearest($conf['sidebar']);
 ?>		<div id="main__sidebar__layout" data-config="toggle_<?php echo ( ($hasSidebar && ($ACT=='show')) ? 'auto' : 'hide' ); ?>" data-state="default">
<?php include('tpl_sidebar.php') ?>
			<main id="main__content"<?php echo ( my_headerstyle() == 'pagename' ? ' class="nopageheadline"' : '' ); ?>>
				<h1 class="pagetitle"><?php tpl_pagetitle() ?></h1>
<?php my_toc(str_repeat(TPL_TAB, 4)); 
?><!-- - - - - - - - - ARTICLE CONTENT - - - - - - - -->
<?php tpl_content(false) ?>
<!-- - - - - - - - - END OF ARTICLE  - - - - - - - -->
<?php echo my_topbtn(str_repeat(TPL_TAB, 4)); ?>
			</main>
		</div>
	</div>
<?php include('tpl_footer.php') ?>
</body>
</html>