<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>			<div id="site-toolbar">
				<div id="tb-siteinfo">
<?php

    $tbStyle = tpl_getConf('toolbarstyle');

    /* collect all toolbar items from various DW menus: */
	$list = array_merge(
	    (new \dokuwiki\Menu\PageMenu())->getItems(),
	    (new \dokuwiki\Menu\SiteMenu())->getItems(),
	    (new \dokuwiki\Menu\UserMenu())->getItems(),
	);

    /* lists of tb items that should *not* be shown: */
    $exTB  = ['top', 'profile', 'login','logout']; /* don't show in toolbar */
    $exPop = ['top']; /* don't show in popup menu */

    if (tpl_getConf('userinfo') == 'toolbar') {
        my_userinfo(str_repeat(chr(9),5));
    } else {
        tpl_includeFile('siteinfo.html');
    }
 ?>
				</div>
				<div id="site-toolbar-group">
<?php
	pActionlist(str_repeat(chr(9),4), 'pagetools-menu', $list, $exTB, $tbStyle, false);
 ?>
					<div id="tb-menu-group">
						<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-popup" title="<?php echo htmlentities($lang['tools']); ?>">
							<svg class="overflow" viewBox="0 0 24 24"><path d="M16.59,5.59L18,7L12,13L6,7L7.41,5.59L12,10.17L16.59,5.59M16.59,11.59L18,13L12,19L6,13L7.41,11.59L12,16.17L16.59,11.59Z" /></svg>
							<svg class="menu" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
							<span class="label sr-only"><?php echo htmlentities($lang['tools']); ?></span>
						</button>
<?php
	pActionlist(str_repeat(chr(9),6), 'pagetools-popup', $list, $exPop, '', true);
 ?>
					</div>
				</div>
			</div>
