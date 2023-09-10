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

?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>">
	<head>
		<meta charset="utf-8" />
		<title><?php tpl_pagetitle() ?> - <?php echo strip_tags($conf['title']) ?></title>
		<?php tpl_metaheaders() ?>
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<?php echo tpl_favicon(array('favicon', 'mobile')) ?>
		<?php tpl_includeFile('meta.html') ?>

	</head>
	<body class="site <?php echo trim(tpl_classes()); ?>" data-pageid="<?php echo htmlentities($ID); ?>">
		<div id="skip-link">
			<a href="#main-content"><?php echo htmlentities($lang['skip_to_content']); ?></a>
		</div>
		<div id="toolbar-layout"><!-- begin toolbar -->
<?php include('tpl_toolbar.php') ?>
		</div><!-- end of toolbar -->
        <div id="header-layout"><!-- begin header -->
<?php include('tpl_header.php') ?>
        </div><!-- end header -->
		<div id="main-layout">
			<main id="main-content"<?php echo ( my_headerstyle() == 'pagename' ? ' class="nopageheadline"' : '' ); ?>>
<!-- - - - - - - - - ARTICLE CONTENT - - - - - - - -->
<?php tpl_content(false) ?>
<!-- - - - - - - - - END OF ARTICLE  - - - - - - - -->
			</main>
		</div>
<?php include('tpl_footer.php') ?>
		<div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
	</body>
</html>
