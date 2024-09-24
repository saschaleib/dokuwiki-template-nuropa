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
        //console.info('init()')
	    $p.gui.init();
	    $p.keyboard.init();
	},

	/* all functions that are related to the keyboard control */
	keyboard: {
		init: function() {
			//console.info('keyboard.init()')
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
			//console.info('keyboard.cancel(' + id + ')');
		
			$p.keyboard._list = $p.keyboard._list.filter( it => { 
				return (it.id !== id);
			});
		},
		
		/* clear all waitForKey callbacks */
		clear: function() {
			$p.keyboard._list = [];
		},
		
		/* list of monitoring callbacks */
		_list: [],
		
		/* internal callback functions */
		_callback: {
			
			/* called internally on any registered keyup event */
			_handleKeyUp: function(e) {
				//console.info('_handleKeyUp');
				
				/* collect the interesting info: */
				let target = e.target;
				let data = (e.data ? e.data : null);
				let evt = e.originalEvent;
				let key = evt.key;
				let modifiers = {
					'alt': evt.altKey,
					'ctrl': evt.ctrlKey,
					'meta': evt.metaKey,
					'shift': evt.shiftKey
				};
				
				/* check if any registered callback matches: */
				var match = null;
				$p.keyboard._list.forEach( it => {
					if ((it.id == data) && (it.k == key)) {
						
						// TODO: also check for modifiers! 
						
						//console.log("correct key. calling callback:");
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

            //console.info('gui.init()')

            $p.gui.overlay.init();
            $p.gui.toolbar.init();
            $p.gui.sidebar.init();
            $p.gui.toc.init();
            $p.gui.menus.init();
            $p.gui.editor.init();
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

                    //console.info('gui.toolbar._callback.resized()');

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

		sidebar: {
			
            init: function() {
                //console.info('gui.sidebar.init()');

				const btn = document.getElementById('tg_button');
				if (btn) {
					btn.addEventListener('click', $p.gui.sidebar._onSbBtnClick);
				}
            },
			
			_onSbBtnClick: function(e) {
                //console.info('gui.sidebar._onSbBtnClick()');
				
				const layout = document.getElementById('main-sidebar-layout');
				if (layout) {
					layout.classList.forEach( it => {
						if ( it == 'toggle_auto') {
							layout.classList.replace('toggle_auto', 'toggle_collapse');
						} else if ( it == 'toggle_collapse') {
							layout.classList.replace('toggle_collapse', 'toggle_auto');
						}
					});
				}
				e.preventDefault();
			}
		},

		toc: {
			
            init: function() {
                //console.info('gui.toc.init()');

                jQuery('#toc-menubutton').on('click', $p.gui.toc._onTocBtnClick);

            },
			
			_onTocBtnClick: function() {
                console.info('$p.gui.toc._onTocBtnClick()');
				
				const toc = document.getElementById('toc');
				if (toc) {
					toc.classList.forEach( it => {
						if ( it == 'toggle_hide') {
							toc.classList.replace('toggle_hide', 'toggle_show');
						} else if ( it == 'toggle_show') {
							toc.classList.replace('toggle_show', 'toggle_hide');
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

					//console.info('onMenuButtonClick');
					
					let btn = e.currentTarget;
					
                    try {
                        const tid = jQuery(this).attr('aria-controls');
						const menu = jQuery('#' + tid);
						const align = jQuery(this).attr('data-align_menu');
						
						if ( jQuery(this).attr('aria-expanded') == 'true' ) { /* menu already expanded */

							menu.slideUp(200);
							//jQuery(this).attr('aria-expanded', 'false');
							$p.gui.overlay.hide(tid);
							
						} else {
							
							/* position the menu under the menu button: */
							if (align == 'right') {
								jQuery(menu).css('margin-left', 0 - (jQuery(menu).outerWidth() - 25) + 'px'); 
							}
							
							/* show the menu: */
							menu.slideDown(200, () => {
								let a = jQuery(menu).find('a:visible').first();
								a.focus();
							});
							jQuery(this).attr('aria-expanded', 'true');
							$p.gui.menus._openMenus.push(tid);
							$p.gui.overlay.show($p.gui.menus._callback.onBackdropClick, this);
						}
						
                    } catch(e) {
                        console.error(e);
                    }

                },

                onBackdropClick: function() {

                    try {
						while ( $p.gui.menus._openMenus.length > 0) {
							let tid = $p.gui.menus._openMenus.pop();
							jQuery('#' + tid).slideUp(200);
						}
                    } catch(e) {
                        console.error(e);
                    }

                }
            }
        },

		/* editor */
		editor: {
			init: function() {
                //console.info('gui.editor.init()');

				try {
					if (document.body.classList.contains('mode_edit') || document.body.classList.contains('mode_preview')) {
						
						const tb = document.getElementById('size__ctl');
						if (tb) {
							/* create button: */
							const btn = document.createElement('button');
							btn.setAttribute('id', 'btn_focus');
							btn.setAttribute('title', 'Focus mode');
							btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 12C17 7.55 11.62 5.31 8.46 8.46C5.31 11.61 7.55 17 12 17C14.76 17 17 14.76 17 12M12 15C9.33 15 8 11.77 9.88 9.88C11.77 8 15 9.33 15 12C15 13.66 13.66 15 12 15M5 15H3V19C3 20.1 3.9 21 5 21H9V19H5M5 5H9V3H5C3.9 3 3 3.9 3 5V9H5M19 3H15V5H19V9H21V5C21 3.9 20.1 3 19 3M19 19H15V21H19C20.1 21 21 20.1 21 19V15H19" style="fill:#C0C0C0" /></svg>';
							btn.addEventListener('click', $p.gui.editor._onButtonClick);
							
							tb.appendChild(btn);
						}
					}
				} catch(err) {
					console.error(err);
				}
			},
			
			_onButtonClick: function(e) {

				document.body.classList.toggle('focus_mode');
				e.preventDefault();
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

			show: function(callback, btn) {

				//console.log('$p.gui.overlay.show(...)');

				let ov = jQuery('#overlay');
				if (callback !== undefined && typeof callback == "function") {
					$p.gui.overlay._callbacks.push({
						'cb': callback,
						'btn': btn
					});
				}
				jQuery(ov).show();
				
				/* wait for the Esc key */
				let id = $p.keyboard.waitForKey('body', 'Escape', [], $p.gui.overlay.hide);
				$p.gui.overlay._monitors.push(id);
			},

			hide: function(tid) {
				
				//console.log('$p.gui.overlay.hide("' + tid + '")');

				/* call and remove all callbacks (TODO: only unregister a specific callback!) */
				while ($p.gui.overlay._callbacks.length > 0) {

					const cbObj = $p.gui.overlay._callbacks.pop();
					if (cbObj) {
						if (cbObj.cb && cbObj.cb instanceof Function) {
							cbObj.cb();
						}
						/* update the button attributes */
						if (cbObj.btn) {
							jQuery(cbObj.btn).attr('aria-expanded', false);
						}
					}
				}

				/* if the list is now empty, also cancel all active keyboard monitors */
				if ($p.gui.overlay._callbacks.length < 1) {
					while ($p.gui.overlay._monitors.length > 0) {

						let monitor = $p.gui.overlay._monitors.pop();
						$p.keyboard.cancel(monitor);
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