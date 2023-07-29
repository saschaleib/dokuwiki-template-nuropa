/**
 *  Page scripts for Ad Hominem Info Template
 *
 * @author     Sascha Leib <sascha@leib.be>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

/* everything is contained in the $p namespace: */
$p = {

	/* called to initialize the entire script */
	init:	function() {
        console.info('init()')
	    $p.gui.init();
	},

	gui: {

        init: function() {

            console.info('gui.init()')

            $p.gui.overlay.init();
            $p.gui.toolbar.init();
            $p.gui.menus.init();
        },

        toolbar: {

            init: function() {
                //console.info('gui.toolbar.init()');

                jQuery(window).on('resize', function() {
                    $p.gui.toolbar._callback.resized();
                });

                $p.gui.toolbar._callback.resized();
            },

            _callback: {
                resized: function() {

                    console.info('gui.toolbar._callback.resized()');

                    /* recalculate the overflow menu */

                    /* first, check which items are visible in the toolbar: */
                    let tb = jQuery('#pagetools-menu');
                    let parentWidth = jQuery(tb).innerWidth();
                    var itemPos = 0; /* current position */
                    let shown = []; /* list of items shown in the toolbar */

                    jQuery(tb).children('li').each((i, it) => {

                        let itemId = jQuery(it).data('type');
                        let itemWidth = jQuery(it).outerWidth(true);
                        if ( (itemId) && (itemPos + itemWidth - 10) < parentWidth ) {
                            shown.push(itemId);
                        }
                        itemPos += itemWidth;
                    });

                    /* hide already shown items in the popup: */
                    jQuery('#pagetools-popup').children('li').each((i, it) => {
                        let itemId = jQuery(it).data('type');
                        if (itemId) {
                            if (shown.includes(itemId)) {
                                jQuery(it).hide();
                            } else {
                                jQuery(it).show();
                            }
                        }
                    });
                }
            }
        },

        /**
		 * Page.GUI.Menus Namespace
		 *
		 * @description Namespace for all functions related to the page menus
		 */
        menus: {

            init: function() {

                jQuery('*[aria-haspopup=menu]')
                    .on('click', $p.gui.menus._callback.onMenuButtonClick );
            },

            _openMenus: [],

            _callback: {

                onMenuButtonClick: function(e) {

                    try {
                        let tid = jQuery(this).attr('aria-controls');
                        jQuery('#' + tid).slideDown();
                        $p.gui.menus._openMenus.push(tid);
                        $p.gui.overlay.show($p.gui.menus._callback.onBackdropClick);
                    } catch(e) {
                        console.error(e);
                    }

                },

                onBackdropClick: function() {

                    try {
                        let tid = $p.gui.menus._openMenus.pop();
                        jQuery('#' + tid).slideUp();
                    } catch(e) {
                        console.error(e);
                    }

                }
            }
        },

        /**
		 * Page.GUI.Overlay Namespace
		 *
		 * @description Namespace for all functions related to the overlay
		 */
		overlay: {

			init: function() {

				let ov = jQuery('<div>')
				    .attr('id','overlay')
				    .attr('style', 'z-index:99; display:none;')
				    .attr('tabindex', '-1');

				// attach callback
				jQuery(ov).click($p.gui.overlay.hide);

				jQuery('body').append(ov);

			},

			show: function(callback) {

				let ov = jQuery('#overlay');
				if (callback !== undefined && typeof callback == "function") {
					$p.gui.overlay._callbacks.push(callback);
				}
				jQuery(ov).show();
			},

			hide: function() {

				while ($p.gui.overlay._callbacks.length > 0) {

					let cb = $p.gui.overlay._callbacks.pop();
					cb();
				}

				jQuery('#overlay').hide();
			},

			_callbacks: []
		}
	}
};

// init when document is ready:
jQuery(function() { $p.init(); });
