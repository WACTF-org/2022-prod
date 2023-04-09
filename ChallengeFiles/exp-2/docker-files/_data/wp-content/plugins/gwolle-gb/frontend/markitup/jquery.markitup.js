// ----------------------------------------------------------------------------
// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.14
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2007-2012 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function(jQuery) {
	jQuery.fn.markItUp = function(settings, extraSettings) {
		var method, params, options, ctrlKey, shiftKey, altKey; ctrlKey = shiftKey = altKey = false;

		if (typeof settings == 'string') {
			method = settings;
			params = extraSettings;
		}

		options = {	id:						'',
					nameSpace:				'',
					root:					'',
					previewHandler:			false,
					previewInWindow:		'', // 'width=800, height=600, resizable=yes, scrollbars=yes'
					previewInElement:		'',
					previewAutoRefresh:		true,
					previewPosition:		'after',
					previewTemplatePath:	'~/templates/preview.html',
					previewParser:			false,
					previewParserPath:		'',
					previewParserVar:		'data',
					resizeHandle:			true,
					beforeInsert:			'',
					afterInsert:			'',
					onEnter:				{},
					onShiftEnter:			{},
					onCtrlEnter:			{},
					onTab:					{},
					markupSet:			[	{ /* set */ } ]
				};
		jQuery.extend(options, settings, extraSettings);

		// compute markItUp! path
		if (!options.root) {
			jQuery('script').each(function(a, tag) {
				miuScript = jQuery(tag).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.jsjQuery/);
				if (miuScript !== null) {
					options.root = miuScript[1];
				}
			});
		}

		// Quick patch to keep compatibility with jQuery 1.9
		var uaMatch = function(ua) {
			ua = ua.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
				/(webkit)[ \/]([\w.]+)/.exec(ua) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
				/(msie) ([\w.]+)/.exec(ua) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
				[];

			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};
		var matched = uaMatch( navigator.userAgent );
		var browser = {};

		if (matched.browser) {
			browser[matched.browser] = true;
			browser.version = matched.version;
		}
		if (browser.chrome) {
			browser.webkit = true;
		} else if (browser.webkit) {
			browser.safari = true;
		}

		return this.each(function() {
			var jQueryjQuery, textarea, levels, scrollPosition, caretPosition, caretOffset,
				clicked, hash, header, footer, previewWindow, template, iFrame, abort;
			jQueryjQuery = jQuery(this);
			textarea = this;
			levels = [];
			abort = false;
			scrollPosition = caretPosition = 0;
			caretOffset = -1;

			options.previewParserPath = localize(options.previewParserPath);
			options.previewTemplatePath = localize(options.previewTemplatePath);

			if (method) {
				switch(method) {
					case 'remove':
						remove();
					break;
					case 'insert':
						markup(params);
					break;
					default:
						jQuery.error('Method ' +  method + ' does not exist on jQuery.markItUp');
				}
				return;
			}

			// apply the computed path to ~/
			function localize(data, inText) {
				if (inText) {
					return 	data.replace(/("|')~\//g, "jQuery1"+options.root);
				}
				return 	data.replace(/^~\//, options.root);
			}

			// init and build editor
			function init() {
				id = ''; nameSpace = '';
				if (options.id) {
					id = 'id="'+options.id+'"';
				} else if (jQueryjQuery.attr("id")) {
					id = 'id="markItUp'+(jQueryjQuery.attr("id").substr(0, 1).toUpperCase())+(jQueryjQuery.attr("id").substr(1))+'"';

				}
				if (options.nameSpace) {
					nameSpace = 'class="'+options.nameSpace+'"';
				}
				jQueryjQuery.wrap('<div '+nameSpace+'></div>');
				jQueryjQuery.wrap('<div '+id+' class="markItUp"></div>');
				jQueryjQuery.wrap('<div class="markItUpContainer"></div>');
				jQueryjQuery.addClass("markItUpEditor");

				// add the header before the textarea
				header = jQuery('<div class="markItUpHeader"></div>').insertBefore(jQueryjQuery);
				jQuery(dropMenus(options.markupSet)).appendTo(header);

				// add the footer after the textarea
				footer = jQuery('<div class="markItUpFooter"></div>').insertAfter(jQueryjQuery);

				// add the resize handle after textarea
				if (options.resizeHandle === true && browser.safari !== true) {
					resizeHandle = jQuery('<div class="markItUpResizeHandle"></div>')
						.insertAfter(jQueryjQuery)
						.on("mousedown.markItUp", function(e) {
							var h = jQueryjQuery.height(), y = e.clientY, mouseMove, mouseUp;
							mouseMove = function(e) {
								jQueryjQuery.css("height", Math.max(20, e.clientY+h-y)+"px");
								return false;
							};
							mouseUp = function(e) {
								jQuery("html").off("mousemove.markItUp", mouseMove).off("mouseup.markItUp", mouseUp);
								return false;
							};
							jQuery("html").on("mousemove.markItUp", mouseMove).on("mouseup.markItUp", mouseUp);
					});
					footer.append(resizeHandle);
				}

				// listen key events
				jQueryjQuery.on('keydown.markItUp', keyPressed).on('keyup', keyPressed);

				// bind an event to catch external calls
				jQueryjQuery.on("insertion.markItUp", function(e, settings) {
					if (settings.target !== false) {
						get();
					}
					if (textarea === jQuery.markItUp.focused) {
						markup(settings);
					}
				});

				// remember the last focus
				jQueryjQuery.on('focus.markItUp', function() {
					jQuery.markItUp.focused = this;
				});

				if (options.previewInElement) {
					refreshPreview();
				}
			}

			// recursively build header with dropMenus from markupset
			function dropMenus(markupSet) {
				var ul = jQuery('<ul></ul>'), i = 0;
				jQuery('li:hover > ul', ul).css('display', 'block');
				jQuery.each(markupSet, function() {
					var button = this, t = '', title, li, j;
					title = (button.key) ? (button.name||'')+' [Ctrl+'+button.key+']' : (button.name||'');
					key   = (button.key) ? 'accesskey="'+button.key+'"' : '';
					if (button.separator) {
						li = jQuery('<li class="markItUpSeparator">'+(button.separator||'')+'</li>').appendTo(ul);
					} else {
						i++;
						for (j = levels.length -1; j >= 0; j--) {
							t += levels[j]+"-";
						}
						li = jQuery('<li class="markItUpButton markItUpButton'+t+(i)+' '+(button.className||'')+'"><a href="" '+key+' title="'+title+'">'+(button.name||'')+'</a></li>')
						.on("contextmenu.markItUp", function() { // prevent contextmenu on mac and allow ctrl+click
							return false;
						}).on('click.markItUp', function(e) {
							e.preventDefault();
						}).on("focusin.markItUp", function(){
                            jQueryjQuery.trigger('focus');
						}).on('mouseup', function() {
							if (button.call) {
								eval(button.call)();
							}
							setTimeout(function() { markup(button) },1);
							return false;
						}).on('mouseenter.markItUp', function() {
								jQuery('> ul', this).show();
								jQuery(document).on('click', function() { // close dropmenu if click outside
										jQuery('ul ul', header).hide();
									}
								);
						}).on('mouseleave.markItUp', function() {
								jQuery('> ul', this).hide();
						}).appendTo(ul);
						if (button.dropMenu) {
							levels.push(i);
							jQuery(li).addClass('markItUpDropMenu').append(dropMenus(button.dropMenu));
						}
					}
				});
				levels.pop();
				return ul;
			}

			// markItUp! markups
			function magicMarkups(string) {
				if (string) {
					string = string.toString();
					string = string.replace(/\(\!\(([\s\S]*?)\)\!\)/g,
						function(x, a) {
							var b = a.split('|!|');
							if (altKey === true) {
								return (b[1] !== undefined) ? b[1] : b[0];
							} else {
								return (b[1] === undefined) ? "" : b[0];
							}
						}
					);
					// [![prompt]!], [![prompt:!:value]!]
					string = string.replace(/\[\!\[([\s\S]*?)\]\!\]/g,
						function(x, a) {
							var b = a.split(':!:');
							if (abort === true) {
								return false;
							}
							value = prompt(b[0], (b[1]) ? b[1] : '');
							if (value === null) {
								abort = true;
							}
							return value;
						}
					);
					return string;
				}
				return "";
			}

			// prepare action
			function prepare(action) {
				if (typeof action === 'function') {
					action = action(hash);
				}
				return magicMarkups(action);
			}

			// build block to insert
			function build(string) {
				var openWith 			= prepare(clicked.openWith);
				var placeHolder 		= prepare(clicked.placeHolder);
				var replaceWith 		= prepare(clicked.replaceWith);
				var closeWith 			= prepare(clicked.closeWith);
				var openBlockWith 		= prepare(clicked.openBlockWith);
				var closeBlockWith 		= prepare(clicked.closeBlockWith);
				var multiline 			= clicked.multiline;

				if (replaceWith !== "") {
					block = openWith + replaceWith + closeWith;
				} else if (selection === '' && placeHolder !== '') {
					block = openWith + placeHolder + closeWith;
				} else {
					string = string || selection;

					var lines = [string], blocks = [];

					if (multiline === true) {
						lines = string.split(/\r?\n/);
					}

					for (var l = 0; l < lines.length; l++) {
						line = lines[l];
						var trailingSpaces;
						if (trailingSpaces = line.match(/ *jQuery/)) {
							blocks.push(openWith + line.replace(/ *jQuery/g, '') + closeWith + trailingSpaces);
						} else {
							blocks.push(openWith + line + closeWith);
						}
					}

					block = blocks.join("\n");
				}

				block = openBlockWith + block + closeBlockWith;

				return {	block:block,
							openBlockWith:openBlockWith,
							openWith:openWith,
							replaceWith:replaceWith,
							placeHolder:placeHolder,
							closeWith:closeWith,
							closeBlockWith:closeBlockWith
					};
			}

			// define markup to insert
			function markup(button) {
				var len, j, n, i;
				hash = clicked = button;
				get();
				jQuery.extend(hash, {	line:"",
						 			root:options.root,
									textarea:textarea,
									selection:(selection||''),
									caretPosition:caretPosition,
									ctrlKey:ctrlKey,
									shiftKey:shiftKey,
									altKey:altKey
								}
							);
				// callbacks before insertion
				prepare(options.beforeInsert);
				prepare(clicked.beforeInsert);
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.beforeMultiInsert);
				}
				jQuery.extend(hash, { line:1 });

				if ((ctrlKey === true && shiftKey === true)) {
					lines = selection.split(/\r?\n/);
					for (j = 0, n = lines.length, i = 0; i < n; i++) {
						var str_line = lines[i];
						if (str_line.trim() !== '') {
							jQuery.extend(hash, { line:++j, selection:lines[i] } );
							lines[i] = build(lines[i]).block;
						} else {
							lines[i] = "";
						}
					}

					string = { block:lines.join('\n')};
					start = caretPosition;
					len = string.block.length + ((browser.opera) ? n-1 : 0);
				} else if (ctrlKey === true) {
					string = build(selection);
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
					len = len - (string.block.match(/ jQuery/) ? 1 : 0);
					len -= fixIeBug(string.block);
				} else if (shiftKey === true) {
					string = build(selection);
					start = caretPosition;
					len = string.block.length;
					len -= fixIeBug(string.block);
				} else {
					string = build(selection);
					start = caretPosition + string.block.length ;
					len = 0;
					start -= fixIeBug(string.block);
				}
				if ((selection === '' && string.replaceWith === '')) {
					caretOffset += fixOperaBug(string.block);

					start = caretPosition + string.openBlockWith.length + string.openWith.length;
					len = string.block.length - string.openBlockWith.length - string.openWith.length - string.closeWith.length - string.closeBlockWith.length;

					caretOffset = jQueryjQuery.val().substring(caretPosition,  jQueryjQuery.val().length).length;
					caretOffset -= fixOperaBug(jQueryjQuery.val().substring(0, caretPosition));
				}
				jQuery.extend(hash, { caretPosition:caretPosition, scrollPosition:scrollPosition } );

				if (string.block !== selection && abort === false) {
					insert(string.block);
					set(start, len);
				} else {
					caretOffset = -1;
				}
				get();

				jQuery.extend(hash, { line:'', selection:selection });

				// callbacks after insertion
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.afterMultiInsert);
				}
				prepare(clicked.afterInsert);
				prepare(options.afterInsert);

				// refresh preview if opened
				if (previewWindow && options.previewAutoRefresh) {
					refreshPreview();
				}

				// reinit keyevent
				shiftKey = altKey = ctrlKey = abort = false;
			}

			// Substract linefeed in Opera
			function fixOperaBug(string) {
				if (browser.opera) {
					return string.length - string.replace(/\n*/g, '').length;
				}
				return 0;
			}
			// Substract linefeed in IE
			function fixIeBug(string) {
				if (browser.msie) {
					return string.length - string.replace(/\r*/g, '').length;
				}
				return 0;
			}

			// add markup
			function insert(block) {
				if (document.selection) {
					var newSelection = document.selection.createRange();
					newSelection.text = block;
				} else {
					textarea.value =  textarea.value.substring(0, caretPosition)  + block + textarea.value.substring(caretPosition + selection.length, textarea.value.length);
				}
			}

			// set a selection
			function set(start, len) {
				if (textarea.createTextRange){
					// quick fix to make it work on Opera 9.5
					if (browser.opera && browser.version >= 9.5 && len == 0) {
						return false;
					}
					range = textarea.createTextRange();
					range.collapse(true);
					range.moveStart('character', start);
					range.moveEnd('character', len);
					range.select();
				} else if (textarea.setSelectionRange ){
					textarea.setSelectionRange(start, start + len);
				}
				textarea.scrollTop = scrollPosition;
				textarea.focus();
			}

			// get the selection
			function get() {
				textarea.focus();

				scrollPosition = textarea.scrollTop;
				if (document.selection) {
					selection = document.selection.createRange().text;
					if (browser.msie) { // ie
						var range = document.selection.createRange(), rangeCopy = range.duplicate();
						rangeCopy.moveToElementText(textarea);
						caretPosition = -1;
						while(rangeCopy.inRange(range)) {
							rangeCopy.moveStart('character');
							caretPosition ++;
						}
					} else { // opera
						caretPosition = textarea.selectionStart;
					}
				} else { // gecko & webkit
					caretPosition = textarea.selectionStart;

					selection = textarea.value.substring(caretPosition, textarea.selectionEnd);
				}
				return selection;
			}

			// open preview window
			function preview() {
				if (typeof options.previewHandler === 'function') {
					previewWindow = true;
				} else if (options.previewInElement) {
					previewWindow = jQuery(options.previewInElement);
				} else if (!previewWindow || previewWindow.closed) {
					if (options.previewInWindow) {
						previewWindow = window.open('', 'preview', options.previewInWindow);
						jQuery(window).unload(function() {
							previewWindow.close();
						});
					} else {
						iFrame = jQuery('<iframe class="markItUpPreviewFrame"></iframe>');
						if (options.previewPosition == 'after') {
							iFrame.insertAfter(footer);
						} else {
							iFrame.insertBefore(header);
						}
						previewWindow = iFrame[iFrame.length - 1].contentWindow || frame[iFrame.length - 1];
					}
				} else if (altKey === true) {
					if (iFrame) {
						iFrame.remove();
					} else {
						previewWindow.close();
					}
					previewWindow = iFrame = false;
				}
				if (!options.previewAutoRefresh) {
					refreshPreview();
				}
				if (options.previewInWindow) {
					previewWindow.trigger('focus');
				}
			}

			// refresh Preview window
			function refreshPreview() {
 				renderPreview();
			}

			function renderPreview() {
				var phtml;
				if (options.previewHandler && typeof options.previewHandler === 'function') {
					options.previewHandler( jQueryjQuery.val() );
				} else if (options.previewParser && typeof options.previewParser === 'function') {
					var data = options.previewParser( jQueryjQuery.val() );
					writeInPreview(localize(data, 1) );
				} else if (options.previewParserPath !== '') {
					jQuery.ajax({
						type: 'POST',
						dataType: 'text',
						global: false,
						url: options.previewParserPath,
						data: options.previewParserVar+'='+encodeURIComponent(jQueryjQuery.val()),
						success: function(data) {
							writeInPreview( localize(data, 1) );
						}
					});
				} else {
					if (!template) {
						jQuery.ajax({
							url: options.previewTemplatePath,
							dataType: 'text',
							global: false,
							success: function(data) {
								writeInPreview( localize(data, 1).replace(/<!-- content -->/g, jQueryjQuery.val()) );
							}
						});
					}
				}
				return false;
			}

			function writeInPreview(data) {
				if (options.previewInElement) {
					jQuery(options.previewInElement).html(data);
				} else if (previewWindow && previewWindow.document) {
					try {
						sp = previewWindow.document.documentElement.scrollTop
					} catch(e) {
						sp = 0;
					}
					previewWindow.document.open();
					previewWindow.document.write(data);
					previewWindow.document.close();
					previewWindow.document.documentElement.scrollTop = sp;
				}
			}

			// set keys pressed
			function keyPressed(e) {
				shiftKey = e.shiftKey;
				altKey = e.altKey;
				ctrlKey = (!(e.altKey && e.ctrlKey)) ? (e.ctrlKey || e.metaKey) : false;

				if (e.type === 'keydown') {
					if (ctrlKey === true) {
						li = jQuery('a[accesskey="'+((e.keyCode == 13) ? '\\n' : String.fromCharCode(e.keyCode))+'"]', header).parent('li');
						if (li.length !== 0) {
							ctrlKey = false;
							setTimeout(function() {
								li.triggerHandler('mouseup');
							},1);
							return false;
						}
					}
					if (e.keyCode === 13 || e.keyCode === 10) { // Enter key
						if (ctrlKey === true) {  // Enter + Ctrl
							ctrlKey = false;
							markup(options.onCtrlEnter);
							return options.onCtrlEnter.keepDefault;
						} else if (shiftKey === true) { // Enter + Shift
							shiftKey = false;
							markup(options.onShiftEnter);
							return options.onShiftEnter.keepDefault;
						} else { // only Enter
							markup(options.onEnter);
							return options.onEnter.keepDefault;
						}
					}
					if (e.keyCode === 9) { // Tab key
						if (shiftKey == true || ctrlKey == true || altKey == true) {
							return false;
						}
						if (caretOffset !== -1) {
							get();
							caretOffset = jQueryjQuery.val().length - caretOffset;
							set(caretOffset, 0);
							caretOffset = -1;
							return false;
						} else {
							markup(options.onTab);
							return options.onTab.keepDefault;
						}
					}
				}
			}

			function remove() {
				jQueryjQuery.off(".markItUp").removeClass('markItUpEditor');
				jQueryjQuery.parent('div').parent('div.markItUp').parent('div').replaceWith(jQueryjQuery);
				jQueryjQuery.data('markItUp', null);
			}

			init();
		});
	};

	jQuery.fn.markItUpRemove = function() {
		return this.each(function() {
				jQuery(this).markItUp('remove');
			}
		);
	};

	jQuery.markItUp = function(settings) {
		var options = { target:false };
		jQuery.extend(options, settings);
		if (options.target) {
			return jQuery(options.target).each(function() {
				jQuery(this).trigger('focus');
				jQuery(this).trigger('insertion', [options]);
			});
		} else {
			jQuery('textarea').trigger('insertion', [options]);
		}
	};
})(jQuery);


// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
var marktitup_mySettings = {
	onTab: { keepDefault:false, replaceWith:'    ' },
	markupSet:  [
		{name: gwolle_gb_localize.bold, key:'B', openWith:'(!([b]|!|<b>)!)', closeWith:'(!([/b]|!|</b>)!)' },
		{name: gwolle_gb_localize.italic, key:'I', openWith:'(!([i]|!|<i>)!)', closeWith:'(!([/i]|!|</i>)!)'  },
		{name: gwolle_gb_localize.bullet, openWith:'[li]', closeWith:'[/li]', multiline:true, openBlockWith:'[ul]\n', closeBlockWith:'\n[/ul]'},
		{name: gwolle_gb_localize.numeric, openWith:'[li]', closeWith:'[/li]', multiline:true, openBlockWith:'[ol]\n', closeBlockWith:'\n[/ol]'},
		{name: gwolle_gb_localize.picture, key:'P', replaceWith:'[img][![' + gwolle_gb_localize.source + ':!:https://]!][/img]' },
		{name: gwolle_gb_localize.link, key:'L', openWith:'[url href=[![' + gwolle_gb_localize.link + ':!:https://]!]]', closeWith:'[/url]', placeHolder: gwolle_gb_localize.linktext },
		{name: gwolle_gb_localize.clean, className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		{name: gwolle_gb_localize.emoji, className:'emoji' }
	]
}


jQuery(document).ready(function() {

	/* Initialize BBcode editor */
	jQuery('.gwolle-gb-write textarea.gwolle_gb_content').markItUp(marktitup_mySettings);
	jQuery('#gwolle_gb_editor textarea#gwolle_gb_content').markItUp(marktitup_mySettings);
	jQuery('#gwolle_gb_editor textarea#gwolle_gb_admin_reply').markItUp(marktitup_mySettings);


	/* Slide the Emoji rows (frontend) */
	jQuery( '.gwolle-gb-write li.markItUpButton.emoji a' ).on( 'click', function() {
		var form = jQuery(this).closest('form.gwolle-gb-write');
		if ( jQuery('.gwolle_gb_emoji').css('display') == 'none' ) {
			jQuery('.gwolle_gb_emoji', form).slideDown("slow");
		} else {
			jQuery('.gwolle_gb_emoji', form).slideUp("slow");
		}
	});
	/* Slide the Emoji rows (main editor) */
	jQuery( '#markItUpGwolle_gb_content li.markItUpButton.emoji a' ).on( 'click', function() {
		if ( jQuery('.gwolle_gb_emoji').css('display') == 'none' ) {
			jQuery('.gwolle_gb_emoji').slideDown("slow");
		} else {
			jQuery('.gwolle_gb_emoji').slideUp("slow");
		}
	});
	/* Slide the Emoji rows (admin_reply editor) */
	jQuery( '#markItUpGwolle_gb_admin_reply li.markItUpButton.emoji a' ).on( 'click', function() {
		if ( jQuery('.gwolle_gb_admin_reply_emoji').css('display') == 'none' ) {
			jQuery('.gwolle_gb_admin_reply_emoji').slideDown("slow");
		} else {
			jQuery('.gwolle_gb_admin_reply_emoji').slideUp("slow");
		}
	});


	/* Insert the Emoji symbol (frontend) */
	jQuery('.gwolle_gb_emoji a').on( 'click', function() {
		var form = jQuery(this).closest('form.gwolle-gb-write');
		var target = jQuery('.gwolle_gb_content', form);
		emoticon = jQuery(this).attr("title");
		jQuery.markItUp( { target:target, replaceWith:emoticon } );
	});
	/* Insert the Emoji symbol (main editor) */
	jQuery('.gwolle_gb_emoji a').on( 'click', function() {
		emoticon = jQuery(this).attr("title");
		jQuery.markItUp( { target:'#gwolle_gb_content', replaceWith:emoticon } );
	});
	/* Insert the Emoji symbol (admin_reply editor) */
	jQuery('.gwolle_gb_admin_reply_emoji a').on( 'click', function() {
		emoticon = jQuery(this).attr("title");
		jQuery.markItUp( { target:'#gwolle_gb_admin_reply', replaceWith:emoticon } );
	});
});
