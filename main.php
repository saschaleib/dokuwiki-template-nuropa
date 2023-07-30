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
<?php my_toolbar("\t\t"); ?>
		<div id="pre-header-layout">
			<div id="pre-header">
				<div id="header-tools-layout">
					<div id="site-logo"><?php my_sitelogo(); ?></div>
					<div id="search-group"><?php tpl_searchform(); ?></div>
					<div id="user-group">
						<?php my_userinfo(str_repeat(chr(9),6)); ?>
					</div>
					<!-- languages menu placeholder -->
				</div>
			</div>
		</div>
		<div id="header-layout">
			<header>
				<div id="header-breadcrumbs">
                    <nav class="breadcrumbs-nav">
                    <!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
                    <?php
                        tpl_flush();
                        my_youarehere(str_repeat(chr(9),4), 'header');
                        my_breadcrumbs(str_repeat(chr(9),4), 'header');
                    ?>				</nav>
                    <!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
                    </nav>
                </div>
				<div id="page-headline-layout">
					<p id="site-headline"><?php tpl_link( my_homelink(), htmlentities($conf['title']), ''); ?></p>
					<p id="site-claim"><?php echo htmlentities($conf['tagline']); ?></p>
				</div>
				<div id="header-navigation">
					<!-- - - - - - - - - SIDEBAR CONTENT - - - - - - - -->
					<?php
								tpl_flush();
								//tpl_include_page($conf['sidebar'], true, true);
					?>
				<!-- - - - - - - - - END OF SIDEBAR CONTENT  - - - - - - - -->
				</div>
			</header>
		</div>
		<div id="post-header-layout" style="<?php my_banner_style(); ?>">
			<div id="post-header">
                <div id="post-header-breadcrumbs">
                    <nav class="breadcrumbs-nav">
                    <!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
                    <?php
                        tpl_flush();
                        my_youarehere(str_repeat(chr(9),4),'banner');
                        my_breadcrumbs(str_repeat(chr(9),4),'banner');
                    ?>				</nav>
                    <!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
                    </nav>
                </div>
            </div>
        </div>
		<div id="main-layout">
			<main id="main-content">
<!-- - - - - - - - - ARTICLE CONTENT - - - - - - - -->
<?php tpl_content(false) ?>
<!-- - - - - - - - - END OF ARTICLE  - - - - - - - -->
			</main>
		</div>
		<div id="footer-layout">
			<footer>
				<?php tpl_includeFile('footer.html') ?>
			</footer>
		</div>
		<div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
	</body>
</html>
