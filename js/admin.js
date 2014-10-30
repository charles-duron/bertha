/***************************************************************************






DEBUT DU DOCUMENT READY






***************************************************************************/
$(document).ready(function()
{
/*****************

déroulement des messages contacts

*****************/
	$('#form_contact textarea').click(function()
	{
		$(this).removeAttr('id');
	});

	$('.deroul').live('click', function()
	{
		if($(this).next().find('div').is(':visible'))
		{   
			$(this).next().css({'background-color':'rgb(200,200,200)','transition':'background-color .3s'});
			$(this).next().find('div').slideToggle();
		}
		else
		{   
			$(this).next().css({'background-color':'rgb(240,240,240)','transition':'background-color .3s'});
			$(this).next().find('div').slideToggle();      
		}
	});
/*****************

déroulement des catégories de media dans le panneau central

*****************/
	$('.type_media a.ouverture').each(function()
	{
		if($.cookie($(this).text()))
		{
			$(this).parent().find('div').css({'display':'block'}).addClass('open');
		}
	});
	
	$('.type_media a.ouverture').live('click', function()
	{
		if($(this).parent().children('div').hasClass('open'))
		{
			$(this).parent().children('div').removeClass('open');
			$.cookie($(this).text(), null, { path: '/' });
		}
		else
		{
			$(this).parent().children('div').addClass('open');
			createCookie($(this).text(), 'open', 7);
		}

		$(this).parent().find('div').slideToggle('fast');
		$('.error-message').each(function()
		{
			if($(this).html() == '')
			{
				$(this).hide();
			}
		});
	});
/*****************

hauteurs et positionnements...

*****************/
	$('#droite').css('min-height', ($('#gauche ul').height() * 1.3) + 'px');		//gestion de la hauteur de la partie droite en fonction de la hauteur du menu
	$('#btn_centre, #btn_centre2').height($('#btn_centre').width());	//gestion de la hauteur des boutons d'ouverture menu en fonction de la largeur du premier
	$('#btn_centre2').css('top', (parseInt($('#btn_centre').css('top')) + $('#btn_centre').height()) + 'px');	//gestion de la position du second bouton en fonction de celle du premier

	$(window).resize(function()
	{
		$('#btn_centre, #btn_centre2').height($('#btn_centre').width());
		$('#btn_centre2').css('top', (parseInt($('#btn_centre').css('top')) + $('#btn_centre').height()) + 'px');
	});  
/*****************

gestion du panneau central

*****************/
	if($.cookie('centre') == 'open')	//panneau ouvert
	{
		$('#gauche').removeClass('shadow_gauche');

		if($.cookie('taille') == 'small')
		{
			$('#centre').css({'left':'20%', 'width':'25%', 'padding-right':'0'});
			$('#droite').css({'left':'45%', 'width':'55%'});      
			$('#btn_centre, #btn_centre2').css({'left':'43%'});
			$('#btn_centre').css({'-o-transform': 'rotate(180deg)',
			'-ms-transform': 'rotate(180deg)', 
			'-webkit-transform': 'rotate(180deg)', 
			'-moz-transform':'rotate(180deg)', 
			'-webkit-transform':'rotate(180deg)'
			}).removeClass('cache').addClass('ouvert');
		}
		else
		{
			$('#centre').css({'left':'20%', 'width':'77%', 'padding-right':'0'}); 
			$('#btn_centre2').hide();     
			$('#btn_centre').css({'left':'95%',
			'-o-transform': 'rotate(180deg)',
			'-ms-transform': 'rotate(180deg)', 
			'-webkit-transform': 'rotate(180deg)', 
			'-moz-transform':'rotate(180deg)', 
			'-webkit-transform':'rotate(180deg)'
			}).removeClass('cache').addClass('ouvert');
		}

		$('#contenu_centre').show();   
	}

	$('#btn_centre, #btn_centre2').click(function()	//au clic sur les boutons
	{
		$.cookie('centre', null);	//on reset le cookie indiquant l'état du panneau

		if($(this).hasClass('cache'))	//si le panneau est fermé
		{
			$('#gauche').removeClass('shadow_gauche');
			if(this.id == "btn_centre")	//bouton 1 : on ouvre un peu
			{
				$('#centre').animate({'left':'20%', 'width':'25%', 'padding-right':'0'}, 500);
				$('#droite').animate({'left':'45%', 'width':'55%'}, 500);
				$('#btn_centre, #btn_centre2').animate({'left':'43%'}, 500);        
				$(this).css({'-o-transform': 'rotate(180deg)',
				'-ms-transform': 'rotate(180deg)', 
				'-webkit-transform': 'rotate(180deg)', 
				'-moz-transform':'rotate(180deg)', 
				'-webkit-transform':'rotate(180deg)'
				}).removeClass('cache').addClass('ouvert');
				$.cookie('taille', 'small', 7);
			}
			else	//bouton 2 : on ouvre en grand
			{
				$('#centre').animate({'left':'20%', 'width':'77%', 'padding-right':'0'}, 500);        
				$('#btn_centre').animate({'left':'95%'}, 500);   
				$('#btn_centre2').hide();     
				$('#btn_centre').css({'-o-transform': 'rotate(180deg)',
				'-ms-transform': 'rotate(180deg)', 
				'-webkit-transform': 'rotate(180deg)', 
				'-moz-transform':'rotate(180deg)', 
				'-webkit-transform':'rotate(180deg)'
				}).removeClass('cache').addClass('ouvert');
				$.cookie('taille', 'big', 7);
			}
			createCookie('centre','open',7);
			$('#contenu_centre').show();
		}
		else	//si le panneau est ouvert
		{
			$('#gauche').addClass('shadow_gauche');            
			$('#centre').animate({'left':'0', 'width':'20%', 'padding-right':'2%'}, 500);
			$('#droite').animate({'left':'22%', 'width':'78%'}, 500);
			$('#btn_centre, #btn_centre2').animate({'left':'20%'}, 500); 
			$('#btn_centre2').fadeIn(500);    
			$('#btn_centre, #btn_centre2').css({'-o-transform': 'rotate(0deg)',
			'-ms-transform': 'rotate(0deg)', 
			'-webkit-transform': 'rotate(0deg)', 
			'-moz-transform':'rotate(0deg)', 
			'-webkit-transform':'rotate(0deg)'
			}).removeClass('ouvert').addClass('cache');              
			createCookie('centre','close',7);
			$('#contenu_centre').hide();
		}    
	});
/*****************

gestion des formulaires

*****************/
	if($('#form_comptes').length > 0)
	{         
		$('input[type="file"]').customFileInput(); 
	}
	
	if($('#form_medias').length > 0)
	{         
		$('input[type="file"]').customFileInput(); 
	}
	
	if($('#form_parametre').length > 0)
	{         
		$('#file1').customFileInput(); 
		$('#file2').customFileInput();      
	}
	
	if($('#form_css').length > 0)
	{
		$('input[type="file"]').customFileInput({buttonText:'Fichier à uploader (.css)'});
	}
	
	if($('#form_rubrique').length > 0 )
	{
		tri_rubriques('.up, .down');
	} 

	if($('#form_pages').length >0 || $('#form_actu').length >0 || $('#form_evenement').length >0)
	{
		$('textarea').htmlarea({
		toolbar: ["html", "|", 
		"copy", "cut", "paste", "|",
		"bold", "italic", "underline", "strikethrough", "increaseFontSize", "decreaseFontSize", "|", 
		"justifyLeft", "justifyCenter", "justifyRight", "indent", "outdent", "|",
		"orderedlist", "unorderedlist", "|", 
		"p", "h2", "h3", "h4", "|", 
		"image", "video", "player", "link", "unlink", "insertHorizontalRule", ], 
		css: "../editeur/font-wysiwyg.css|http://fonts.googleapis.com/css?family=Ubuntu+Condensed"});     
	}

	if($('#form_pages').length > 0)
	{
		tri_pages('.up, .down');
		choix_langue('#droite form select[name="id_langue"]');
		$('.keyword input').height(20).css({'width':'82%', 'float':'left'});

		if($('input[name="keyword"]:checked').val() == "oui")
		{
			$('input[name="ajout_mot"]').attr('disabled', false);
			$('.keyword a img').attr('src', '../img/icones/ajouter.png');
		}       

		$('input[name="keyword"]').change(function()
		{
			if($('input[name="keyword"]:checked').val() == "oui")
			{
				$('input[name="ajout_mot"]').attr('disabled', false);
				$('.keyword a img').attr('src', '../img/icones/ajouter.png');
			} 
			else
			{
				$('input[name="ajout_mot"]').attr('disabled', true).val('').placeholder().show(true);
				$('input[name="liste_mots"]').val('');
				$('.liste_mots').html('');
				$('.keyword a img').attr('src', '../img/icones/ajouter_disabled.png');
			}
		});

		if($('.mot').length > 0)
		{
			margin_mot();
		}      

		$('.keyword a').click(function()
		{
			ajout_mot();          
		});

		$('input[name="ajout_mot"]').keypress(function(event)
		{
			if(event.keyCode == 13 || event.which == 13)
			{
				ajout_mot();
				return false;
			}
		});

		$('.mot').live('click', function()
		{
			$(this).remove();
			$('input[name="liste_mots"]').val('');
			if($('.mot').length > 0)
			{
				margin_mot();
				$('.mot').each(function(j)
				{
					var separe = '';
					if(j != 0)
					{
						separe = ',';
					}
					$('input[name="liste_mots"]').val($('input[name="liste_mots"]').val() + separe + $(this).text());
				});
			}
		});

		for(t = 0; t < 5; t++)
		{
			$('#liens_gabarit ul').append('<li><a href="javascript:void(0)"></a></li>');
		}

		var gabarit = '';

		if($.cookie('gabarit') != null && $('#ligne_coloree').length == 0)
		{
			gabarit=$.cookie('gabarit');
		}
		else
		{
			gabarit=$('input[name="gabarit"]').val();
			$.cookie('gabarit', $('input[name="gabarit"]').val(), 7);
		}

		form_page(gabarit);

		$('#liens_gabarit ul li a').click(function()
		{
			if($.cookie('gabarit') != parseInt($(this).parent().index()) + 1)
			{
				form_page(parseInt($(this).parent().index()) + 1);
				$.cookie('gabarit', parseInt($(this).parent().index()) + 1, 7);
				$('input[name="gabarit"]').val(parseInt($(this).parent().index()) + 1)
			}
		});       
	}

	if($('#form_edit_css').length > 0)
	{
		var editor_css = CodeMirror.fromTextArea(document.getElementById("code-css"), 
		{
			lineNumbers: true,
			mode: "css",
			gutters: ["CodeMirror-lint-markers"],
			lint: true
		});
	}

});
/***************************************************************************






FIN DU DOCUMENT READY






***************************************************************************/
/*****************

creation de cookies

*****************/
function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
};
/*****************

tri des rubriques gràce aux flèches

*****************/
function tri_rubriques(fleches)
{
	$(fleches).click(function()
	{
		var tr = $(this).parent().parent();
		var td = $(this).parent();
		var id_rubrique = td.attr('id').split('-');
		var id_langue = td.attr('class').split('-');


		if($(this).hasClass('down'))
		{
			$('body').append('<div id="loader"><div></div></div>');

			$.ajax(
			{
				type: 'POST', 
				url:'../admin/ajax.php', 
				data:'cas=rubriques&rang=1&id_rubrique=' + id_rubrique[1] + '&sens=down&id_langue=' + id_langue[1], 
				success: function()
				{
					if(!tr.next().attr('class'))
					{                   
						tr.insertAfter(tr.next());
					}
					$('#loader').remove();
				}
			});	
		}
		else
		{
			$('body').append('<div id="loader"><div></div></div>');

			$.ajax(
			{
				type: 'POST', 
				url:'../admin/ajax.php', 
				data:'cas=rubriques&rang=1&id_rubrique=' + id_rubrique[1] + '&sens=up&id_langue=' + id_langue[1], 
				success: function()
				{
					if(!tr.prev().attr('class'))
					{  
						tr.insertBefore(tr.prev());
					}
					$('#loader').remove();
				}
			});	
		}
	});
};
/*****************

tri des pages gràce aux flèches

*****************/
	function tri_pages(fleches)
	{
		$(fleches).click(function()
		{
			var tr = $(this).parent().parent();
			var td = $(this).parent();
			var id_page = td.attr('id').split('-');
			var id_rubrique = td.attr('class').split('-');

			if($(this).hasClass('down'))
			{
				$('body').append('<div id="loader"><div></div></div>');

				$.ajax(
				{
					type: 'POST', 
					url:'../admin/ajax.php', 
					data:'cas=pages&rang=1&id_page=' + id_page[1] + '&sens=down&id_rubrique=' + id_rubrique[1], 
					success: function()
					{
						if(!tr.next().attr('class'))
						{                   
							tr.insertAfter(tr.next());
						}
						$('#loader').remove();
					}
				});	
			}
			else
			{
			$('body').append('<div id="loader"><div></div></div>');

			$.ajax(
			{
				type: 'POST', 
				url:'../admin/ajax.php', 
				data:'cas=pages&rang=1&id_page=' + id_page[1] + '&sens=up&id_rubrique=' + id_rubrique[1], 
				success: function()
				{
				if(!tr.prev().attr('class'))
				{  
					tr.insertBefore(tr.prev());
				}
				$('#loader').remove();
			}
			});	

			}
		});
	};
/*****************

affichage des rubriques au choix dans page en fonction de la langue selectionnée

*****************/
function choix_langue(select)
{
	if($(select).val() != '')
	{
		requete_rubriques($(select).val());
	}

	$(select).change(function()
	{
		$('body').append('<div id="loader"><div></div></div>');
		requete_rubriques($(this).val());    
	});
};


function requete_rubriques(id_langue)
{
	$.ajax(
	{
		type: 'POST', 
		url:'../admin/ajax.php', 
		data:'id_langue=' + id_langue, 
		success: function(reponse)
		{
		$('#droite form select[name="id_rubrique"]').html(reponse);  
		
		if($('#droite form select[name="id_langue"]').val() == '')
		{
			$('#droite form select[name="id_rubrique"]').html('<option value="">Sélectionner la rubrique</option>');
		}
		$('#loader').remove();
		}
	});	
};
/*****************

gestion du formulaire decréation/modification de page

*****************/
	function form_page(nb)
	{
		var tab_position = new Array('6px center', '-34px center', '-74px center', '-114px center', '-154px center', '-174px center');
		var tab_move = new Array('-14px center', '-54px center', '-94px center', '-134px center', '-174px center', '-194px center');     

		zone1=$('textarea[name="zone1"]').val();     
		zone2=$('textarea[name="zone2"]').val();    
		zone3=$('textarea[name="zone3"]').val();     

		var i=1;
		$('#liens_gabarit ul li a').each(function(j)
		{
			$(this).css({
			'background-position': tab_position[j],
			'background-color':'transparent',
			'border':'1px solid #d4d4d4'
			});        
			if(nb == i)
			{
				$(this).css(
				{
					'background-position': tab_move[j],
					'background-color':'white',
					'border':'1px solid #aaaaaa'
				});      
			}      
			i++;
		});  

		$('.wysiwyg').css({'float': 'none', 'clear':'none', 'margin-top':'0', 'margin-bottom':'0', 'display':'block', 'border':'none'});
		$('.wysiwyg').find('textarea').css({'font-size':'15px', 'background-image': ''});
		$('.wysiwyg').find('iframe, .ToolBar').css('background-image', '');    

		if(nb == 1)
		{
			var br = '';
			var br1 = '';
			if(zone2 != '') { br = '<br />'; }
			if(zone3 != '') { br1 = '<br />'; }

			$('.zone1').css({'width': '100%'}).find('iframe').css('height', '400px'); 
			$('.zone1').find('textarea').css('height', '390px'); 
			$('.zone1').find('iframe').contents().find('body').html(zone1 + br +  zone2 + br1 + zone3); 
			$('.zone1').find('textarea').val(zone1 + br +  zone2 + br1 + zone3);

			$('.zone2, .zone3').hide();     
			$('.zone2, .zone3').find('iframe').contents().find('body').empty();       
			$('.zone2, .zone3').find('textarea').val('');     
		}
		else
		{
			$('.zone1').find('iframe').contents().find('body').html(zone1); 
			$('.zone1').find('textarea').val(zone1);
			$('.zone2').find('iframe').contents().find('body').html(zone2); 
			$('.zone2').find('textarea').val(zone2); 
			$('.zone3').find('iframe').contents().find('body').html(zone3);    
			$('.zone3').find('textarea').val(zone3);     
		}     
		if(nb == 2)
		{
			$('.zone1').css({'width': '100%', 'border-bottom':'1px solid #aaaaaa'});
			$('.zone2, .zone3').css({'width': '50%', 'float':'left'});
			$('.zone3').find('iframe, textarea, .ToolBar').css('background-image', 'url(../img/icones/border_wysiwyg.jpg)');                          
		}   
		if(nb == 3)
		{
			$('.zone1, .zone2').css({'width': '50%', 'float':'left'});
			$('.zone2').find('iframe, textarea, .ToolBar').css('background-image', 'url(../img/icones/border_wysiwyg.jpg)');  
			$('.zone3').css({'width': '100%', 'clear':'both', 'border-top':'1px solid #aaaaaa'});                    
		}    
		if(nb == 2 || nb == 3)
		{
			$('.wysiwyg').find('iframe').css('height', '300px');
			$('.wysiwyg').find('textarea').css('height','290px'); 
		}      
		if(nb == 4)
		{
			$('.zone1').css({'width': '50%', 'float':'left'}).find('iframe').css({'height': '664px'});        
			$('.zone1').find('textarea').css({'height': '654px'});                                                                                 
			$('.zone2').css({'border-bottom':'1px solid #aaaaaa'}) 
			$('.zone2, .zone3').css({'width': '50%', 'float':'left'}).find('iframe').css('height', '318px');
			$('.zone2, .zone3').find('textarea').css('height', '308px');            
			$('.zone2, .zone3').find('.ToolBar, textarea, iframe').css({'background-image': 'url(../img/icones/border_wysiwyg.jpg)'});   
			var zone = $('.zone1');
		}    
		if(nb == 5)
		{     
			$('.zone1').css('border-bottom','1px solid #aaaaaa');
			$('.zone2').css({'width': '50%', 'float':'right'}).find('iframe').css({'height': '664px'});
			$('.zone1, .zone3').css({'width': '50%', 'float':'left'}).find('iframe').css({'height': '318px'});          
			$('.zone1, .zone3').find('textarea').css({'height': '308px'});
			$('.zone2').find('.ToolBar, iframe, textarea').css({'background-image': 'url(../img/icones/border_wysiwyg.jpg)'});
			var zone = $('.zone2'); 
		} 

		if(zone)
		{
			var add=($('.zone2').height() + $('.zone3').height());         
			if( add >  $(zone).height() )
			{
			var difference=(add - zone.height()) + 1;
			zone.find('iframe').height($('.zone1').find('iframe').height() + difference);
			zone.find('textarea').height($('.zone1').find('textarea').height() + difference);
			} 
		}

		$('#liens_gabarit ul li a').hover(function()
		{
			$(this).css(
			{
				'background-position': tab_move[$(this).parent().index()],
				'background-color':'white',
				'border':'1px solid #aaaaaa'
			});
		},
		function()
		{
			if($(this).parent().index() != parseInt($.cookie('gabarit')) - 1)
			{
				$(this).css(
				{
					'background-position': tab_position[$(this).parent().index()],
					'background-color':'transparent',
					'border':'1px solid #d4d4d4'
				});
			}
		});       
	}

/*-------------------------------------------------------------------------------------------------------------*/

function margin_mot()
{
$('.mot').css('margin-right', '7px');
$('.mot').last().css('margin-right', '0');  
}

/*------------------------------------------------------------------------------------------------------------*/

function ajout_mot()
{
if($('input[name="ajout_mot"]').val() != '')
{
var pipe='';
if($('input[name="liste_mots"]').val() != '')
{
pipe=',';
}
$('.liste_mots').html($('.liste_mots').html() + '<span class="mot">' + $('input[name="ajout_mot"]').val() + '</span>');
$('input[name="liste_mots"]').val($('input[name="liste_mots"]').val() + pipe + $('input[name="ajout_mot"]').val());
$('input[name="ajout_mot"]').val('').placeholder().show(true);
margin_mot();
}
}
/*------------------------------------------------------------------------------------------------------------*/


$.fn.customFileInput = function(options)
{
var defaults = 
{
width: 'inherit',
buttonText: 'Sélectionnez votre fichier',
changeText: 'change',
inputText: 'No file selected',
icone : '',
cut_fichier : 30,
showInputText: true,
maxFileSize: 0,
onChange: $.noop
};
var opts = $.extend(true, {}, defaults, options);

var fileInput = $(this)
.addClass('customfile-input') 
.mouseover(function(){ upload.addClass('customfile-hover'); })
.mouseout(function(){ upload.removeClass('customfile-hover'); })
.focus(function(){
upload.addClass('customfile-focus'); 
fileInput.data('val', fileInput.val());
})
.blur(function(){ 
upload.removeClass('customfile-focus');
$(this).trigger('checkChange');
})
.bind('disable',function(){
fileInput.attr('disabled',true);
upload.addClass('customfile-disabled');
})
.bind('enable',function(){
fileInput.removeAttr('disabled');
upload.removeClass('customfile-disabled');
})
.bind('checkChange', function(){
if(fileInput.val() && fileInput.val() != fileInput.data('val')){
fileInput.trigger('change');
}
})
.mousedown(function(e){ 
if( e.button == 2 ) {  
fileInput.css({             
'top': e.pageY - upload.offset().top - fileInput.outerHeight() - 30
});	  
} 
return true; 
})  
.bind('change',function(){     

var fileName = $(this).val().split(/\\/).pop();
var cut_fileName = fileName.substr(0, opts.cut_fichier) + '...';

var fileExt = 'customfile-ext-' + fileName.split('.').pop().toLowerCase();


uploadFeedback
.html(cut_fileName + '<span>Parcourir</span>') 
.removeClass(uploadFeedback.data('fileExt') || '') 
.addClass(fileExt) 
.data('fileExt', fileExt) 
.addClass('customfile-feedback-populated'); 

uploadButton.text('Change');
})
.click(function(){  
fileInput.data('val', fileInput.val());
setTimeout(function(){
fileInput.trigger('checkChange');
},100);       
});    

var upload = $('<div class="customfile"></div>');   
var uploadFeedback = $('<a href="javascript:void(0)" class="customfile-feedback" aria-hidden="true"></a>')
.html(opts.buttonText + '<span>Parcourir</span>')
.css({'background-image':'url(' + opts.icone + ')', 'background-repeat' : 'no-repeat', 'background-position': 'left center'}) 
.appendTo(upload);

if(fileInput.is('[disabled]')){
fileInput.trigger('disable');
}
upload
.mousemove(function(e){
fileInput.css({             
'left':  e.pageX - upload.offset().left - fileInput.outerWidth() + 20,
'top': e.pageY - upload.offset().top - fileInput.outerHeight() + 10
});	 
})  
.insertAfter(fileInput);

fileInput.appendTo(upload);

return $(this);
}  



