<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

	$hasSidebar = page_findnearest($conf['sidebar']);
	
	$svgIcon = "<svg viewBox='0 0 24 24' version='1.1' xmlns='http://www.w3.org/2000/svg'><rect class='top-bar' x='3' y='7' width='14' height='3'/><rect class='middle-bar' x='3' y='12' width='14' height='3'/><rect class='bottom-bar' x='3' y='17' width='14' height='3'/></svg>";

?>				<div id="side__bar">
					<header id="sb__header">
						<h2 class="sr-only"><?php echo $lang['sidebar'] ?></h2>
						<button type="button" id="tg__button" title="<?php echo $lang['sidebar'] ?>" data-state="default" aria-controls="sidebar__nav"><?php echo $svgIcon; ?></button>
					</header>
					<nav id="sidebar__nav">
<!-- - - - - - - - - Sidebar header include - - - - - - - -->
<?php
			tpl_flush();
			tpl_includeFile('sidebarheader.html');
?><!-- - - - - - - - - Sidebar page - - - - - - - -->
<?php

			tpl_include_page($conf['sidebar'], true, true);
?><!-- - - - - - - - - Sidebarfooter include - - - - - - - -->
<?php
			tpl_includeFile('sidebarfooter.html');
?>
<!-- - - - - - - - - End of Sidebarfooter - - - - - - - -->
					</nav>
				</div>
