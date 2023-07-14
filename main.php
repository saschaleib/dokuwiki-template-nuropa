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
			<a href="#main-content"><?php echo $lang['skip_to_content']; ?></a>
		</div>
		<div id="toolbar-layout">
			<div id="site-toolbar">
				<div class="siteinfo" role="banner"><?php tpl_includeFile('siteinfo.html'); ?></div>
				<div class="toolbar-actions"><?php my_toolbar(); ?></div>
			</div>
		</div>
		<div id="pre-header-layout">
			<div id="pre-header">
				<div id="header-tools-layout">
					<div id="site-logo"><?php my_sitelogo(); ?></div>
					<div id="search-group"><?php tpl_searchform(); ?></div>
					<div id="user-block">
						<?php my_userinfo(str_repeat(chr(9),6)); ?>
					</div>
					<div id="languages-block">
						<button type="button" id="language-menu-button" aria-haspopup="menu" aria-controls="languages-menu" aria-expanded="false">
							<span class="icon" data-lang="en"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z" /></svg></span>
							<span class="label">English</span>
						</button>
						<div id="languages-menu" role="menu">
							<div class="close-popup"><h3>Available languages:</h3><button type="button" id="close-lang-menu" title="Close this menu">✖</button></div>
							<div class="language-list-layout">
								<ul role="group">
									<li role="presentation"><a role="menuitem" href="_themes/template-2023/__blocks/header/index_en.htm" hreflang="en" class="active" lang="en">English<span class="sr-only">(current language)</span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="header-layout">
			<header>
				<div id="page-headline-layout">
					<p id="site-headline"><?php tpl_link( my_homelink(), htmlentities($conf['title']), ''); ?></p>
					<p id="site-claim"><?php echo htmlentities($conf['tagline']); ?></p>
				</div>
				<div id="header-navigation">
					<header>
						<button type="button" id="nav-menu" aria-label="Menu"><span>Menu</span></button>
					</header>
					<!-- - - - - - - - - SIDEBAR CONTENT - - - - - - - -->
					<?php
								tpl_flush();
								tpl_include_page($conf['sidebar'], true, true);
					?>
					<!-- - - - - - - - - END OF SIDEBAR CONTENT  - - - - - - - -->
				</div>
				<div id="headline-breadcrumb-layout">
					<div id="headline-breadcrumbs">
						<nav id="breadcrumbs-nav">
						<!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
						<?php
							tpl_flush();
							if($conf['youarehere']) { my_youarehere(str_repeat(chr(9),4)); }
						?>				</nav>
						<!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
						</nav>
					</div>
				</div>
			</header>
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
		<?php include('tpl_footer.php'); ?>
		<div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
	</body>
</html>