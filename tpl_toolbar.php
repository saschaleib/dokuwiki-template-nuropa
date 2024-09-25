<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>			<div id="site-toolbar">
				<div id="tb-siteinfo">
<?php

    $tbStyle = tpl_getConf('toolbarstyle');

    /* lists of tb items that should *not* be shown: */
    $exTB  = ['top', 'profile', 'login','logout']; /* don't show in toolbar */
    $exPop = ['top']; /* don't show in popup menu */

    if (tpl_getConf('userinfo') == 'toolbar') {
        my_userinfo(str_repeat(chr(9),5));
    } else {
        tpl_includeFile('toolbarinfo.html');
    }
	
    /* collect all toolbar items from various DW menus: */
	$list = array_merge(
	    (new \dokuwiki\Menu\PageMenu())->getItems(),
	    (new \dokuwiki\Menu\SiteMenu())->getItems(),
	    (new \dokuwiki\Menu\UserMenu())->getItems(),
	);

	$svgIcon = "<svg width='100%' height='100%' viewBox='0 0 24 24' version='1.1' xmlns='http://www.w3.org/2000/svg'><rect class='h1' x='5' y='5' width='14' height='2'/><rect class='v1' x='17' y='5' width='2' height='14'/><rect class='h2' x='5' y='17' width='14' height='2'/><rect class='v2' x='5' y='5' width='2' height='14'/></svg>";

 ?>
				</div>
				<div id="site-toolbar-group">
<?php
	my_actionlist(str_repeat(chr(9),4), 'pagetools-menu', $list, $exTB, $tbStyle, false);
 ?>
					<div id="tb-menu-group">
						<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-popup" title="<?php echo htmlentities($lang['tools']); ?>" data-align_menu="right">
							<?php echo $svgIcon; ?>
							<span class="label sr-only"><?php echo htmlentities($lang['tools']); ?></span>
						</button>
<?php
	my_actionlist(str_repeat(chr(9),6), 'pagetools-popup', $list, $exPop, '', true);
 ?>
					</div>
				</div>
			</div>
