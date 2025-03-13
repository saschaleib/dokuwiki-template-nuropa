<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

$meta['toolbarstyle'] = array('multichoice',
						'_choices' => array('auto', 'compact'));

$meta['toolbaropt'] = array('multichoice',
						'_choices' => array('user', 'langs', 'none'));

$meta['sitelogo'] = array('multichoice',
						'_choices' => array('image', 'file'));

$meta['searchbaropt'] = array('multichoice',
						'_choices' => array('user', 'langs', 'none'));

$meta['pageheadline'] = array('multichoice',
						'_choices' => array('sitename', 'pagename', 'file'));

$meta['sitemenu'] = array('string');
$meta['menuplace'] = array('multichoice',
						'_choices' => array('none', 'before', 'between', 'after'));

$meta['youareherepos'] = array('multichoice',
						'_choices' => array('menu', 'banner'));

$meta['bannerimg'] = array('string');

$meta['showtoc'] = array('multichoice',
						'_choices' => array('show', 'hide'));

$meta['homelink'] = array('string');

$meta['darkmode'] = array('multichoice',
						'_choices' => array('off', 'auto'));

$meta['neatnik'] = array('multichoice',
						'_choices' => array('true', 'false'));