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
		<meta charset="utf-8">
		<title><?php tpl_pagetitle() ?> - <?php echo strip_tags($conf['title']) ?></title>
		<?php tpl_metaheaders() ?>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php echo tpl_favicon(array('favicon', 'mobile')) ?>
		<?php tpl_includeFile('meta.html') ?>

	</head>
	<body class="site <?php echo my_bodyclasses(); ?>" data-pageid="<?php echo htmlentities($ID); ?>">
		<div id="skip-link">
			<a href="#main-content"><?php echo htmlentities($lang['skip_to_content']); ?></a>
		</div>
		<div id="toolbar-layout">
			<!-- BEGIN TOOLBAR -->
<?php include('tpl_toolbar.php') ?>
			<!-- END OF TOOLBAR -->
		</div>
        <div id="header-layout">
			<!-- BEGIN HEADER -->
<?php include('tpl_header.php') ?>
			<!-- END HEADER -->
		</div>
		<div id="main-layout">
<?php $hasSidebar = page_findnearest($conf['sidebar']);
 ?>			<div id="main-sidebar-layout" class="toggle_<?php echo ( ($hasSidebar && ($ACT=='show')) ? 'auto' : 'hide' ); ?>">
				<!-- BEGIN SIDEBAR -->
<?php include('tpl_sidebar.php') ?>
				<!-- END OF SIDEBAR -->
				<main id="main-content"<?php echo ( my_headerstyle() == 'pagename' ? ' class="nopageheadline"' : '' ); ?>>
<?php my_toc(str_repeat("\t", 5)); 
?><!-- - - - - - - - - ARTICLE CONTENT - - - - - - - -->

<?php tpl_content(false) ?>

<!-- - - - - - - - - END OF ARTICLE  - - - - - - - -->
					<div id="to-top-block">
						<a href="#dokuwiki__top" title="Back to top [t]" rel="nofollow" accesskey="t">
							<span class="sr-only">Back to top</span>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13 20h-2V8l-5.5 5.5-1.42-1.42L12 4.16l7.92 7.92-1.42 1.42L13 8v12z"></path></svg>
						</a>
					</div>
				</main>
			</div>
		</div>
		<div id="footer-layout">
<?php include('tpl_footer.php') ?>
			<div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
		</div>
	</body>
</html>
