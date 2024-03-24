<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

	$hasSidebar = page_findnearest($conf['sidebar']);
	$showSidebar = $hasSidebar && ($ACT=='show');

?>				<div id="sidebar" class="toggle <?php echo ( $showSidebar ? 'auto' : 'hide' ); ?>">
					<button type="button" class="tg_button" title="<?php echo $lang['sidebar'] ?>"><span><?php echo $lang['sidebar'] ?></span></button>
					<nav id="sbNavigation">
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
