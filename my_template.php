<?php
/**
 * Overwriting DokuWiki template functions
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Sascha Leib <sascha@leib.be>
 */

use dokuwiki\Extension\Event;
use dokuwiki\Form\Form;
use dokuwiki\File\PageResolver;

/**
 * Creates the Site logo image link
 *
 */
function my_sitelogo($prefix) {
    global $conf;

	// get the user config:
	$style = tpl_getConf('sitelogo');
	
	if ($style == 'file') {
		
		tpl_includeFile('sitelogo.html');
		
	} else { // $style = 'image'

		// get logo either out of the template images folder or data/media folder
		$logoSize = array();
		$logo = tpl_getMediaFile(array(':logo.svg', ':wiki:logo.svg', ':logo.png', ':wiki:logo.png', 'images/sitelogo.svg'), false, $logoSize);
		echo $prefix . tpl_link( my_homelink(),
			'<img src="'.$logo.'" ' . (is_array($logoSize) && array_key_exists(3, $logoSize) ? $logoSize[3] : '') . ' alt="' . htmlentities($conf['title']) . '">', 'class="logo"', true).TPL_NL;
	}
}

/**
 * get a link to the homepage.
 *
 * wraps the original wl() function to allow overriding in the options
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @returns string (link)
 */
function my_homelink() {
    global $conf;

	$hl = trim(tpl_getConf('homelink'));

	if ( $hl !== '' ) {
		return $hl;
	} else {
		return wl(); // default homelink
	}
}

/**
 * My implementation of the basic userinfo (in the global banner)
 *
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @param  string $prefix to be added before each line
 *
 * @return void
 */
function my_userinfo($prefix = '', $id) {
    global $lang;
    global $INPUT;
    global $INFO;
    global $USERINFO;

    // is there a user logged in?
    $userid = $INPUT->server->str('REMOTE_USER');

    // get the user menu items:
    $items = (new \dokuwiki\Menu\UserMenu())->getItems();

    if (isset($USERINFO['name'])) { // user is loggd in:

		$userIcon = '<svg class="icon" viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>';

        // output the button:
        echo $prefix . "<button id=\"{$id}\" popovertarget=\"{$id}__menu\">" . TPL_NL;
        echo $prefix . TPL_TAB . $userIcon . TPL_NL;
        echo $prefix . TPL_TAB . '<span class="label"><span class="sr-only">' . htmlentities($lang['loggedinas']) . ' </span><span class="name">' . htmlentities($USERINFO['name']) . '</span></span>' . TPL_NL;
        echo $prefix . '</button>' . TPL_NL;

        // add the menu:
        echo $prefix . "<div id=\"{$id}__menu\" popover>" . TPL_NL;
        my_actionlist($prefix . TPL_TAB, null, $items, ['admin'], '', 'menu');
        echo $prefix . '</div>' . TPL_NL;

    } else {
        my_actionlist($prefix, 'user-action-buttons', $items, ['admin'], '');
    }

}

/**
 * Hierarchical breadcrumbs
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Nigel McNie <oracle.shinoda@gmail.com>
 * @author Sean Coates <sean@caedmon.net>
 * @author <fredrik@averpil.com>
 *
 * @param  string $prefix to be added before each line
 *
 */
function my_youarehere($prefix, $position, $return = false) {
	global $conf;
	global $ID;
	global $lang;
	
	// check if enabled
	if (!$conf['youarehere']) return false;
	
    // check if right position:
    if(tpl_getConf('youareherepos') !== $position) return false;
	
	// prepare output string:
	$out =  $prefix . '<h2 class="sr-only">' . $lang['youarehere'] . "</h2>\n";
	
	$parts = explode(':', $ID);
	$count = count($parts);

	/* start the list: */
	$out .= $prefix . "<ol class=\"youarehere\">\n";
	
	/* is there a higher-level homepage defined? */
	$hl = trim(tpl_getConf('homelink'));
	if ( $hl !== '' ) {
		
		$homeIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22" /></svg>';
		
		$out .= $prefix . "\t<li class=\"home hasicon\">" . tpl_link( $hl, $homeIcon . '<span>' . htmlentities(tpl_getLang('homepage')) . '</span>', ' title="' . htmlentities(tpl_getLang('homepage')) . '"', true) . "</li>\n";
	}
	
	// always print the startpage
	$out .= $prefix . "\t<li" . ( $hl == '' ? ' class="home"' : '' ) . '>' . tpl_pagelink(':' . $conf['start'], null, true) . "</li>\n";
	
	// print intermediate namespace links
	$part = '';
	for ($i = 0; $i < $count - 1; $i++) {
		$part .= $parts[$i] . ':';
		$page = $part;
		if ($page == $conf['start']) continue; // Skip startpage
	
		// output
		$out .= $prefix . "\t<li>" . tpl_pagelink($page, null, true) . "</li>\n";
	}
	
	// print current page, skipping start page, skipping for namespace index
	if (isset($page)) {
		$page = (new PageResolver('root'))->resolveId($page);
		if ($page == $part . $parts[$i]) {
			$out .= $prefix . "</ol>\n";
			if ($return) return $out;
			echo $out;
			return true;
		}
	}
	$page = $part . $parts[$i];
	if ($page == $conf['start']) {
		$out .= $prefix . "</ol>\n";
		if ($return) return $out;
		echo $out;
		return true;
	}
	
	/* actual page link and end: */
	$out .= $prefix . "\t<li>" .tpl_pagelink($page, null, true) . "</li>\n";
	$out .= $prefix . "</ol>\n";

	if ($return) return $out;
	echo $out;
	return (bool)$out;
}

/**
 * Print the breadcrumbs trace
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_breadcrumbs($prefix, $position) {
    global $lang;
    global $conf;

    //check if enabled
    if(!$conf['breadcrumbs']) return false;

    // check if right position:
    if(tpl_getConf('breadcrumbpos', 'none') !== $position) return false;

    $crumbs = breadcrumbs(); //setup crumb trace

	/* begin listing */
	echo $prefix . '<p class="sr-only">' . $lang['breadcrumb'] . "</p>\n";
	echo $prefix . "<ol class=\"trace\" reversed>";

    $last = count($crumbs);
    $i    = 0;
    foreach($crumbs as $id => $name) {
        $i++;
		echo '<li' . ($i == $last ? ' class="current"' : '') . '><bdi>' . tpl_link(wl($id), hsc($name), '', true) .  '</bdi></li>';
    }
	echo "</ol>\n";
}

/**
 * Inserts a cleaner version of the TOC
 *
 * This is an update of the original function that renders the TOC directly.
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param  string $prefix to be added before each line
 *
 * @return void
 */
function my_toc($prefix = '') {
    global $TOC;
    global $ACT;
    global $ID;
    global $REV;
    global $INFO;
    global $conf;
    global $lang;
    $toc = array();

    if(is_array($TOC)) {
        // if a TOC was prepared in global scope, always use it
        $toc = $TOC;
    } elseif(($ACT == 'show' || substr($ACT, 0, 6) == 'export') && !$REV && $INFO['exists']) {
        // get TOC from metadata, render if neccessary
        $meta = p_get_metadata($ID, '', METADATA_RENDER_USING_CACHE);
        if(isset($meta['internal']['toc'])) {
            $tocok = $meta['internal']['toc'];
        } else {
            $tocok = true;
        }
        $toc = isset($meta['description']['tableofcontents']) ? $meta['description']['tableofcontents'] : null;
        if(!$tocok || !is_array($toc) || !$conf['tocminheads'] || count($toc) < $conf['tocminheads']) {
            $toc = array();
        }
    } elseif($ACT == 'admin') {
        // try to load admin plugin TOC
        /** @var $plugin AdminPlugin */
        if ($plugin = plugin_getRequestAdminPlugin()) {
            $toc = $plugin->getTOC();
            $TOC = $toc; // avoid later rebuild
        }
    }

	/* inline icons: */
	$iconSvg = "<svg width='100%' height='100%' viewBox='0 0 24 24' version='1.1' xmlns='http://www.w3.org/2000/svg'><rect class='top-bar' x='5' y='6' width='14' height='2'></rect><rect class='middle-bar' x='5' y='11' width='14' height='2'></rect><rect class='bottom-bar' x='5' y='16' width='14' height='2'></rect></svg>";

	$tocView = tpl_getConf('showtoc', 'hide');
	if (in_array($ACT, array('admin'))) {
		$tocView = 'hide';
	}
	
	/* Build the hierarchical list of headline links: */
	if (count($toc) >= intval($conf['tocminheads'])) {
		echo $prefix . "<aside id=\"toc\" class=\"toggle_{$tocView}\">\n";
		echo $prefix . "\t<div id=\"toc_header\"><h2>" . htmlentities($lang['toc']) . "</h2><button type=\"button\" id=\"toc-menubutton\" class=\"tg_button\" title=\"" . htmlentities($lang['toc']) . '" aria-haspopup="true" aria-controls="toc-menu"><span class="sr-only">' . htmlentities($lang['toc']) . "</span>" . $iconSvg . "</button></div>\n";
		echo $prefix . "\t<div id=\"toc-menu\" class=\"tg_content\" role=\"menu\" aria-labelledby=\"toc-menubutton\">";
		
		$level = 0;
		foreach($toc as $it) {
			
			$nl = intval($it['level']);
			$cp = ($nl <=> $level);

			if ($cp > 0) {
				while ($level < $nl) {
					echo "\n" . $prefix . str_repeat("\t", $level*2 + 2) . "<ol>\n";
					$level++;
				}
			} else if ($cp < 0) {
				while ($level > $nl) {
					echo "\n" . $prefix . str_repeat("\t", $level*2) . "</ol>\n" . $prefix . str_repeat("\t", $level*2-1) . "</li>\n";
					$level--;
				}
			} else {
				echo "</li>\n";
			}
			
			$href = ( array_key_exists('link', $it ) ? $it['link'] : '' ) . ( array_key_exists('hid', $it) && $it['hid'] !== '' ? '#' . $it['hid'] : '' );

			echo $prefix . str_repeat("\t", $nl*2 + 1) . '<li  role="presentation"><a role="menuitem" href="' . $href . '">' . htmlentities($it['title']) . "</a>";
			$level = $nl;
		}
		
		for ($i = $level-1; $i > 0; $i--) {
			echo "</li>\n" . $prefix . str_repeat("\t", $i*2 + 1) . "</ol>";
		}
			
		echo "</li>\n" . $prefix . "\t\t</ol>\n" . $prefix . "\t</div>\n" . $prefix . "</aside>\n";
	}
}

/**
 * Insert the main headline for the page
 *
 * provides additional configuration options for this element
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_pagetitle($prefix) {
    global $ID;
    global $conf;
	
	/* get the header style */
	$type = my_headerstyle();

	/* build the headline section */
	echo $prefix . '<div class="type-' . $type . "\">\n";
	switch ($type) {
		case 'file':
			tpl_includeFile('title.html');
			break;
		case 'sitename':
			echo $prefix . "\t<h2 class=\"title\">" . $conf['title'] . "</h2>\n";
			if ($conf['tagline'] && $conf['tagline'] !== '') {
				echo $prefix . "\t<p class=\"tagline\">" . $conf['tagline'] . "</p>\n";
			}
			break;
		case 'pagename':
			echo $prefix . "\t<h1>" . tpl_pagetitle($ID, true) . "</h1>\n";
			break;
		default:
			echo "<pre>UNKNOWN:" . tpl_getConf('pageheadline') . "</pre>";
			break;
	}
	echo $prefix . "</div>\n";

}

/**
 * Print the breadcrumbs trace
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_banner_style() {
    global $ID;
    global $conf;

    /* don't run if banner image is disabled */
    if (trim(tpl_getConf('bannerimg')) == '') return;

    $background = null;

    /* list of extensions to look for (in reverse order) */
    $exts = array('jpg','jpeg','png','svg');

    /* build a list of paths to look at: */
    $cpath = '';
    $paths = array();
    foreach (explode(':', $ID) as $it) {
        foreach ($exts as $ext) {
            array_push($paths, $cpath . trim(tpl_getConf('bannerimg')) . '.' . $ext);
        }
        $cpath .= $it . ':';
    }
    $paths = array_reverse($paths); /* reverse the order */

    /* check if an image exists in one of the paths: */
    foreach ($paths as $rpath) {
        $lpath = mediaFN($rpath);
        if (file_exists($lpath)) {
            $background = ml($rpath, '', true, '', false);
            break;
        }
    }
	/* if not found, use the template banner: */
	if (!$background) {
		$background = tpl_basedir() . 'images/banner.jpg';
	}

    /* find the element height */
    $height = tpl_getConf('bannersize', 'inherit');


    if ($background) {
        echo "background-image: url('" . $background . "');min-height: " . $height . ';';
    }
}

/**
 * Modification of the original tpl_license in the DokuWiki code
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Sascha Leib <ad@hominem.info>
 *
 * @param string $target open link in new tab?
 */
function my_license_button($target) {
    global $license;
    global $conf;
    global $lang;
    if(!$conf['license']) return '';
    if(!is_array($license[$conf['license']])) return '';
    $lic = $license[$conf['license']];
    $trg = ($target) ? ' target="'.$target.'"' : '';

    $src = 'lib/tpl/nuropa/images/license/'.$conf['license'].'.svg';

    echo "\t<a href=\"".$lic['url'].'" rel="license"'.$trg.'><img src="'.DOKU_BASE.$src.'" alt="'.$lic['name'].'" width="80" height="15" /></a>';
}

/**
 * Wrapper for tpl_classes to add template classes
 *
 * @author Sascha Leib <ad@hominem.info>
 */
function my_bodyclasses() {

    global $conf;
	
	$cls = tpl_classes();
	
	/* enable darkmode? */
	if (tpl_getConf('darkmode') === 'auto') {
		$cls .= ' darkmode';
	}
	
	return $cls;
}

/* private helper function to putput a list of action items: */
function my_actionlist($prefix, $id, $list, $exclude, $class = null, $role = null) {

	/* build menu */
	echo $prefix . '<ul'  . ($id ? " id=\"{$id}\"" : '') . ($class ? " class=\"{$class}\"" : ''). ($role ? " role=\"{$role}\"" : '') . ">" . TPL_NL;
	
	/* specific roles for items? */
	$itemRole = null;
	if ($role == 'menu') $itemRole = 'menuitem';
	
	/* add the items: */
	foreach($list as $it) {

		$typ = htmlentities($it->getType());
		if (!in_array($typ, $exclude)) {
			
		echo $prefix . TPL_TAB . "<li data-type=\"{$typ}\"" . ($itemRole ? " role=\"{$itemRole}\"" : '') . '>'
					 . my_HtmlLink($it, false, true)
					 . '</li>' . TPL_NL;
		}
	}
	echo $prefix . '</ul>' . TPL_NL;
};

/* overwrite the asHtmlLink function for my purposes */
function my_HtmlLink($item, $classprefix = 'menuitem ', $svg = true) {

	$attList = $item->getLinkAttributes($classprefix);
	
	// remove the attributes that can cause accessiblity issues:
	unset($attList['title']);
	unset($attList['accesskey']);
	
	// build the HTML:
	$attr = buildAttributes($attList);
	$html = "<a {$attr}>";
	if ($svg) {
		$html .= inlineSVG($item->getSvg());
		$html .= '<span>' . hsc($item->getLabel()) . '</span>';
	} else {
		$html .= hsc($item->getLabel());
	}
	$html .= '</a>';
	return $html;
}

/* helper function to determine the headline style: */
function my_headerstyle() {
	global $ID;
    global $conf;
	
	/* what kind of headline should be included? */
	$type = tpl_getConf('pageheadline');
	if ($type == 'siteonhp') {
		if ($ID == $conf['start']) {
			$type = 'sitename';
		} else {
			$type = 'pagename';
		}
	}
	return $type;
}

/**
 * creates a back to top button
 *
 * Buids the HTML code to insert the "to top" button
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 *
 * @return string html
**/
function my_topbtn($prefix)
{
    global $lang;

	$iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" style="fill:#166DDF" /></svg>';

	$html = $prefix . '<div id="to-top-block">' . TPL_NL .
			$prefix . TPL_TAB . '<a href="#main-content" title="' . $lang['btn_top'] .'">' . TPL_NL .
			$prefix . TPL_TAB . '<span class="sr-only">' . $lang['btn_top'] .' </span>' . TPL_NL .
			$prefix . TPL_TAB . TPL_TAB . $iconSvg . TPL_NL .
			$prefix . TPL_TAB . '</a>' . TPL_NL .
			$prefix . '</div>' . TPL_NL;

    return $html;
}

/**
 * inserts the custom search form
 *
 * modification of "tpl_searchform" from the "template.php" file
 *
 * @param bool $ajax
 * @param bool $autocomplete
 * @return bool
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Sascha Leib <sascha@leib.be>
 */
function my_searchform($ajax = true, $autocomplete = false)
{
    global $lang;
    global $ACT;
    global $QUERY;
    global $ID;

	/* SVG icons to include: */
	$SearchIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" /></svg>';

	$cancelIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>close</title><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>';

    // don't print the search form if search action has been disabled
    if (!actionOK('search')) return false;

    $searchForm = new Form([
        'action' => wl(),
        'method' => 'get',
        'role' => 'search',
        'class' => 'search',
        'id' => 'dw__search',
    ], true);
	
	// begin the search field combo:
    $searchForm->addTagOpen('div')->addClass('field');
	
	// hidden items:
    $searchForm->setHiddenField('do', 'search');
    $searchForm->setHiddenField('id', $ID);
	
	// add an accessible label:
	$searchForm->addHTML('<label for="qsearch__in" class="sr-only">' . $lang['btn_search'] . '</label>');

	
	// the actual search field:
    $searchForm->addTextInput('q')
        ->addClass('edit')
        ->attrs([
            'placeholder' => $lang['btn_search'],
            'autocomplete' => $autocomplete ? 'on' : 'off'
        ])
        ->id('qsearch__in')
        ->val($ACT === 'search' ? $QUERY : '')
        ->useInput(false);

	// cancel button:
    /*$searchForm->addTagOpen('button')
		->attrs([
            'type' => 'reset',
            'title' => $lang['btn_cancel']
        ]);
	$searchForm->addHTML($cancelIcon);
	$searchForm->addHTML('<span>' . $lang['btn_cancel'] . '</span>');
	$searchForm->addTagClose('button');*/

	// search button:
    $searchForm->addTagOpen('button')
		->attrs([
            'type' => 'submit',
            'title' => $lang['btn_search']
        ]);
	$searchForm->addHTML($SearchIcon);
	$searchForm->addHTML('<span>' . $lang['btn_search'] . '</span>');
	$searchForm->addTagClose('button');
	
	// end of search area
    $searchForm->addTagClose('div');
	
	// the results list is moved outside of the field:
    if ($ajax) {
        $searchForm->addTagOpen('div')->id('qsearch__out');
        $searchForm->addTagClose('div');
    }

    echo $searchForm->toHTML('QuickSearch') . TPL_NL;

    return true;
}


/**
 * inserts the Languages menu, if appropriate.
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param  string $prefix to be added before each line
 * @param  string $place the location from where it is called
 * @param  string $checkage should the age of the translation be checked?
 */
function my_langmenu($prefix, $menuId, $checkage = true) {

	global $INFO;
	global $conf;

	// the current page language: 
	$lang = $conf['lang'];

	/* collect the output: */
	$out = '';

	/* try to load the plugin: */
	$trans = plugin_load('helper','translation');
	
	/* plugin available? */
	if ($trans) {
		
		if (!$trans->istranslatable($INFO['id'])) return '';
		if ($checkage) $trans->checkage();

		[, $idpart] = $trans->getTransParts($INFO['id']);

		// get the current language name (in the local language)
		$langName = htmlentities($trans->getLocalName($lang));
	
		/* prepare the menu icon (note that the language code and name are embedded! */
		$svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><title>{$langName}</title><path d='M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z' /><text lengthAdjust='spacingAndGlyphs' x='50%' y='47%' dominant-baseline='middle' text-anchor='middle'>{$lang}</text></svg>";
		
		// prepare the menu button:
		$out .= $prefix . TPL_TAB . "<button aria-haspopup=\"menu\" popovertarget=\"{$menuId}\" title=\"{$trans->getLang('translations')}\">".TPL_NL;
		$out .= $prefix . TPL_TAB . TPL_TAB . $svg . TPL_NL;
		$out .= $prefix . TPL_TAB . TPL_TAB . '<span class="label">' . $langName . '</span>'.TPL_NL;
		$out .= $prefix . TPL_TAB . '</button>'.TPL_NL;

		/* build the menu content */
		$out .= $prefix . TPL_TAB . "<div id=\"{$menuId}\" role=\"menu\" popover>".TPL_NL;
		$out .= $prefix . TPL_TAB . TPL_TAB . '<ul>'.TPL_NL;

		// loop over each language and add it to the menu:
		$filter = tpl_getConf('langfilter', 'all');
		foreach ($trans->translations as $t) {
			$l = ( $t !== '' ? $t : $lang );
			
			[$trg, $lng] = $trans->buildTransID($t, $idpart);
			$trg = cleanID($trg);
			$exists = page_exists($trg, '', false);
			
			/* only show if translation exists? */
			if ($exists || $filter === 'all') {
				$class = 'wikilink' . ( $exists ? '1' : '2');
				$link = wl($trg);
				$current = ($lng == $lang);
				
				$out .= $prefix . TPL_TAB . TPL_TAB . TPL_TAB .'<li>'.TPL_NL;
				$out .= $prefix . TPL_TAB . TPL_TAB . TPL_TAB . TPL_TAB . "<a href=\"{$link}\" lang=\"{$lng}\" hreflang=\"{$lng}\" class=\"{$class}\" role=\"menuitem\"" . ( $current ? ' aria-current="true"' : '' ) . ">".TPL_NL;
				$out .= $prefix . TPL_TAB . TPL_TAB . TPL_TAB . TPL_TAB . TPL_TAB . "<bdi>". $trans->getLocalName($lng) . '</bdi>' . TPL_NL;
				$out .= $prefix . TPL_TAB . TPL_TAB . TPL_TAB . TPL_TAB . '</a>'.TPL_NL;
				$out .= $prefix . TPL_TAB . TPL_TAB . TPL_TAB . '</li>'.TPL_NL;
			}
		}

		// close all open elements:
		$out .= $prefix . TPL_TAB . TPL_TAB . '</ul>'.TPL_NL
			 .	$prefix . TPL_TAB . '</div>'.TPL_NL;

	echo $out; // done.
	}
}