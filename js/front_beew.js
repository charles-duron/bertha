/*****************

détection de terminaux mobiles  (ex : if( isMobile.any() ) alert('Mobile'); )

*****************/
var isMobile = 
	{
		Android: function() 
		{
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() 
		{
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() 
		{
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() 
		{
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() 
		{
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() 
		{
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	},
/*****************

parametres du slide

*****************/
	timer = '',
	secondes = 10,
	vitesse = 'slow';
/***************************************************************************






DEBUT DU DOCUMENT READY






***************************************************************************/
$(document).ready(function()
{

	$('#mini_slide').hover(function()	//Premier cas : au survol
	{
		if($('#play').hasClass('cache'))
		{
			clearTimeout(timer);
		}
	},
	function()	//Gestion du deuxième cas : sortie du survol
	{
		if($('#play').hasClass('cache'))
		{
			timer = setTimeout("mini_slide('#mini_slide');", secondes * 1000);
		}
	});
/*****************

changement du texte du rond du slide au survol de la vignette

*****************/
	$('.image_slide').hover(function()
	{	
		$('.texte_rond_slide_visible', this).hide();
		$('.texte_rond_slide_cache', this).fadeIn('slow');
	},
	function()
	{
		$('.texte_rond_slide_cache', this).hide();
		$('.texte_rond_slide_visible', this).fadeIn('slow');
	});
/*****************

changement de l'aspect du bouton envoi du footer

*****************/
	$('.lien_submit_footer').hover(function()
	{	
		$('#submit_footer').css({'background-image':'url("../img/icones/envoyer_hover.png")'});
	},
	function()
	{
		$('#submit_footer').css({'background-image':'url("../img/icones/envoyer.png")'});
	});
/*****************

ajout du rond_catchphrase dans h1

*****************/
	$('h1').each(function()
	{
		$(this).wrapInner('<span></span>').append('<div class="rond_catchphrase sous_titre" ></div>');
	});
/*****************

gestion du bouton de retour au haut de page

*****************/
	$(window).scroll(function()
	{
		if($(window).scrollTop() > 0 && $(window).scrollTop() < 200)
		{
			$('#scrolltop').animate({bottom:'-=60'}, 'fast').removeClass('in').addClass('out');
		}
		else
		{
			if($(window).scrollTop() > 200)
			{
				if($('#scrolltop').length == 0)
				{
					$('body').append('<a href="javascript:void(0)" id="scrolltop" class="out" ><img src="../img/icones/scrolltop.png" title="haut de page"></a>');
				}

				if($('#scrolltop').hasClass('out'))
				{
					$('#scrolltop').animate({bottom:'+=60'}, 'fast').removeClass('out');
				}
			}
		}
	});

	$('#scrolltop').live('click',function()
	{
		$('html,body').animate({scrollTop:0},'slow', 'swing');
	});
/*****************

suppression du voile de la fonzy box au clic

*****************/
	$('#voile').live('click', function()
	{
		$(this).fadeOut(vitesse);
	});
/***************************************************



fonctionnalités spéciales mobile



***************************************************/
	if(isMobile.any() || $(window).width() < 800)
	{
/*****************

retrait du titre sur les images de compétences sur mobile

*****************/
		$('.thumb img').each(function()
		{
			var src = $(this).attr('src').split('_titre')[0];
			$(this).attr('src', src + '.jpg');
		});
/*****************

retrait du titre lien au clic sur les elements de menu de niveau 1 avec des enfants

*****************/
		$('.niveau_1').each(function()
		{
			if($(this).find('.niveau_2').length != 0)
			{
				$('.lien_principal', this).attr('href', '#');
			}
		});
	}
	else
	{
/***************************************************



fonctionnalités spéciales ordi



***************************************************/
/*****************

gestion de la taille des images du slider en fonction de la taille de l'ecran

*****************/
		$('.image_slide').each(function()
		{
			var src = $('img', this).attr('src').split('_')[0],
				nouveau_src = src + '_moyen.jpg';
				
			$('img', this).attr('src', nouveau_src);
		});		
/*****************

flip des competences

*****************/
		
		$(function ()
		{
			// Utilize the modernzr feature support class to detect CSS 3D transform support
			if ($('html').hasClass('csstransforms3d') && navigator.userAgent.search("MSIE") >= 0)
			{	
				// if it's supported, remove the scroll effect add the cool card flipping instead
				$('.thumb').removeClass('scroll').addClass('flip');		
				// add/remove flip class that make the transition effect
				$('.thumb.flip').hover(
					function ()
					{
						$(this).find('.thumb-wrapper').addClass('flipIt');
					},
					function ()
					{
						$(this).find('.thumb-wrapper').removeClass('flipIt');			
					}
				);
				
			}
			else
			{
				if ($('html').hasClass('csstransforms3d') && (navigator.userAgent.search("Firefox") >= 0 || navigator.userAgent.search("Chrome") >= 0 || (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) || navigator.userAgent.search("Opera") >= 0))
				{	
					// if it's supported, remove the scroll effect add the cool card flipping instead
					$('.thumb').removeClass('scroll').addClass('flip');		
					// add/remove flip class that make the transition effect
					$('.thumb.flip').hover(
						function ()
						{
							$(this).find('.thumb-wrapper').addClass('flipIt');
						},
						function ()
						{
							$(this).find('.thumb-wrapper').removeClass('flipIt');			
						}
					);
					
				}
				else
				{
					// CSS 3D is not supported, use the scroll up effect instead
					$('.thumb').hover(
						function ()
						{
							$(this).find('.thumb-detail').stop().animate({bottom:0}, 500, 'easeOutCubic');
						},
						function ()
						{
							$(this).find('.thumb-detail').stop().animate({bottom: ($(this).height() * -1) }, 500, 'easeOutCubic');			
						}
					);
				}
			}
		});
	}
/*****************

gestion de la marge des elements de portfolio

*****************/
	if($('#portfolio').width() >800)
	{
		var tiers = ($('#portfolio').width() - 680) / 3;
		$('.view').css({'margin-left':tiers});
	}
	else
	{
		$('.view').css({
		'margin':'10px auto 30px',
		'float':'initial'});
	}
/***************************************************


appels de fonction


***************************************************/
	mini_slide('#mini_slide');
	
	navigation('#gauche', '#droite', '#mini-slide', '.slide');

	playpause('#play', '#pause');

	ouverture_menu('#bouton_menu', '#menu');//mobile
	
	ouverture_sous_menus();

	scroll_to_contact();
	
	fonzy_box('.fonzy_box');
  
});
/*********************************************************************************







fin document ready







*********************************************************************************/
/*****************

slider

*****************/
function mini_slide(ul)
{
	//Si le timer n'est pas nul, on identifie le li visible et le bouton de navigation suivant.
		//S'il existe un li suivant, on switche l'affichage li visible/suivant et on baisse l'opacité du bouton actif et on augmente celle du suivant.
		//Sinon, on cache le dernier et on affiche le premier.
	if(timer != '')
	{
		var next_li = $('.slide:visible', ul).first().next();

		if(next_li.length > 0)	//s'il existe une image suivante
		{
			$('.slide:visible', ul).hide().removeClass('visible');
			$(next_li).fadeIn(vitesse).addClass('visible');
		}
		else	//sinon
		{
			$('.slide:visible', ul).last().hide().removeClass('visible');
			$('.slide', ul).first().fadeIn(vitesse).addClass('visible');
		}
	}
	//Sinon, si le timer est nul, on teste l'existence du bloc "#nav_mini_slide".
		//S'il existe, on insère le bloc "#nav_mini_slide" après le ul. On compte le nombre de li.
			//Si au moins 1 li existe, on les parcourt. Par défaut, on active uniquement le premier.
	else
	{
		if($('.slide').length > 0)
		{
			$('.slide', ul).first().show();
		}
	}
	
	timer = setTimeout("mini_slide('#mini_slide');", secondes * 1000);	//La fonction mini-slide est relancée toutes les x secondes
	
};
/*****************

navigation par fleches

*****************/
function navigation(gauche, droite, ul, slide)
{

	$(gauche).live('click', function()	//Au clic du bouton précédent
	{	
		clearTimeout(timer);
		
		$(slide).each(function(index, value)	//on fait le tour des slides
		{		
			if($(this).hasClass('visible'))	//on identifie le slide visible
			{			
				if(index > 0)	//s'il existe un slide précédent :
				{
					var prev_li = $(this).prev();

					$(slide).hide().removeClass('visible');
					$(prev_li).fadeIn(vitesse).addClass('visible');
				}
				else	//sinon :
				{
					var last_li = $(slide).last();

					$(slide).hide().removeClass('visible');
					$(last_li).fadeIn(vitesse).addClass('visible');			
				}				
				if($('#play').hasClass('cache'))
				{
					timer = setTimeout("mini_slide('#mini_slide');", secondes * 1000);
				}
				
				return false;	//on casse la boucle
			}		
		});		
	});
	
	$(droite).live('click', function()	//Au clic sur le bouton "suivant"
	{	
		clearTimeout(timer);
		
		$(slide).each(function(index, value)	//on fait le tour des slides
		{		
			if($(this).hasClass('visible'))	//on identifie le slide visible
			{
				if((($(slide).length - 1) - index) > 0)	//s'il existe un slide suivant :
				{
					var next_li = $(this).next();

					$(slide).hide().removeClass('visible');
					$(next_li).fadeIn(vitesse).addClass('visible');
				}
				else	//sinon :
				{
					var first_li = $(slide).first();

					$(slide).last().hide().removeClass('visible');
					$(first_li).fadeIn(vitesse).addClass('visible');
				}
				
				if($('#play').hasClass('cache'))
				{
					timer = setTimeout("mini_slide('#mini_slide');", secondes * 1000);
				}
		
				return false;	//on casse la boucle
			}		
		});
	});	
};
/*****************

bouton play/pause

*****************/
function playpause(play, pause)
{
	$(pause).live('click', function()	//Au clic sur le bouton "pause"
	{
		$(pause).hide().removeClass('visible').addClass('cache');
		$(play).first().fadeIn('fast').addClass('visible').removeClass('cache');			

		clearTimeout(timer);
		timer='';
	});
	
	$(play).live('click', function()	//Au clic sur le bouton "play"
	{	
		$(play).hide().removeClass('visible').addClass('cache');
		$(pause).first().fadeIn('fast').addClass('visible').removeClass('cache');		
		
		//On relance le timer
		timer = setTimeout("mini_slide('#mini_slide');", 3000);
	});	
};
/*****************

ouverture/fermeture du menu pour mobile

*****************/
function ouverture_menu(bouton, menu)
{
	$(bouton).live('click', function()
	{
		if($(menu).hasClass('ferme'))	//ouverture
		{
			$(menu).removeClass('ferme').slideToggle('slow');
			$('#bouton_menu').css({'background-image':'url("../img/icones/fermer_menu_mobile_0.8.png")'});
		}
		else	//fermeture
		{
			$(menu).addClass('ferme').slideToggle();
			$('#bouton_menu').css({'background-image':'url("../img/icones/menu_mobile_0.8.png")'});
			
			$('.niveau_2').each(function()	//on fait le tour des éléments de sous-menu pour les fermer si nécessaire
			{
				if($(this).hasClass('ouvert'))
				{
					$(this).slideToggle().removeClass('ouvert');		
				}
			});
			
			$('.niveau_1').each(function()	//on fait le tour des éléments de menu pour remettre le padding-bot à 5
			{
				if($(this).css('padding-bottom', '10px'))
				{
					$(this).css('padding-bottom', '5px');		
				}
			});
		}
	});
};
/*****************

ouverture des sous-éléments de menu au clic

*****************/
function ouverture_sous_menus()
{
	$('.niveau_1').live('click', function()
	{
		if(isMobile.any() || $(window).width() < 800)	//cas où on a affaire à un terminal mobile
		{
			if($('.niveau_2', this).hasClass('ouvert'))	//si le sous-menu est ouvert, on le ferme
			{
				$('.niveau_2', this).slideToggle().removeClass('ouvert');		
				$(this).css('padding-bottom', '5px');
			}
			else	//s'il est fermé, on referme les éventuels sous-menus ouverts et on ouvre celui-ci
			{
				$('.niveau_2').each(function()	//on fait le tour des éléments de sous-menu pour les fermer si nécessaire
				{
					if($(this).hasClass('ouvert'))
					{
						$(this).slideToggle().removeClass('ouvert');
						$(this).parent('.niveau_1').css('padding-bottom', '5px');
					}
				});
				
				if($(this).find('.niveau_2').length != 0)
				{
					$('.niveau_2', this).slideToggle().css('display', 'block').addClass('ouvert');	//et on ouvre les sous-menus s'ils existent
					$(this).css('padding-bottom', '10px');
				}
				else
				{
					$(menu).addClass('ferme').slideToggle();	//on ferme le menu
					$('#bouton_menu').css({'background-image':'url("../img/icones/menu_mobile_0.8.png")'});
				
					$('.niveau_2').each(function()	//on fait le tour des éléments de sous-menu pour les fermer si nécessaire
					{
						if($(this).hasClass('ouvert'))
						{
							$(this).slideToggle().removeClass('ouvert');		
							$(this).css('padding-bottom', '5px');
						}
					});
				}
			}
		}
		else	//cas où on a affaire à un ordinateur
		{
			
			if($(this).find('.niveau_2').length != 0)
			{
				$('.niveau_1 a').each(function()
				{
					if($(this).hasClass('selectionne'))
					{
						$(this).removeClass('selectionne').addClass('deselectionne');
					}
				});
				
				$('a', this).each(function()
				{
					if($(this).hasClass('deselectionne'))
					{
						$(this).removeClass('deselectionne').addClass('selectionne');
					}
				});
			}
		}
	});	
};
/*****************

scroll vers le formulaire de contact

*****************/
function scroll_to_contact()
{
	$('.contact').live('click', function()
	{
		$('html, body').animate(
		{
			scrollTop: $('#formulaire_contact_footer').offset().top
		}, 1300, 'swing');
	});
};
/*****************

fancy box

*****************/
function fonzy_box(image)
{ //Au clique d'une image
  $(image).click(function()
	{ //Si la div voile existe
    if($('#voile').length == 0)
		{ //On crée l'élément voile avec son image
      $('body').append('<div id="voile"><div><img src="' + $('img',this).attr('src') + '" /></div></div>');
		}
    //Sinon on change juste son attribut src
    else
    {
      $('#voile div').html('<img src="' + $('img', this).attr('src') + '" />');
      /*$('#voile*/
		} 
    //Fonction pour le chargerment de l'image
    $('#voile img').one('load', function()
    {
      //On fait apparaitre la div #voile
		  $('#voile').fadeIn();
      //Mais on garde son contenu en hidden le temps de calculer sa position 
			$('#voile div').css('visibility', 'hidden');
			
      //On récupère la width du conteneur et la heigth de la fenêtre
			var max_width = $('#conteneur').width() - 100;
			var max_height = $(window).height() - 200;
			
      //On ajuste la taille de l'image en fonction de max_width et max_height
			$('#voile div img').css({'max-width' : '' + max_width + 'px', 'max-height' : '' + max_height + 'px'});
			//On donne la même width que l'image à la div
      $('#voile div').width($('#voile div img').width());
			
      //On récupère la height de la div
			var height = $('#voile div').height();
			//On ajuste le margin-left et le margin-top en fonction des tailles récupérées
      $('#voile div').css({'margin-left' : '-' + ($('#voile div img').width() + 30) / 2 + 'px', 'margin-top' : '-' + (height + 30) / 2 + 'px'});			
			//une fois les calculs terminés on rend visible le contenu
      $('#voile div').css('visibility', 'visible');			
		});
	});
  
  //Au redimensionnement de la fenêtre
  $(window).resize(function()
  {
    if($('#voile').length > 0 && $('#voile').is(':visible'))
    {
      var max_width = $('#conteneur').width() - 100;
			var max_height = $(window).height() - 200;
      
			$('#voile div img').css({'max-width' : '' + max_width + 'px', 'max-height' : '' + max_height + 'px'});
      $('#voile div').width($('#voile div img').width());
			var height = $('#voile div').height();
      $('#voile div').css({'margin-left' : '-' + ($('#voile div img').width() + 30) / 2 + 'px', 'margin-top' : '-' + (height + 30) / 2 + 'px'});
    }
  }); 
};
