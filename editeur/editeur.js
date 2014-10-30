$.fn.htmlarea = function (opts)
{
	if (opts && typeof (opts) === "string")
	{
		var args = [];
		for (var i = 1; i < arguments.length; i++) { args.push(arguments[i]); }
		var htmlarea = jHtmlArea(this[0]);
		var f = htmlarea[opts];
		if (f)
		{
			return f.apply(htmlarea, args);
		}
	}
	return this.each(function ()
	{
		jHtmlArea(this, opts); 
	});
};

var jHtmlArea = window.jHtmlArea = function (elem, options)
{
	if (elem.jquery)
	{
		return jHtmlArea(elem[0]);
	}
	if (elem.jhtmlareaObject)
	{
		return elem.jhtmlareaObject;
	}
	else
	{
		return new jHtmlArea.fn.init(elem, options);
	}
};
/*************************

	fonctions
	
***********************/
jHtmlArea.fn = jHtmlArea.prototype =
{

	jhtmlarea: "0.7.5",  
	init: function (elem, options)
	{              
		if (elem.nodeName.toLowerCase() === "textarea")
		{
			var opts = $.extend({}, jHtmlArea.defaultOptions, options);                      
			elem.jhtmlareaObject = this;                  
			var textarea = this.textarea = $(elem);
			var container = this.container = $("<div/>").addClass("wysiwyg " + textarea.attr('name')).insertAfter(textarea);                                                                                                          
			var toolbar = this.toolbar = $("<div/>").addClass("ToolBar").appendTo(container);
			priv.initToolBar.call(this, opts);         
			var iframe = this.iframe = $("<iframe/>");
			var htmlarea = this.htmlarea = $("<div/>").append(iframe);
			container.append(htmlarea).append(textarea.hide()); 
			priv.initEditor.call(this, opts);
			priv.attachEditorEvents.call(this, container);
			if (opts.loaded)
			{
				opts.loaded.call(this);
			} 
		}
	},   
	dispose: function ()
	{ 
		this.textarea.show().insertAfter(this.container);
		this.container.remove();
		this.textarea[0].jhtmlareaObject = null;
	},
	execCommand: function (a, b, c)
	{
		this.iframe[0].contentWindow.focus();
		this.editor.execCommand(a, b || false, c || null);   
		this.updateTextArea();                 
	},
	ec: function (a, b, c)
	{
		this.execCommand(a, b, c);
	},
	queryCommandValue: function (a)
	{
		this.iframe[0].contentWindow.focus();
		return this.editor.queryCommandValue(a);
	},
	qc: function (a)
	{
		return this.queryCommandValue(a);
	},
	removeTag:function(tag)
	{  
		$(tag, this.editor).each(function()
		{
			$(this).error(function ()
			{   
				$(this).remove();
			});
		});
	},
	replace_bold:function()
	{
		$('b', this.editor).replaceWith(function()
		{
			return '<strong>' + $(this).html() + '</strong>';               
		});           
	},             
	getSelectedHTML: function ()
	{
		if ($.browser.msie)
		{
			return this.getRange().htmlText;
		}
		else
		{
			var elem = this.getRange().cloneContents();
			return $("<p/>").append($(elem)).html();
		}
	},
	getSelection: function ()
	{
		if ($.browser.msie) {
		//return (this.editor.parentWindow.getSelection) ? this.editor.parentWindow.getSelection() : this.editor.selection;
		return this.editor.selection;
		}
		else
		{
			this.iframe[0].contentDocument.defaultView.getSelection();
			return this.iframe[0].contentDocument.defaultView.getSelection();
			alert(this.iframe[0].contentDocument.defaultView.getSelection())
		}
	},
	getRange: function ()
	{
		var s = this.getSelection();
		if (!s) { return null; }
		//return (s.rangeCount > 0) ? s.getRangeAt(0) : s.createRange();
		return (s.getRangeAt) ? s.getRangeAt(0) : s.createRange();
	},
	html: function (v)
	{
		if (v)
		{
			this.textarea.val(v);
			this.updateHtmlArea();
		}
		else
		{
			return this.toHtmlString();
		}
	},
	pasteHTML: function (html)
	{
		this.iframe[0].contentWindow.focus();
		var r = this.getRange();
		if ($.browser.msie)
		{
			r.pasteHTML(html);
		} else if ($.browser.mozilla)
		{
			r.deleteContents();
			r.insertNode($((html.indexOf("<") != 0) ? $("<span/>").append(html) : html)[0]);
		}
		else
		{ // Safari
			r.deleteContents();
			r.insertNode($(this.iframe[0].contentWindow.document.createElement("span")).append($((html.indexOf("<") != 0) ? "<span>" + html + "</span>" : html))[0]);
		}
		r.collapse(false);
		r.select();
	},
	cut: function ()
	{
		this.ec("cut");
	},
	copy: function ()
	{
		this.ec("copy");
	},
	paste: function ()
	{
		this.ec("paste");
	},
	bold: function ()
	{ 
		this.ec("bold");
		this.replace_bold(); 
	},
	italic: function ()
	{
		this.ec("italic");
	},
	underline: function ()
	{
		this.ec("underline");
	},
	strikeThrough: function ()
	{
		this.ec("strikethrough");
	},
	image: function() 
	{
		var num = prompt("Numero de l'image");
		if(num != null)
		{
			var extension = '';
			$('#galerie_bo .image').each(function()
			{
				var numero = $('p', this).text().split('[')[1].split(']')[0];
				if(numero == num)
				{
					var extension = $('img', this).attr('src').split('/medias/')[1].split('.')[1];
					var titre = $('p', this).attr('id').split('#_#')[0];
					var alt = $('p', this).attr('id').split('#_#')[1];
					img = '<img alt="' + alt + '" src="../img/medias/' + titre + '.' + extension + '" />';
				}
				
			});
			
			if ($.browser.msie && !num) 
			{
				this.ec("insertHtml", true);
			} 
			else 
			{
				this.ec("insertHtml", false, img);
			}
		}                
	},
	video: function() 
	{
		var num = prompt("Numero de la video");
		if(num != null)
		{
			$('#galerie_bo .video').each(function()
			{
				var video = $('iframe', this).attr('src').split('youtube.com/embed/')[1].split('?')[0];
				var numero = $(this).attr('id').split('_')[1];
				if(numero == num)
				{
					iframe_video = '<iframe src="http://www.youtube.com/embed/' + video + '?wmode=transparent" frameborder="0" ></iframe>';
				}
			});
			
			if ($.browser.msie && !iframe_video) 
			{
				this.ec("insertHtml", true);
			} 
			else 
			{
				this.ec("insertHtml", false, iframe_video);
			}
		}
	},         
	player: function() 
	{
		var num = prompt("Numero du morceau de musique");
		if(num != null)
		{
			$('#galerie_bo audio').each(function()
			{
				var numero = $(this).parent().parent().parent().find('p').text().split('[')[1].split(']')[0];
				if(numero == num)
				{
					var extension = $(this).attr('src').split('/medias/')[1].split('.')[1];
					var titre = $(this).parent().parent().next('p').attr('id').split('#_#')[0];
					var alt = $(this).parent().parent().next('p').attr('id').split('#_#')[1];
					
					audio = '<img src="../img/icones/music.png" class="no_front" /><div class="audiojs" ><audio alt="' + alt + '" src="../img/medias/' + titre + '.' + extension + '" preload="auto" /></div>';
				}
			});
			if ($.browser.msie && !audio) 
			{
				this.ec("insertHtml", true);
			} 
			else 
			{
				this.ec("insertHtml", false, audio);
			}  
		}
	},         
	removeFormat: function () 
	{
		this.ec("removeFormat", false, []);
		this.unlink();
	},
	link: function () 
	{
		if ($.browser.msie) 
		{
			this.ec("createLink", true);
		} 
		else 
		{
			this.ec("createLink", false, prompt("URL du lien:", "http://"));
		}
	},
	unlink: function ()
	{
		this.ec("unlink", false, []);
	},
	orderedList: function ()
	{
		this.ec("insertorderedlist");
	},
	unorderedList: function ()
	{
		this.ec("insertunorderedlist");
	},
	superscript: function ()
	{
		this.ec("superscript");
	},
	subscript: function ()
	{
		this.ec("subscript");
	},
	p: function ()
	{
		this.formatBlock("<p>");
	},
	div: function ()
	{
		this.formatBlock("<div>");
	},
	h1: function ()
	{
		this.heading(1);
	},
	h2: function ()
	{
		this.heading(2);
	},
	h3: function ()
	{
		this.heading(3);
	},
	h4: function ()
	{
		this.heading(4);
	},
	h5: function ()
	{
		this.heading(5);
	},
	h6: function ()
	{
		this.heading(6);
	},
	heading: function (h)
	{
		this.formatBlock($.browser.msie ? "Heading " + h : "h" + h);
	},

	indent: function ()
	{
		this.ec("indent");
	},
	outdent: function ()
	{
		this.ec("outdent");
	},
	insertHorizontalRule: function ()
	{
		this.ec("insertHorizontalRule", false, "ht");
	},
	justifyLeft: function ()
	{
		this.ec("justifyLeft");
	},
	justifyCenter: function ()
	{
		this.ec("justifyCenter");
	},
	justifyRight: function ()
	{
		this.ec("justifyRight");
	},
	increaseFontSize: function ()
	{
		if ($.browser.msie)
		{
			this.ec("fontSize", false, this.qc("fontSize") + 1);
		}
		else if ($.browser.safari)
		{
			this.getRange().surroundContents($(this.iframe[0].contentWindow.document.createElement("span")).css("font-size", "larger")[0]);
		}
		else
		{
			this.ec("increaseFontSize", false, "big");
		}
	},
	decreaseFontSize: function ()
	{
		if ($.browser.msie)
		{
			this.ec("fontSize", false, this.qc("fontSize") - 1);
		}
		else if ($.browser.safari)
		{
			this.getRange().surroundContents($(this.iframe[0].contentWindow.document.createElement("span")).css("font-size", "smaller")[0]);
		}
		else
		{
			this.ec("decreaseFontSize", false, "small");
		}
	},
	forecolor: function (c)
	{
		this.ec("foreColor", false, c || prompt("Enter HTML Color:", "#"));
	},
	formatBlock: function (v)
	{
		this.ec("formatblock", false, v || null);
	},
	showHTMLView: function ()
	{
		this.updateTextArea();
		this.textarea.show();
		this.htmlarea.hide();
		$("ul li:not(li:has(a.html))", this.toolbar).hide();
		$("ul:not(:has(:visible))", this.toolbar).hide();
		$("ul li a.html", this.toolbar).addClass("highlighted");
	},
	hideHTMLView: function ()
	{
		this.updateHtmlArea();
		this.textarea.hide();
		this.htmlarea.show();
		$("ul", this.toolbar).show();
		$("ul li", this.toolbar).show().find("a.html").removeClass("highlighted");
	},
	toggleHTMLView: function ()
	{
		(this.textarea.is(":hidden")) ? this.showHTMLView() : this.hideHTMLView();
	},
	toHtmlString: function ()
	{
		return this.editor.body.innerHTML;
	},
	toString: function ()
	{
		return this.editor.body.innerText;
	},
	updateTextArea: function ()
	{
		this.textarea.val(this.toHtmlString());             
	},
	updateHtmlArea: function ()
	{
		this.editor.body.innerHTML = this.textarea.val();
	}
};

jHtmlArea.fn.init.prototype = jHtmlArea.fn;

jHtmlArea.defaultOptions =
{
	toolbar: [
	["html"], ["bold", "italic", "underline", "strikethrough", "|", "subscript", "superscript"],
	["increasefontsize", "decreasefontsize"],
	["orderedlist", "unorderedlist"],
	["indent", "outdent"],
	["justifyleft", "justifycenter", "justifyright"],
	["link", "unlink", "image", "video", "player", "horizontalrule"],
	["p", "h1", "h2", "h3", "h4", "h5", "h6"],
	["cut", "copy", "paste"]
	],
	css: null,
	toolbarText:
	{
		bold: "Gras", italic: "Italique", underline: "Souligné", strikethrough: "Barré",
		cut: "Couper", copy: "Copier", paste: "Coller",
		h1: "Titre de niveau 1", h2: "Titre de niveau 2", h3: "Titre de niveau 3", h4: "Titre de niveau 4", h5: "Titre de niveau 5", h6: "Titre de niveau 6", p: "Paragraphe",
		indent: "Indentation à droite", outdent: "Indentation à gauche", horizontalrule: "Ligne horizontale",
		justifyleft: "Alignement à gauche", justifycenter: "Alignement au centre", justifyright: "Alignement à droite",
		increasefontsize: "Augmenter la taille de la police", decreasefontsize: "Diminuer la taille de la police", forecolor: "Couleur du texte",
		link: "Lien", unlink: "Retirer un lien", image: "Image",
		orderedlist: "Liste numérotée", unorderedlist: "Liste à puce",
		subscript: "Indice", superscript: "Exposant",
		html: "Code HTML", video:"Vidéo", player:"Player", size_image:"Taille de l'image"
	},
	url_image: 'http://'
};
var priv =
{
	toolbarButtons:
	{
		strikethrough: "strikeThrough", orderedlist: "orderedList", unorderedlist: "unorderedList",
		horizontalrule: "insertHorizontalRule",
		justifyleft: "justifyLeft", justifycenter: "justifyCenter", justifyright: "justifyRight",
		increasefontsize: "increaseFontSize", decreasefontsize: "decreaseFontSize",
		html: function (btn)
		{
			this.toggleHTMLView();
		}
	},
	initEditor: function (options)
	{
		var br='';
		var edit = this.editor = this.iframe[0].contentWindow.document;
		edit.designMode = 'on';
		edit.open();
		if($.browser.mozilla)
		{
			br='<br>';
		}
		edit.write(this.textarea.val() + br);

		edit.close();
		if (options.css)
		{
			var explode=options.css.split('|');
			for(var i=0; i < explode.length; i++)
			{
				var e = edit.createElement('link'); e.rel = 'stylesheet'; e.type = 'text/css'; e.href = explode[i]; edit.getElementsByTagName('head')[0].appendChild(e);
			}
		}
	},
	initToolBar: function (options)
	{
		var that = this;

		var menuItem = function (className, altText, action)
		{
			return $("<li/>").append($("<a href='javascript:void(0);'/>").addClass(className).attr("title", altText).click(function () { action.call(that, $(this)); }));
		};

		function addButtons(arr)
		{
			var ul = $("<ul/>").appendTo(that.toolbar);
			for (var i = 0; i < arr.length; i++)
			{
				var e = arr[i];
				if ((typeof (e)).toLowerCase() === "string")
				{
					if (e === "|")
					{
						ul.append($('<li class="separator"/>'));
					}
					else
					{
						var f = (function (e)
						{
							// If button name exists in priv.toolbarButtons then call the "method" defined there, otherwise call the method with the same name
							var m = priv.toolbarButtons[e] || e;
							if ((typeof (m)).toLowerCase() === "function") {
							return function (btn) { m.call(this, btn); };
							}
							else
							{
								return function () { this[m](); this.editor.body.focus(); };
							}
						})(e.toLowerCase());
						var t = options.toolbarText[e.toLowerCase()];
						ul.append(menuItem(e.toLowerCase(), t || e, f));
					}
				}
				else
				{
					ul.append(menuItem(e.css, e.text, e.action));
				}
			}
			$('<div style="clear:both"></div>').appendTo(that.toolbar);
		};
		if (options.toolbar.length !== 0 && priv.isArray(options.toolbar[0]))
		{
			for (var i = 0; i < options.toolbar.length; i++)
			{
				addButtons(options.toolbar[i]);
			}
		}
		else
		{
			addButtons(options.toolbar);
		}
	},
	attachEditorEvents: function (bloc_wysiwyg)
	{
		var t = this; 
		var fnHA = function ()
		{
			t.updateHtmlArea();
		};

		this.textarea.click(fnHA).
		keyup(fnHA).
		keydown(fnHA).
		mousedown(fnHA).
		blur(fnHA);   

		var fnTA = function ()
		{                
			t.updateTextArea();                             
		}; 

		var focus_editor=function()
		{
			this.getRange();
		};                         

		$(this.editor.body).click(fnTA).
		keyup(fnTA).
		keydown(fnTA).
		mousedown(fnTA).
		blur(fnTA);                    

		$('img', this.editor.body).live('mouseenter', function()
		{
			if($(this).parent().attr('id') != 'bloc_img' && $(this).attr('class') != 'no_front')
			{
				var style = $(this).attr('style');     
				$(this).attr('style', 'width:100%');                          
				$(this).replaceWith('<span style="' + style + '" id="bloc_img"><span id="opts_image">Options</span>' + $(this)[0].outerHTML + '<span id="delete"></span></span>');
				t.editor.designMode = 'off';
			}
		});
		$('#bloc_img', this.editor.body).live('mouseleave', function()
		{
			$('img', this).attr('style', $(this).attr('style'));              
			$(this).replaceWith($('img', this)[0].outerHTML);
			if($('#fond', t.editor.body).length == 0)
			{
				t.editor.designMode = 'on';
			}
		});

		$('#delete', this.editor.body).live('click', function()
		{
			$(this).parent().remove();
			t.editor.designMode = 'on';
		}); 

		$('input[type="submit"]').click(function()
		{
			$('#fond', t.editor.body).remove();
/*****************
on s'assure que le morceau de musique est bien supprimé lorsqu'on supprime l'icône correspondante...
*****************/
			$("*:not(:has(>.no_front)):has(>.audiojs) > .audiojs", t.editor.body).remove();
		}); 


		$('#opts_image', this.editor.body).live('click', function()
		{
			t.editor.designMode = 'off';
			$(this).parent().find('img').addClass('image_selected');                                                     
			var dialogue='<div id="fond">';
			dialogue+='<div id="bloc">';
			dialogue+='<h3>Options de l\'image</h3>';
			dialogue+='<form>';
			dialogue+='<div class="left">';
			dialogue+='<label>Largeur en %</label>';
			dialogue+='<input type="text" id="width" value="' + this.parentNode.style.width.replace('%', '') + '" />';
			dialogue+='</div>';
			dialogue+='<div class="right">';
			dialogue+='<label>Position</label>';
			dialogue+='<select id="position">';
			dialogue+='<option value="">...</option>';
			dialogue+='<option value="left">Left</option>';
			dialogue+='<option value="right">Right</option>';
			dialogue+='</select>';
			dialogue+='</div>';
			dialogue+='<label>Marges % ( Top | Right | Bottom | Left )</label>';
			dialogue+='<input type="text" id="m_top" value="' + this.parentNode.style.marginTop.replace('%', '') + '" />';
			dialogue+='<input type="text" id="m_right" value="' + this.parentNode.style.marginRight.replace('%', '') + '" />';
			dialogue+='<input type="text" id="m_bottom" value="' + this.parentNode.style.marginBottom.replace('%', '') + '" />';
			dialogue+='<input type="text" id="m_left" value="' + this.parentNode.style.marginLeft.replace('%', '') + '" />';
			dialogue+='<div style="clear:both;"></div>';               
			dialogue+='</form>';
			dialogue+='<input type="button" value="Ok" />';
			dialogue+='</div></div>'; 
			$(t.editor.body).append(dialogue); 
			$('.ToolBar', bloc_wysiwyg).css({'position':'relative'}).append('<div id="cache_tool"></div>');                 
			$('#position option[value=' + this.parentNode.style.cssFloat + ']', t.editor.body).attr('selected', 'selected');
			$('#width', t.editor.body).focus();                         
		});

		$('#width', this.editor.body).live('keypress', function(e) 
		{
			if(e.keyCode==13 || e.which==13)
			{
				return false;
			}
		});

		$('#fond input[type="button"]', this.editor.body).live('click', function()
		{
			$('.image_selected', t.editor.body).css('width',parseInt($('#width', t.editor.body).val()) + '%');
			if($('#position', t.editor.body).val() != '')
			{
				$('.image_selected', t.editor.body).css('float',$('#position', t.editor.body).val());
			}
			else
			{
				$('.image_selected', t.editor.body).css('float', '');
			}
			var tab_marge=['top', 'right', 'bottom', 'left'];
			for(var i=0; i < tab_marge.length; i++)
			{
				if($('#m_' + tab_marge[i], t.editor.body).val() != '')
				{
					$('.image_selected', t.editor.body).css('margin-' + tab_marge[i], parseInt($('#m_' + tab_marge[i], t.editor.body).val()) + '%');
				}
				else
				{
					$('.image_selected', t.editor.body).css('margin-' + tab_marge[i], '');
				}
			} 
			$('.image_selected', t.editor.body).removeAttr('class');
			$('#fond', t.editor.body).remove();
			$('#cache_tool', bloc_wysiwyg).remove();
			t.editor.designMode = 'on';
		});

		$('form').submit(function ()
		{
			t.toggleHTMLView(); t.toggleHTMLView();
		});     
		// Fix for ASP.NET Postback Model
		if (window.__doPostBack)
		{
			var old__doPostBack = __doPostBack;
			window.__doPostBack = function ()
			{
				if (t)
				{
					if (t.toggleHTMLView)
					{
						t.toggleHTMLView();
						t.toggleHTMLView();
					}
				}
				return old__doPostBack.apply(window, arguments);
			};
		}    
	},
	isArray: function (v) {
	return v && typeof v === 'object' && typeof v.length === 'number' && typeof v.splice === 'function' && !(v.propertyIsEnumerable('length'));
	}
};


