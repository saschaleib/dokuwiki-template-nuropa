<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

/* TOOLBAR */

/* appearance of the toolbar ('auto' or 'compact') */
$meta['toolbarstyle'] = array('multichoice',
						'_choices' => array('auto', 'compact'));
						
/* what should be displayed in the left corner of the toolbar? ('user', 'langs' or 'none') */
$meta['toolbaropt'] = array('multichoice',
						'_choices' => array('user', 'langs', 'none'));

/* SEARCHBAR */

/* How should the site logo be displayed ('image' or 'file') */
$meta['sitelogo'] = array('multichoice',
						'_choices' => array('image', 'file'));

/* what should be displayed to the right of the search field? ('user', 'langs' or 'none') */
$meta['searchbaropt'] = array('multichoice',
						'_choices' => array('user', 'langs', 'none'));

/* HEADLINE BAR */

/* what to show in place of a page headline */
$meta['pageheadline'] = array('multichoice',
						'_choices' => array('sitename', 'pagename', 'file'));

/* MENU BAR */

/* should a site-wide horizontal menu be added? */
$meta['menuplace'] = array('multichoice',
						'_choices' => array('none', 'before', 'between', 'after'));

/* DW name of the menu page */
$meta['sitemenu'] = array('string');

/* where to show the two hierarchical lists */
$meta['youareherepos'] = array('multichoice',
						'_choices' => array('menu', 'banner', 'content'));

/* name of the banner file (without extension) and min height of the banner bar */
$meta['bannerimg'] = array('string');

/* CONTENT HEADER */

/* show the TOC by default */
$meta['showtoc'] = array('multichoice',
						'_choices' => array('show', 'hide'));

/* show page title above TOC */
$meta['titleabovetoc'] = array('multichoice',
						'_choices' => array('true', 'false'));

/* GENERAL SETTINGS */

/* allow overriding the home link */
$meta['homelink'] = array('string');

/* allow dark mode styling ('off' or 'auto') */
$meta['darkmode'] = array('multichoice',
						'_choices' => array('off', 'auto'));

/* enable 'neatnik' mode (boolean) */
$meta['neatnik'] = array('multichoice',
						'_choices' => array('true', 'false'));