/**
 *  Page scripts for the Nuropa Template
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
	    $p.keyboard.init();
	},

	/* all functions that are related to the keyboard control */
	keyboard: {
		init: function() {
			console.info('keyboard.init()')
		},
		
		/* ask to be informed about a specific key press event.
		 * parameters:
		 *   element: String - selector for the element to attach to (e.g. 'body', '#search')
		 *   key: String - name of the key to look for (e.g. 'escape', 's', etc.)
		 *   modifiers: Array - list of modifiers that are required to trigger the event (see docs)
		 *   callback: Function - to be called when the event happens
		 * Returns: String ID of the stored reference
		 */
		waitForKey: function(element, key, modifiers, callback) {
			
			/* build the storage object */
			item = {
				id: null, /* unique ID (see below) */
				e: jQuery(element),
				k: key,
				m: modifiers,
				c: callback,
				$: null /* jQuery object (set below) */
			};
			
			/* give the item a unique id */
			var exists = false;
			do {
				item.id = Math.random().toString(16).slice(2);
				$p.keyboard._list.every( it => {
					if (item.id == it.id) {
						exists = true; /* found a double! */
					}
					return !exists; /* must return true if not found! */
				});
			} while (exists);
			
			//console.log('new keyboard monitoring id ' + item.id + ' on object "' + element + '".');
			
			/* attach the event listener: */
			item.e.on('keyup', null, item.id, $p.keyboard._callback._handleKeyUp);
			$p.keyboard._list.push(item);
			
			return item.id; /* to remove the monitor, if needed. */
		},
		
		/* cancel a specific active monitor (pass the id): */
		cancel: function(id) {
			console.info('keyboard.cancel(' + id + ')');
		
			$p.keyboard._list = $p.keyboard._list.filter( it => { 
				return (it.id !== id);
			});
		},
		
		/* list of monitoring callbacks */
		_list: [],
		
		/* internal callback functions */
		_callback: {
			
			/* called internally on any registered keyup event */
			_handleKeyUp: function(e) {
				console.info('_handleKeyUp');
				
				/* collect the interesting info: */
				let target = e.target;
				let data = (e.data ? e.data : null);
				let evt = e.originalEvent;
				let key = evt.key;
				let modifiers = {
					alt: evt.altKey,
					ctrl: evt.ctrlKey,
					meta: evt.metaKey,
					shift: evt.shiftKey
				}
				
				/* check if any registered callback matches: */
				var match = null;
				$p.keyboard._list.forEach( it => {
					if ((it.id == data) && (it.k == key)) {
						
						// TODO: also check for modifiers! 
						
						console.log("correct key. calling callback:");
						if (it.c && it.c instanceof Function) {
							it.c();
						}
					}
				});
			}
		}
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
					let items = jQuery(tb).children('li');
					
					jQuery(items).show();
                    jQuery(tb).children('li').each((i, it) => {

                        let itemId = jQuery(it).data('type');
                        let itemWidth = jQuery(it).outerWidth(true);
                        if ( (itemId) && (itemPos + itemWidth - 10) < parentWidth ) {
                            shown.push(itemId);
                        } else {
							//jQuery(it).hide();
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

					console.info('onMenuButtonClick');
					
					let btn = e.currentTarget;
					
                    try {
                        let tid = jQuery(this).attr('aria-controls');
						let menu = jQuery('#' + tid);
						
						/* position the menu under the menu button: */
						let mLeft = 0 - (jQuery(menu).outerWidth() - 18);
						jQuery(menu).css('margin-left', mLeft + 'px'); 
						
						/* show the menu: */
                        menu.slideDown(200, () => {
							let a = jQuery(menu).find('a:visible').first();
							a.focus();
						});
                        $p.gui.menus._openMenus.push(tid);
                        $p.gui.overlay.show($p.gui.menus._callback.onBackdropClick);
						
                    } catch(e) {
                        console.error(e);
                    }

                },

                onBackdropClick: function() {

                    try {
                        let tid = $p.gui.menus._openMenus.pop();
                        jQuery('#' + tid).slideUp(200);
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
				    .attr('style', 'display:none;')
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
				
				/* wait for the Esc key */
				let id = $p.keyboard.waitForKey('body', 'Escape', [], $p.gui.overlay.hide);
				$p.gui.overlay._monitors.push(id);
			},

			hide: function() {

				/* cancel all active keyboard monitors */
				while ($p.gui.overlay._monitors.length > 0) {

					let monitor = $p.gui.overlay._monitors.pop();
					$p.keyboard.cancel(monitor);
				}

				/* call all the registered callbacks: */
				while ($p.gui.overlay._callbacks.length > 0) {

					let callback = $p.gui.overlay._callbacks.pop();
					if (callback && callback instanceof Function) {
						callback();
					}
				}

				/* remove the overlay: */
				jQuery('#overlay').hide();
			},
			
			/* reference to the Esc key monitor, if active: */
			_monitors: [],

			/* keep an internal list of functions waiting for a callback: */
			_callbacks: []
		}
	}
};

// init when document is ready:
jQuery(function() { $p.init(); });