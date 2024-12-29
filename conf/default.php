<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

/* TOOLBAR */

/* what should be displayed in the left corner of the toolbar? ('user', 'langs' or 'none') */
$conf['toolbaropt'] = 'user';

/* appearance of the toolbar ('auto' or 'compact') */
$conf['toolbarstyle'] = 'auto';

/* SEARCHBAR */

/* How should the site logo be displayed ('image' or 'file') */
$conf['sitelogo'] = 'image';

/* what should be displayed to the right of the search field? ('user', 'langs' or 'none') */
$conf['searchbaropt'] = 'langs';

/* HEADLINE BAR */

/* what to show in place of a page headline */
$conf['pageheadline'] = 'sitename';

/* MENU BAR */

/* should a site-wide horizontal menu be added? */
$conf['sitemenu'] = 'menu';
$conf['menuplace'] = 'between';

/* where to show the two hierarchical lists */
$conf['youareherepos'] = 'menu';
//$conf['breadcrumbpos'] = 'sidebar';

/* name of the banner file (without extension) and min height of the banner bar */
$conf['bannerimg'] = 'banner'; /* empty = no banner bar */

/* show the TOC by default */
$conf['showtoc'] = 'show';

/* allow overriding the home link */
$conf['homelink'] = '';

/* GENERAL Settings */

/* allow dark mode styling ('off' or 'auto') */
$conf['darkmode'] = 'auto';

/* enable 'neatnik' mode (boolean) */
$conf['neatnik'] = 'true';