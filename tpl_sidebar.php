<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

	$hasSidebar = page_findnearest($conf['sidebar']);
	
	$svgIcon = "<svg viewBox='0 0 24 24' version='1.1' xmlns='http://www.w3.org/2000/svg'><rect class='top' x='3' y='6' width='18' height='2'/><path class='arrow1' d='M20.245,10.664l1.384,1.384l-4.95,4.95l-1.384,-1.384l4.95,-4.95Z'/><rect class='mid' x='3' y='11.1' width='18' height='2'/><path class='arrow2' d='M21.629,12.038l-1.384,1.384l-4.95,-4.95l1.384,-1.384l4.95,4.95Z'/><rect class='bottom' x='3' y='16.2' width='18' height='2'/></svg>";

?>				<div id="side__bar">
					<header id="sb__header">
						<button type="button" id="tg__button" title="<?php echo $lang['sidebar'] ?>" data-state="default" aria-controls="sidebar__nav"><?php echo $svgIcon; ?></button>
						<h2 class="sr-only"><?php echo $lang['sidebar'] ?></h2>
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
