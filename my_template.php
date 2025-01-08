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
function my_sitelogo($prefix = "") {
    global $conf;

    // get the user config:
    $style = tpl_getConf("sitelogo");

    if ($style == "file") {
        tpl_includeFile("sitelogo.html");
    } else {
        // $style = 'image'

        // get logo either out of the template images folder or data/media folder
        $logoSize = [];
        $logo = tpl_getMediaFile(
            [
                ":logo.svg",
                ":wiki:logo.svg",
                ":logo.png",
                ":wiki:logo.png",
                "images/sitelogo.svg",
            ],
            false,
            $logoSize
        );
        echo $prefix .
            tpl_link(
                my_homelink(),
                '<img src="' .
                    $logo .
                    '" ' .
                    (is_array($logoSize) && array_key_exists(3, $logoSize)
                        ? $logoSize[3]
                        : "") .
                    ' alt="' .
                    htmlentities($conf["title"]) .
                    '">',
                'class="logo"',
                true
            ) .
            TPL_NL;
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

    $hl = trim(tpl_getConf("homelink"));

    if ($hl !== "") {
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
function my_userinfo($prefix = "", $id) {
    global $lang;
    global $INPUT;
    global $INFO;
    global $USERINFO;

    // is there a user logged in?
    $userid = $INPUT->server->str("REMOTE_USER");

    // get the user menu items:
    $items = (new \dokuwiki\Menu\UserMenu())->getItems();

    if (isset($USERINFO["name"])) {
        // user is loggd in:

        $userIcon =
            '<svg class="icon" viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>';

        // output the button:
        echo $prefix .
            "<button id=\"{$id}\" popovertarget=\"{$id}__menu\" data-isopen=\"false\" title=\"" .
            htmlentities($lang["profile"]) .
            '">' .
            TPL_NL;
        echo $prefix . TPL_TAB . $userIcon . TPL_NL;
        echo $prefix .
            TPL_TAB .
            '<span class="label"><span class="sr-only">' .
            htmlentities($lang["loggedinas"]) .
            ' </span><span class="name">' .
            htmlentities($USERINFO["name"]) .
            "</span></span>" .
            TPL_NL;
        echo $prefix . "</button>" . TPL_NL;

        // add the menu:
        echo $prefix .
            "<div id=\"{$id}__menu\" popover data-controlledby=\"{$id}\">" .
            TPL_NL;
        my_actionlist($prefix . TPL_TAB, null, $items, ["admin"], "", "menu");
        echo $prefix . "</div>" . TPL_NL;
    } else {
        my_actionlist($prefix, "user-action-buttons", $items, ["admin"], "");
    }
}

/**
 * Hierarchical breadcrumbs ("You are here")
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <ad@hominem.info>
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Nigel McNie <oracle.shinoda@gmail.com>
 * @author Sean Coates <sean@caedmon.net>
 * @author <fredrik@averpil.com>
 *
 * @param  string $prefix to be added before each line
 * @param  string $position where to insert the breadcrumbs
 *
 */
function my_youarehere($prefix = "", $position) {
    global $conf;
    global $ID;
    global $lang;

    // check if enabled (default dokuwiki setting)
    if (!$conf['youarehere']) {
        return '';
    }

    // check if right position:
    if (tpl_getConf('youareherepos') !== $position) {
        return '';
    }

    $parts = explode(':', $ID);
    $count = count($parts);
    $label = htmlentities($lang['youarehere']);
    $out = $prefix . "<nav class=\"youarehere\" aria-label=\"{$label}\">" . TPL_NL;

    /* start the list: */ ;
    $out .= $prefix . TPL_TAB . '<ol>' . TPL_NL;

    /* is there a higher-level homepage defined? */
    $hl = trim(tpl_getConf("homelink"));
    if ($hl !== "") {
        $homeIcon =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22" /></svg>';

        $out .=
            $prefix . TPL_TAB . TPL_TAB . '<li class="home hasicon">' .
            tpl_link(
                $hl,
                $homeIcon .
                    "<span>" .
                    htmlentities(tpl_getLang("homepage")) .
                    "</span>",
                ' title="' . htmlentities(tpl_getLang("homepage")) . '"',
                true
            ) .
            '</li>' . TPL_NL;
    }

    // always print the startpage
    $out .= $prefix . TPL_TAB . TPL_TAB . '<li' . ($hl == "" ? ' class="home"' : "") . '>' .
            tpl_pagelink(":" . $conf["start"], null, true) . '</li>' . TPL_NL;

    // print intermediate namespace links
    $part = "";
    for ($i = 0; $i < $count - 1; $i++) {
        $part .= $parts[$i] . ":";
        $page = $part;
        if ($page == $conf["start"]) {
            continue;
        } // Skip startpage

        $flexShrink = $count - $i;

        // output
        $out .= $prefix . TPL_TAB . TPL_TAB . '<li>' . tpl_pagelink($page, null, true) . '</li>' . TPL_NL;
    }

    // print current page, skipping start page, skipping for namespace index
    if (isset($page)) {
        $page = (new PageResolver("root"))->resolveId($page);
        if ($page == $part . $parts[$i]) {
            $out .= $prefix . '</ol>' . TPL_NL;
            return $out;
        }
    }
    $page = $part . $parts[$i];
    if ($page == $conf["start"]) {
        $out .= $prefix . '</ol>' . TPL_NL;
        return $out;
    }

    /* actual page link and end: */
    $out .= $prefix . TPL_TAB . TPL_TAB . '<li aria-current="page">' . tpl_pagelink($page, null, true) . '</li>' . TPL_NL;
    $out .= $prefix . TPL_TAB . '</ol>' . TPL_NL;
    $out .= $prefix . '</nav>' . TPL_NL;

    return $out;
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
function my_breadcrumbs($prefix = "", $position) {
    global $lang;
    global $conf;

    //check if enabled
    if (!$conf["breadcrumbs"]) {
        return '';
    }

    // check if right position:
    if (tpl_getConf("breadcrumbpos", "sidebar") !== $position) {
        return '';
    }

    $crumbs = breadcrumbs(); //setup crumb trace
    $out = '';

    /* begin listing */
    $out .= $prefix . '<p class="sr-only">' . $lang["breadcrumb"] . "</p>\n";
    $out .= $prefix . "<ol class=\"trace\" reversed>";

    $last = count($crumbs);
    $i = 0;
    foreach ($crumbs as $id => $name) {
        $i++;
        $out .= "<li" .
            ($i == $last ? ' class="current"' : "") .
            "><bdi>" .
            tpl_link(wl($id), hsc($name), "", true) .
            "</bdi></li>";
    }
    $out .= "</ol>\n";

    return $out;
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
function my_toc($prefix = "") {
    global $TOC;
    global $ACT;
    global $ID;
    global $REV;
    global $INFO;
    global $conf;
    global $lang;
    $toc = [];

    if (is_array($TOC)) {
        // if a TOC was prepared in global scope, always use it
        $toc = $TOC;
    } elseif (
        ($ACT == "show" || substr($ACT, 0, 6) == "export") &&
        !$REV &&
        $INFO["exists"]
    ) {
        // get TOC from metadata, render if neccessary
        $meta = p_get_metadata($ID, "", METADATA_RENDER_USING_CACHE);
        if (isset($meta["internal"]["toc"])) {
            $tocok = $meta["internal"]["toc"];
        } else {
            $tocok = true;
        }
        $toc = isset($meta["description"]["tableofcontents"])
            ? $meta["description"]["tableofcontents"]
            : null;
        if (
            !$tocok ||
            !is_array($toc) ||
            !$conf["tocminheads"] ||
            count($toc) < $conf["tocminheads"]
        ) {
            $toc = [];
        }
    } elseif ($ACT == "admin") {
        // try to load admin plugin TOC
        /** @var $plugin AdminPlugin */
        if ($plugin = plugin_getRequestAdminPlugin()) {
            $toc = $plugin->getTOC();
            $TOC = $toc; // avoid later rebuild
        }
    }

    /* inline icons: */
    $iconSvg =
        "<svg width='100%' height='100%' viewBox='0 0 24 24' version='1.1' xmlns='http://www.w3.org/2000/svg'><rect class='top-bar' x='5' y='6' width='14' height='2'></rect><rect class='middle-bar' x='5' y='11' width='14' height='2'></rect><rect class='bottom-bar' x='5' y='16' width='14' height='2'></rect></svg>";

    $tocView = tpl_getConf("showtoc", "hide");
    if (in_array($ACT, ["admin"])) {
        $tocView = "hide";
    }

    /* Build the hierarchical list of headline links: */
    if (count($toc) >= intval($conf["tocminheads"])) {
        echo $prefix . "<aside id=\"toc\" class=\"toggle_{$tocView}\">\n";
        echo $prefix .
            "\t<div id=\"toc_header\"><h2>" .
            htmlentities($lang["toc"]) .
            "</h2><button type=\"button\" id=\"toc-menubutton\" class=\"tg_button\" title=\"" .
            htmlentities($lang["toc"]) .
            '" aria-haspopup="true" aria-controls="toc-menu"><span class="sr-only">' .
            htmlentities($lang["toc"]) .
            "</span>" .
            $iconSvg .
            "</button></div>\n";
        echo $prefix .
            "\t<div id=\"toc-menu\" class=\"tg_content\" role=\"menu\" aria-labelledby=\"toc-menubutton\">";

        $level = 0;
        foreach ($toc as $it) {
            $nl = intval($it["level"]);
            $cp = $nl <=> $level;

            if ($cp > 0) {
                while ($level < $nl) {
                    echo "\n" .
                        $prefix .
                        str_repeat("\t", $level * 2 + 2) .
                        "<ol>\n";
                    $level++;
                }
            } elseif ($cp < 0) {
                while ($level > $nl) {
                    echo "\n" .
                        $prefix .
                        str_repeat("\t", $level * 2) .
                        "</ol>\n" .
                        $prefix .
                        str_repeat("\t", $level * 2 - 1) .
                        "</li>\n";
                    $level--;
                }
            } else {
                echo "</li>\n";
            }

            $href =
                (array_key_exists("link", $it) ? $it["link"] : "") .
                (array_key_exists("hid", $it) && $it["hid"] !== ""
                    ? "#" . $it["hid"]
                    : "");

            echo $prefix .
                str_repeat("\t", $nl * 2 + 1) .
                '<li  role="presentation"><a role="menuitem" href="' .
                $href .
                '">' .
                htmlentities($it["title"]) .
                "</a>";
            $level = $nl;
        }

        for ($i = $level - 1; $i > 0; $i--) {
            echo "</li>\n" . $prefix . str_repeat("\t", $i * 2 + 1) . "</ol>";
        }

        echo "</li>\n" .
            $prefix .
            "\t\t</ol>\n" .
            $prefix .
            "\t</div>\n" .
            $prefix .
            "</aside>\n";
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
function my_pagetitle($prefix, $breadcrumbs) {
    global $ID;
    global $conf;

    /* get the header style */
    $type = my_headerstyle();

    /* build the headline section */
    echo $prefix . '<div id="pagetitle__layout">' . TPL_NL;
    echo $prefix . TPL_TAB . "<div class=\"content type-{$type}" .
        ( $breadcrumbs !== '' ? ' has-breadcrumbs' : '' ) . '">' . TPL_NL;

	// insert the breadcrumbs:
   if ($breadcrumbs != '') {
   	echo $breadcrumbs;
    }

    // insert the headline:
    switch ($type) {
        case "file":
            tpl_includeFile("title.html");
            break;
        case "sitename":
            echo $prefix . TPL_TAB . "<h2 class=\"sitename\">{$conf["title"]}</h2>" . TPL_NL;
                if ($conf["tagline"] && $conf["tagline"] !== "") {
                    echo $prefix . TPL_TAB . "<p class=\"tagline\">{$conf["tagline"]}</p>" . TPL_NL;
                }
            break;
        case "pagename":
            echo $prefix . TPL_TAB . TPL_TAB . '<h1>' . tpl_pagetitle($ID, true) . '</h1>' . TPL_NL;
            if ($conf["tagline"] && $conf["tagline"] !== "") {
                echo $prefix . TPL_TAB . "<p class=\"tagline\">{$conf["tagline"]}</p>" . TPL_NL;
            }
            break;
        default:
            echo "<pre>UNKNOWN:" . tpl_getConf("pageheadline") . "</pre>";
            break;
    }

    echo $prefix . TPL_TAB . "</div>" . TPL_NL;
    echo $prefix . "</div>" . TPL_NL;
}

/**
 * Output the page banner
 *
 * Based on original code from the DokuWiki template
 *
 * @author Sascha Leib <ad@hominem.info>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 * @param string $embed any HTML code to be embedded in the banner
 *
 * @return void
 */
function my_banner($prefix, $embed = '') {
    global $ID;
    global $conf;

	$style = ''; // banner style settings

    /* Only look for banner if it is defined: */
    if (trim(tpl_getConf("bannerimg")) !== "") {
		$background = null;

		/* list of extensions to look for (in reverse order) */
		$exts = ["jpg", "jpeg", "png", "svg"];

		/* build a list of paths to look at: */
		$cpath = "";
		$paths = [];
		foreach (explode(":", $ID) as $it) {
			foreach ($exts as $ext) {
				array_push(
					$paths,
					$cpath . trim(tpl_getConf("bannerimg")) . "." . $ext
				);
			}
			$cpath .= $it . ":";
		}
		$paths = array_reverse($paths); /* reverse the order */

		/* check if an image exists in one of the paths: */
		foreach ($paths as $rpath) {
			$lpath = mediaFN($rpath);
			if (file_exists($lpath)) {
				$background = ml($rpath, "", true, "", false);
				break;
			}
		}
		/* if not found, use the template banner: */
		if (!$background) {
			$background = tpl_basedir() . "images/banner.svg";
		}

		if ($background) {
			$style = "background-image: url('{$background}')";
		}
	}

	// build the banner HTML code:
	echo $prefix . '<div id="banner__layout"' . ( $style !== '' ? " style=\"{$style}\"" : '' ) . '>' . TPL_NL;
	echo $prefix . TPL_TAB . '<div class="content">' . TPL_NL;
	echo $embed;
	echo $prefix . TPL_TAB . '</div>' . TPL_NL;
	echo $prefix . '</div>' . TPL_NL;

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
    if (!$conf["license"]) {
        return "";
    }
    if (!is_array($license[$conf["license"]])) {
        return "";
    }
    $lic = $license[$conf["license"]];
    $trg = $target ? ' target="' . $target . '"' : "";

    $src = "lib/tpl/nuropa/images/license/" . $conf["license"] . ".svg";

    echo "\t<a href=\"" .
        $lic["url"] .
        '" rel="license"' .
        $trg .
        '><img src="' .
        DOKU_BASE .
        $src .
        '" alt="' .
        $lic["name"] .
        '" width="80" height="15" /></a>';
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
    if (tpl_getConf("darkmode") !== "off") {
        $cls .= " darkmode";
    }

    return $cls;
}

/* private helper function to output a list of action items: */
function my_actionlist($prefix, $id, $list, $exclude, $class = null, $role = null) {
    /* build menu */
    echo $prefix .
        "<ul" .
        ($id ? " id=\"{$id}\"" : "") .
        ($class ? " class=\"{$class}\"" : "") .
        ($role ? " role=\"{$role}\"" : "") .
        ">" .
        TPL_NL;

    /* specific roles for items? */
    $itemRole = null;
    if ($role == "menu") {
        $itemRole = "menuitem";
    }

    /* add the items: */
    foreach ($list as $it) {
        $typ = htmlentities($it->getType());
        if (!in_array($typ, $exclude)) {
            echo $prefix .
                TPL_TAB .
                "<li data-type=\"{$typ}\"" .
                ($itemRole ? " role=\"{$itemRole}\"" : "") .
                ">" .
                my_HtmlLink($it, false, true) .
                "</li>" .
                TPL_NL;
        }
    }
    echo $prefix . "</ul>" . TPL_NL;
}

/* overwrite the asHtmlLink function for the template */
function my_HtmlLink($item, $classprefix = "menuitem ", $svg = true) {
    $attList = $item->getLinkAttributes($classprefix);

    // remove the attributes that can cause accessiblity issues:
    unset($attList["title"]);
    unset($attList["accesskey"]);

    // build the HTML:
    $attr = buildAttributes($attList);
    $html = "<a {$attr}>";
    if ($svg) {
        $html .= inlineSVG($item->getSvg());
        $html .= "<span>" . hsc($item->getLabel()) . "</span>";
    } else {
        $html .= hsc($item->getLabel());
    }
    $html .= "</a>";
    return $html;
}

/* helper function to determine the headline style: */
function my_headerstyle() {
    global $ID;
    global $conf;

    /* what kind of headline should be included? */
    $type = tpl_getConf("pageheadline");
    if ($type == "siteonhp") {
        if ($ID == $conf["start"]) {
            $type = "sitename";
        } else {
            $type = "pagename";
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
 */
function my_topbtn($prefix) {
    global $lang;

    $iconSvg =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" style="fill:#166DDF" /></svg>';

    $html =
        $prefix .
        '<div id="to-top-block">' .
        TPL_NL .
        $prefix .
        TPL_TAB .
        '<a href="#main-content" title="' .
        $lang["btn_top"] .
        '">' .
        TPL_NL .
        $prefix .
        TPL_TAB .
        '<span class="sr-only">' .
        $lang["btn_top"] .
        " </span>" .
        TPL_NL .
        $prefix .
        TPL_TAB .
        TPL_TAB .
        $iconSvg .
        TPL_NL .
        $prefix .
        TPL_TAB .
        "</a>" .
        TPL_NL .
        $prefix .
        "</div>" .
        TPL_NL;

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
function my_searchform($ajax = true, $autocomplete = false) {
    global $lang;
    global $ACT;
    global $QUERY;
    global $ID;

    /* SVG icons to include: */
    $SearchIcon =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" /></svg>';

    $clearIcon =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,15.59L17.59,17L14,13.41L10.41,17L9,15.59L12.59,12L9,8.41L10.41,7L14,10.59L17.59,7L19,8.41L15.41,12L19,15.59M22,3A2,2 0 0,1 24,5V19A2,2 0 0,1 22,21H7C6.31,21 5.77,20.64 5.41,20.11L0,12L5.41,3.88C5.77,3.35 6.31,3 7,3H22M22,5H7L2.28,12L7,19H22V5Z" /></svg>';

    $closeIcon =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>';

    $busyAnimation = '<svg id="search__busy__anim" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 512 47"><g><circle cx="-14.781" cy="22.328" r="12.813"/><animateTransform attributeName="transform" type="translate" values="88 0;182 0;251 0;298 0;321 0;323.33 0;325.66 0;327.99 0;330.32 0;332.65 0;334.98 0;337.31 0;339.64 0;341.97 0;344.3 0;346.63 0;348.96 0;351.29 0;353.62 0;356 0;379 0;426 0;494 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0;542 0" dur="3330ms" repeatCount="indefinite"/></g><g><circle cx="-50.328" cy="22.328" r="12.797"/><animateTransform attributeName="transform" type="translate" values="0 0;0 0;0 0;0 0;0 0;88 0;182 0;251 0;298 0;321 0;323.33 0;325.66 0;327.99 0;330.32 0;332.65 0;334.98 0;337.31 0;339.64 0;341.97 0;344.3 0;346.63 0;348.96 0;351.29 0;353.62 0;356 0;406 0;452 0;522 0;577 0;577 0;577 0;577 0;577 0;577 0;577 0;577 0;577 0;577 0" dur="3330ms" repeatCount="indefinite"/></g><g><circle cx="-87.203" cy="22.328" r="12.797"/><animateTransform attributeName="transform" type="translate" values="0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;88 0;182 0;251 0;298 0;321 0;323.33 0;325.66 0;327.99 0;330.32 0;332.65 0;334.98 0;337.31 0;339.64 0;341.97 0;344.3 0;346.63 0;348.96 0;351.29 0;353.62 0;356 0;403 0;450 0;520 0;614 0;614 0;614 0;614 0;614 0;614 0" dur="3330ms" repeatCount="indefinite"/></g><g><circle cx="-125.234" cy="22.328" r="12.797"/><animateTransform attributeName="transform" type="translate" values="0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;88 0;182 0;251 0;298 0;321 0;323.33 0;325.66 0;327.99 0;330.32 0;332.65 0;334.98 0;337.31 0;339.64 0;341.97 0;344.3 0;346.63 0;348.96 0;351.29 0;353.62 0;356 0;402 0;448 0;518 0;611 0" dur="3330ms" repeatCount="indefinite"/></g></svg>';

    // don't print the search form if search action has been disabled
    if (!actionOK("search")) {
        return false;
    }

    $searchForm = new Form(
        [
            "action" => wl(),
            "method" => "get",
            "role" => "search",
            "class" => "search",
            "id" => "dw__search",
        ],
        true
    );

    // begin the search widget header
    $searchForm->addTagOpen("header");

    // hidden items:
    $searchForm->setHiddenField("do", "search");
    $searchForm->setHiddenField("id", $ID);

    // Close button:
    $searchForm->addTagOpen("button")->attrs([
        "type" => "button",
        "id" => "search__close",
        "aria-label" => $lang["js"]["mediaclose"],
    ]);
    $searchForm->addHTML(
        '<span class="label">' . $lang["js"]["mediaclose"] . "</span>"
    );
    $searchForm->addHTML($closeIcon);
    $searchForm->addTagClose("button");

    // begin the search field combo:
    $searchForm->addTagOpen("div")->addClass("field");

    // search button:
    $searchForm->addTagOpen("button")->attrs([
        "type" => "submit",
        "aria-label" => $lang["btn_search"],
    ]);
    $searchForm->addHTML($SearchIcon);
    $searchForm->addHTML(
        '<span class="label">' . $lang["btn_search"] . "</span>"
    );
    $searchForm->addTagClose("button");

    // the actual search field:
    $searchForm
        ->addTextInput("q")
        ->addClass("edit")
        ->attrs([
            "placeholder" => $lang["btn_search"],
            "autocomplete" => $autocomplete ? "on" : "off",
            "aria-label" => $lang["btn_search"]
        ])
        ->id("qsearch__in")
        ->val($ACT === "search" ? $QUERY : "")
        ->useInput(false);

    /* cancel button:
	$searchForm->addTagOpen('button')
		->attrs([
			'type' => 'reset',
			'aria-label' => $lang['btn_delete']
		]);
	$searchForm->addHTML($clearIcon);
	$searchForm->addHTML('<span class="label">' . $lang['btn_delete'] . '</span>');
	$searchForm->addTagClose('button'); */

    // end of search area
    $searchForm->addTagClose("div");

    // add busy animation as SVG:
    $searchForm->addHTML($busyAnimation);

    // end of header area
    $searchForm->addTagClose("header");

    // the results list is moved outside of the field:
    if ($ajax) {
        $searchForm->addTagOpen("div")->id("qsearch__out");
        $searchForm->addTagClose("div");
    }

    echo $searchForm->toHTML("QuickSearch") . TPL_NL;

    return true;
}

/**
 * inserts the Site menu, if appropriate.
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @param  string $prefix to be added before each line
 * @param  string $place the location from where it is called
 */
function my_sitemenu($prefix, $place) {
    global $INFO;
    global $conf;

    // pre-define the menu icon:
    $menuSvg = '<svg class="menu" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>';

	$overflowSvg = '<svg class="overflow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.59,7.41L7,6L13,12L7,18L5.59,16.59L10.17,12L5.59,7.41M11.59,7.41L13,6L19,12L13,18L11.59,16.59L16.17,12L11.59,7.41Z" /></svg>';

    // should the element be displayed at all?
    $menuplace = tpl_getConf('menuplace', '');
    if ($menuplace !== $place) {
        return;
    }

    // check if the menu page exists:
    $menu = tpl_getConf('sitemenu', '');
    if ($menu == '') {
        return;
    }

	$menuTitle = "Menu"; // TODO: get the title from the language settings

    // output the menu wrappers:
    echo $prefix . '<div id="sitemenu__layout">' . TPL_NL;
    echo $prefix . TPL_TAB . '<nav class="content">' . TPL_NL;
    echo $prefix . str_repeat(TPL_TAB, 2) . '<div class="menu-layout">' . TPL_NL;

    $menuId = page_findnearest($menu);
    if ($menuId) {
        $html = p_wiki_xhtml($menuId, '', false);
        echo $html;
    }

    // add the menu button:
	echo $prefix . str_repeat(TPL_TAB, 3) . '<div id="menu__overflow__group">' . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 4) . "<button id=\"menu__button\" aria-haspopup=\"menu\" aria-controls=\"menu__overflow\" title=\"{$menuTitle}\" data-alignment=\"right\" popovertarget=\"menu__overflow\">" . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 5) . $menuSvg . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 5) . $overflowSvg . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 5) . "<span>{$menuTitle}</span>". TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 4) . '</button>' . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 4) . "<div id=\"menu__overflow\" popover data-controlledby=\"menu__button\">" . TPL_NL;
	echo $html;
	echo $prefix . str_repeat(TPL_TAB, 4) . '</div>' . TPL_NL;
	echo $prefix . str_repeat(TPL_TAB, 3) . '</div>' . TPL_NL;

	// close the menu wrappers:
	echo $prefix . str_repeat(TPL_TAB, 2) . '</div>' . TPL_NL;
	echo $prefix . TPL_TAB . '</nav>' . TPL_NL;
	echo $prefix . '</div>' . TPL_NL;
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
function my_langmenu($prefix, $btnId, $checkage = true) {
    global $INFO;
    global $conf;

    // the current page language:
    $lang = substr($conf["lang"], 0, 2);

    /* collect the output: */
    $out = "";

    /* try to load the plugin: */
    $trans = plugin_load("helper", "translation");

    /* plugin available? */
    if ($trans) {
        if (!$trans->istranslatable($INFO["id"])) {
            return;
        }
        if ($checkage) {
            $trans->checkage();
        }

        [, $idpart] = $trans->getTransParts($INFO["id"]);

        // get the current language name (in the local language)
        $langName = htmlentities($trans->getLocalName($lang));

        /* prepare the menu icon (note that the language code and name are embedded! */
        $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><title>{$langName}</title><path d='M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z' /><text lengthAdjust='spacingAndGlyphs' x='50%' y='47%' dominant-baseline='middle' text-anchor='middle'>{$lang}</text></svg>";

        // prepare the menu button:
        $out .=
            $prefix .
            TPL_TAB .
            "<button id=\"{$btnId}\" aria-haspopup=\"menu\" popovertarget=\"{$btnId}__menu\" data-isopen=\"false\" title=\"{$trans->getLang("translations")}\">" .
            TPL_NL;
        $out .= $prefix . TPL_TAB . TPL_TAB . $svg . TPL_NL;
        $out .=
            $prefix .
            TPL_TAB .
            TPL_TAB .
            '<span class="label">' .
            $langName .
            "</span>" .
            TPL_NL;
        $out .= $prefix . TPL_TAB . "</button>" . TPL_NL;

        /* build the menu content */
        $out .=
            $prefix .
            TPL_TAB .
            "<div id=\"{$btnId}__menu\" role=\"menu\" popover data-controlledby=\"{$btnId}\">" .
            TPL_NL;
        $out .= $prefix . TPL_TAB . TPL_TAB . "<ul>" . TPL_NL;

        // loop over each language and add it to the menu:
        $filter = tpl_getConf("langfilter", "all");
        foreach ($trans->translations as $t) {
            $l = $t !== "" ? $t : $lang;

            [$trg, $lng] = $trans->buildTransID($t, $idpart);
            $trg = cleanID($trg);
            $exists = page_exists($trg, "", false);

            /* only show if translation exists? */
            if ($exists || $filter === "all") {
                $class = "wikilink" . ($exists ? "1" : "2");
                $link = wl($trg);
                $current = $lng == $lang;

                $out .= $prefix . str_repeat(TPL_TAB,3) . "<li>" . TPL_NL;
                $out .= $prefix . str_repeat(TPL_TAB,4) . "<a href=\"{$link}\" lang=\""
                      . substr($lng, 0, 2)
                      . "\" hreflang=\"{$lng}\" class=\"{$class}\" role=\"menuitem\""
                      . ($current ? ' aria-current="true"' : "") . ">" . TPL_NL;
                $out .= $prefix . str_repeat(TPL_TAB,5) . "<bdi>" . $trans->getLocalName($lng) . "</bdi>" . TPL_NL;
                $out .= $prefix . str_repeat(TPL_TAB,4) . "</a>" . TPL_NL;
                $out .= $prefix . str_repeat(TPL_TAB,3) . "</li>" . TPL_NL;
            }
        }

        // close all open elements:
        $out .=
            $prefix .
            TPL_TAB .
            TPL_TAB .
            "</ul>" .
            TPL_NL .
            $prefix .
            TPL_TAB .
            "</div>" .
            TPL_NL;
    } else {
        $out .= "<!-- no translation plugin loaded -->";
    }

    echo $out; // done.
}
