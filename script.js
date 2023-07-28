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
	    $p.gui.init();
	},

	gui: {

        init: function() {

            $p.gui.overlay.init();
            $p.gui.menus.init();
        },

        /**
		 * Page.GUI.Manus Namespace
		 *
		 * @description Namespace for all functions related to the page menus
		 */
        menus: {

            init: function() {

                jQuery('.menu-button').on('click', () => { alert("click"); } );

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
				if (zIdx !== undefined) {
					jQuery(ov).css('z-index', parseInt(zIdx));
				}
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
