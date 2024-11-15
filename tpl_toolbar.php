<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

	/* icons to inline in the code: */
	$rightChevronsIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.59,7.41L7,6L13,12L7,18L5.59,16.59L10.17,12L5.59,7.41M11.59,7.41L13,6L19,12L13,18L11.59,16.59L16.17,12L11.59,7.41Z" /></svg>';

?>			<div id="tb__siteinfo">
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
 ?>
			</div>
			<div id="tb__tools__group">
<?php
	my_actionlist(str_repeat(chr(9),4), 'tb__tools__menu', $list, $exTB, $tbStyle, false);

 ?>
				<div id="tb-menu-group">
					<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-popup" title="<?php echo htmlentities($lang['tools']); ?>" data-align_menu="right">
						<?php echo $rightChevronsIcon; ?>
						<span class="label sr-only"><?php echo htmlentities($lang['tools']); ?></span>
					</button>
<?php
	my_actionlist(str_repeat(chr(9),6), 'pagetools-popup', $list, $exPop, '', true);
 ?>
				</div>
			</div>
