<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

    $tbOption = tpl_getConf('toolbaropt');
?>
		<div id="toolbar__layout" aria-label="<?php echo $lang['tools']; ?>">
			<div class="tb-group">
				<div id="tb__incl_pre">
<?php
						tpl_includeFile('toolbar-pre.html');

?>				</div>
				<div id="tb__option" class="<?php echo $tbOption; ?>">
<?php

    /* lists of tb items that should *not* be shown: */
    $exTB  = ['top', 'profile', 'login','logout']; /* don't show in toolbar */
    $exPop = ['top']; /* don't show in popup menu */

	switch ($tbOption) {
		case 'user':
			my_userinfo(str_repeat(DOKU_TAB,4));
			break;
		case 'langs':
			my_langmenu(str_repeat(DOKU_TAB,4));
			break;
		default:
			/* do nothing */
	}
	
    /* collect all toolbar items from various DW menus: */
	$list = array_merge(
	    (new \dokuwiki\Menu\PageMenu())->getItems(),
	    (new \dokuwiki\Menu\SiteMenu())->getItems(),
	    (new \dokuwiki\Menu\UserMenu())->getItems(),
	);
 ?>
				</div>
				<div id="tb__incl_post">
<?php
					tpl_includeFile('toolbar-post.html');

?>				</div>
			</div>
			<div id="tb__tools__group">
<?php
	$tbStyle = tpl_getConf('toolbarstyle', 'auto');
	my_actionlist(str_repeat(DOKU_TAB,4), 'tb__tools__menu', $list, $exTB, $tbStyle, 'menu');

	/* icons to inline in the code: */
	$rightChevronsIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.59,6.84L12.25,12.5L6.59,18.16L5.89,17.45L10.84,12.5L5.89,7.55L6.59,6.84M10.59,6.84L16.25,12.5L10.59,18.16L9.89,17.45L14.84,12.5L9.89,7.55L10.59,6.84Z" /></svg>';

 ?>
				<div id="tb__menu__group"><button id="pagetools__btn" aria-haspopup="menu" aria-controls="pagetools__popup" title="<?php echo htmlentities($lang['tools']); ?>" data-align_menu="right" popovertarget="pagetools__popup">
						<?php echo $rightChevronsIcon; ?>
						<span class="label sr-only"><?php echo htmlentities($lang['tools']); ?></span>
					</button>
					<nav id="pagetools__popup" class="popup align-right" popover>
<?php
	my_actionlist(str_repeat(DOKU_TAB,6), null, $list, $exPop, null, 'menu');
 ?>
					</nav>
				</div>
			</div>
		</div>
