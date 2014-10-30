/*****************

parametres du mini-slide

*****************/
var timer = '';    
var secondes = 4;
var vitesse='slow';
  
  $(document).ready(function()
{
	$('.no_front').each(function()
	{
		$(this).remove();
	});
	
   $('.deroul').live('click', function()
  {
      if($(this).next().find('div').is(':visible'))
        {   
        $(this).next().find('div').hide().removeClass('ouvert').addClass('ferme');
        $(this).next().css({'background-color':'rgb(200,200,200)','height':'10px','transition':'background-color .3s'});
        }
      else
        {   
        $(this).next().css({'background-color':'rgb(240,240,240)','height':'180px','transition':'background-color .3s'});
        $(this).next().find('div').slideToggle().removeClass('ferme').addClass('ouvert');      
        }
  });
/*************************
	gestion de la largeur des parties du site en fonction de leur présence ou non
***********************/
	if($('#mini_slide').length == 0 && $('#conteneur_calendrier').length == 0 && $('#syndications').length == 0)
	{
		$(this).css({'display':'none'});
		$('#section_2').css({'width':'90%'});
	}
/*permet l'affichage en douceur du menu*/
 $('.element_slide').click(function()
{  
  $('.slide').slideToggle();      
});
  
var nombre_bloc = $('.bloc').length;

if(nombre_bloc > 1)
{
  var boutons='<div class="boutons">';
  boutons +='<span id="nb_nav">1 / ' + nombre_bloc + ' </span>';
  boutons +='<a class="precedent" href="javascript:void(0)"><img src="../img/icones/precedent.png" alt="" title="précédent" /></a>'; //"javascript:void" veut dire, "il y aura un lien javascript", remplace le href="#" et évite l'apparition du "#" dans l'url.
  boutons +='<a class="suivant"   href="javascript:void(0)"><img src="../img/icones/suivant.png" alt="" title="suivant" /></a>';
  boutons +='</div>';
  boutons +='<hr />';
    
  $('.bloc').first().before(boutons);// on place le premier bouton avant le bloc
  navigation('.precedent','.suivant');
}

//---------------permet de cacher et montrer le texte de "suggestion"  
$('#zone_droite h3 span').click(function()
{
  if($(this).text()=='+')
  {
    $(this).text('-').css
    ({
      'border-radius':'20px',
      'transition':'0.3s all',
      '-o-transform':'rotate(180deg)',
      '-ms-transform':'rotate(180deg)',       //effet de transition
      '-webkit-transform':'rotate(180deg)',
      '-moz-transform':'rotate(180deg)',
      'background-color':'rgb(245,67,5)'
      });
  }
  else
  {
    $(this).text('+').css
    ({
        'border-radius':'20px',
        'transition':'0.3s all',
        '-o-transform':'rotate(0deg)',
        '-ms-transform':'rotate(0deg)',
        '-webkit-transform':'rotate(0deg)',
        '-moz-transform':'rotate(0deg)',
        'background-color':'red' 
      });
  }
      
    $('#suggestion').slideToggle();
    });

//----------------Affichage et clique du bouton pour remonter le sous-menu---------------------
$(window).scroll(function()
{  
  if($(window).scrollTop() > 0 && $(window).scrollTop() < 200)
  {
    $('#scrolltop').fadeOut('fast');
  }
  else
  {
    if($(window).scrollTop() > 200)
    {
      if($('#scrolltop').length == 0)
      {
        $('body').append('<a href="javascript:void(0)" id="scrolltop"><img src="../img/icones/scrolltop.jpg" title="haut de page /"></a>');
      }
      
      $('#scrolltop').fadeIn('fast');
    }
  }
});

$('#scrolltop').live('click',function()
{
  $('html,body').animate({scrollTop:0},'slow');
});

//---------------------------------Appel vers la fonction menu déroulant--------------------

menu_accordeon('.item_menu');

//--------------------affichage des menus ouvert si il existe le cookie--------------------

if($.cookie('accordeon') != null)
{
  var explode = $.cookie('accordeon').split(',');
  
  for(var i=0; i < explode.length; i++)
  {
    $('#item_sous_menu' + explode[i]).show().removeClass('close').addClass('open');
    $('#item_sous_menu' + explode[i]).parent().find('.item_menu span').text('-');
  }
  
}
//--------------------------------------Appel vers la fonction mini-slide-------------------

mini_slide('#mini_slide');

//-------------- Appel vers la fonction navigation du slide ---------------------------
nav_mini_slide('.nav_off, .nav_on', '#pause');

//------------------- Appel vers la fonction Fonzy ---------------------------------------
  fonzy_box('#mini_slide li a');
  
  //au clique sur le voile, il disparait
  fonzy_box('#mini_slide li a');
	
	$('#voile').live('click', function()
	{
		$(this).fadeOut(vitesse);
	});
  //------------- Au survol apparition et disparition du bouton pause -------
  $('#mini_slide').hover(function()
  {
   //------------ Si le bouton n'existe pas on le crée et l'affiche ---------
   if($('#pause').length == 0)
   {
    //------------------- Arret du timer ----------- 
    clearTimeout(timer);
    timer='';
    $('#mini_slide li:visible').append('<span id="pause" class="pause"></span>');
   }    
  },
  function()
  {
   //Si le bouton n'a pas été cliqué on le supprime et on démarre le timer
   if($('#pause').hasClass('pause'))
   {
    timer = setTimeout("mini_slide('#mini_slide');", 2500);
    $('#pause').remove();
   }
  });
  
  connexion('#connexion');
  tab_suggestions()

}); 
//-------------------------------------------fin du $(document).ready
function tab_suggestions()
{
  $.ajax({type:'POST', url:'../pages/suggestions.php', data:'suggestions=1', 
  success: function(reponse) 
  {
    suggestions('#form_recherche input[type="text"]', reponse);     
  }          
  });
}

//-----------------suggestions du moteur de recherche--------------------------
function suggestions(champ, tab_mots) 
{  
  tab_mots=jQuery.parseJSON(tab_mots)

	$('#form_recherche').append('<ul id="suggestions"></ul>');
	
	$(champ).bind('keyup focus', function()
	{
		var txt = $(this).val();
		
		if(!txt)
		{
			$('#suggestions').hide();
			return;
		}
		
		var li = '';
		for(var i = 0; i < tab_mots.length; i++)
		{
			if(new RegExp("^"+txt,"i").test(tab_mots[i]))
			{
				li += '<li>' + tab_mots[i].replace(new RegExp("^(" + txt + ")", "i"), '<span>$1</span>') + '</li>';
			}
		}
		
		if(li != '')
		{
			$('#suggestions').html(li).show();
		}
		else
		{
			$('#suggestions').hide();
		}
		
	});
	
	
	$('#form_recherche').hover(function()
	{
		is_hover = true;
	},
	function()
	{
		is_hover = false;
	});
	
	$(champ).blur(function()
	{
		if(is_hover == false)
		{
			$('#suggestions').hide();			
		}
	});
	
	$('#suggestions li').live('click', function()
	{
		$(champ).val($(this).text()).focus();
		$('#suggestions').hide();
	});
  
};


//---------------------------------Appel vers la fonction menu_accordeon---------------------------
function menu_accordeon(item)
{
  //--- Au clique d'un item du menu
  $(item).click(function()
  {
    //-------- On détruit le cookie -------
    $.cookie('accordeon', null);//destruction du cookie
  
    //----- Gestion du plus et du moins de l'item ---------
    if($('span', this).text() == '+')
    {
      $('span', this).text('-');
    }
    else
    {
      $('span', this).text('+')
    }
    
    //--- Si le sous-menu est fermé on lui donne la class open -----------------
    if($('ul', $(this).parent()).hasClass('close'))
    {
      $('ul', $(this).parent()).removeClass('close').addClass('open');
    }
    //--- Sinon on lui donne la class close ------------------------------------
    else
    {
      $('ul', $(this).parent()).removeClass('open').addClass('close');
    }
    
    /*
    if($('ul', $(this).parent()).is(':visible'))
    {
      $('ul', $(this).parent()).hide();  
    }
    else
    {
      $('ul', $(this).parent()).show();
    }
    */
    
    //--- Apparition ou disparition avec un effet accordéon --------------------
    $('ul', $(this).parent()).slideToggle();
    
    var i=1;
    var open=new Array();
    
    //--- Boucle pour stocker les sous-menu ouvert dans un cookie --------------
    $('#menu_accordeon ul').each(function()
    {
      if($(this).hasClass('open'))
      {
        open.push(i);  
      }
      i++;
      
    });
    
    //--- Si il y a au moins un 1 sous-menu ouvert on crée le cookie -----------
    if(open.length > 0)
    {
      $.cookie('accordeon', open,7);
    }
    
      
  });
};



//-----------------création de la navigation------------------------------------ 
function navigation(prec, suiv)
{
  //---- Au clique du bouton précédent -----------------------------------------
  $(prec).live('click', function()//le "live" permet de cliquer sur un élément préalablement générer en Javascript/JQUERY
  {
    //------- On identifie le bloc visible et le bloc précédent ----------------
    var bloc_visible = $(this).parent().parent().find('.bloc:visible').attr('id').split('_');
    var bloc_precedent = parseInt(bloc_visible[1]) - 1;
    
    //------ Si le bloc  précédent existe on l'affiche et on cache le bloc visible
    if($('#bloc_' + bloc_precedent) . length > 0)
    {
      $('#bloc_' + bloc_visible[1]).hide();// le .hide permet de cacher un élément
      $('#bloc_' + bloc_precedent).fadeIn('slow');// a la place de fadeIn on peut mettre show pour afficher tout simplement
      $('#nb_nav').text(bloc_precedent + ' / ' + $('.bloc').length);          
    }
    //----- Sinon on cache le premier bloc et affiche le dernier
    else
    {
      $('#bloc_' + bloc_visible[1]).hide();
      $('#bloc_'+ $('.bloc').length).fadeIn();
      $('#nb_nav').text($('.bloc').length +' / ' + $('.bloc').length);  
    }
    
  });
  
  //----- Au clique du bouton suivant ------------------------------
  $(suiv).live('click', function()
  {
    //------- On identifie le bloc visible et le bloc suivant ----------------
    var bloc_visible = $(this).parent().parent().find('.bloc:visible').attr('id').split('_');
    var bloc_suivant = parseInt(bloc_visible[1]) + 1;
    
    //------ Si le bloc  suivant existe on l'affiche et on cache le bloc visible
    if($('#bloc_' + bloc_suivant) . length > 0)
    {
      $('#bloc_' + bloc_visible[1]).hide();
      $('#bloc_' + bloc_suivant).fadeIn('slow');
      $('#nb_nav').text(bloc_suivant + ' / ' + $('.bloc').length);          
    }
    //----- Sinon on cache le dernier bloc et affiche le premier
    else
    {
      $('#bloc_' + bloc_visible[1]).hide();
      $('#bloc_1').fadeIn();
      $('#nb_nav').text('1 / ' + $('.bloc').length);
    }
     
  });

  //-------- Fonction pour afficher une info bulle au survol ---------------------  
  $('.bloc img').hover(function () 
  { 
    //--- On crée l'info bulle et on l'affiche -----------------------------------      
    var info_bulle = '<div class="info_bulle">';
        info_bulle+= $(this).attr('alt');
       info_bulle+= '<div class="pointe"></div>';
       info_bulle+= '</div>';
        
   $(this).parent().prepend(info_bulle);//prepend va faire afficher l'info bulle avant le parent de .bloc img, ici c'est au-dessus du texte
  },                                    // la virgule permet de cumuler des fonctions
  function() 
  { 
    //-------- on supprime l'info bulle ------------------------------------------   
    $(this).parent().find('.info_bulle').remove();
  }); 
  
};

//--------------------------------- FUNCTION mini-slide ---------------------------------
function mini_slide(ul)
{ //--- Si le timer n'est pas nul ---------------------------------------------
  if(timer != '')
  { //--- On identifie le li suivant et le bounton navigation suivant
    var next_li=$('li:visible', ul).first().next();
    var next_nav=$('.nav_on').next('a');

    //-- Si il existe un li suivant --------------------------------------------
    if(next_li.length > 0 )
    { //--- On cache le li visible et on affiche le suivant --------------------
      $('li:visible p', ul).first().hide();
      $('li:visible', ul).first().hide().next().fadeIn(vitesse);
      $('p', next_li). slideToggle(vitesse);
      
      //--- On met en opacité 0.4 le bouton actif et on met en opacité 1 le suivant ---
      $('.nav_on').fadeTo(vitesse, 0.4).removeClass('nav_on').addClass('nav_off');
      next_nav.fadeTo(vitesse, 1).removeClass('nav_off').addClass('nav_on');
    }
    //--- Sinon on cache le dernier li et on repart sur le premier -------------
    else
    { $('li', ul).last().hide();
      $('p', ul).last().hide();
      
      $('li', ul).first().fadeIn(vitesse);
      $('p', ul).first().slideToggle(vitesse);
      
      $('.nav_on').fadeTo(vitesse, 0.4).removeClass('nav_on').addClass('nav_off');
      $('.nav_off').first().fadeTo(vitesse, 1).removeClass('nav_off').addClass('nav_on');    
      }
  }
  //--- Au chargment de la page le timer est nul -------------------------------
  else
  { 
    $('li:visible p', ul) . slideToggle(vitesse);
   
    //-- On test Si la "Div #nav_mini_slide" existe -----------------------------
    if($('#nav_mini_slide').length == 0)
    { 
      //--- On compte le nombre de li -------------------------------------------
      var nb_li=$('li', ul).length;
      //--- On insert le bloc #nav_mini_slide après le ul -----------------------
      $(ul).after('<div id="nav_mini_slide"></div>');
     
      //--- Si il y a au moins 1 li on crée une boucle
      if(nb_li > 0)
      {
        var current='';
        
        for(var i=0; i < nb_li; i++)
        {
          var current='';
          if(i == 0)
          {
            //--- Par défaut le premier bouton est actif ---
            current='nav_on';
          }
          else
          {
            //--- Les autres sont inactifs
            current='nav_off';
          }
          //--- A chaque tour de boucle on crée un bouton ---       
          $('#nav_mini_slide').append('<a href="javascript:void(0)" class="' + current + '"></a>');
     }   
   }
   }   
  }
  
  //--- On relance le fonction mini_slide toutes les "'n' secondes" ---
  timer = setTimeout("mini_slide('#mini_slide');", secondes * 1000);   
};

function nav_mini_slide(nav, pause)
{ //-- Au clique d'un bouton de navigation ---
  $(nav).live('click',function()
  { //--- On calcul la valeur de son index ---
    var nb_index=$(this).index();
    
    //--- Si le bouton cliqué est un bouton inactif ---
    if($(this).hasClass('nav_off'))
    { 
      //--- on arrete le timer ---
      clearTimeout(timer);
      timer='';
    
      //--- Si le bouton pause existe on le supprime ---
      if($('.start').length > 0)
      {
        $('#pause').remove();
      }

           
      //--- On passe le bouton actif en inactif ---
      $('.nav_on').fadeTo(vitesse, 0.4).removeClass('nav_on').addClass('nav_off');
      
      //--- Le bouton cliqué passe en actif ---
      $(this).fadeTo(vitesse, 1).removeClass('nav_off').addClass('nav_on');
      
      //On cache le li visible
      $('#mini_slide li:visible').hide();
      $('#mini_slide p').hide();
      
      //On fait une boucle sur tous les li existants
      $('#mini_slide li').each(function(i)
      {
        //Si la valeur de i correspond à l'index du bouton cliqué
        if(i == nb_index)
        { //On affiche le li
          $(this).fadeIn(vitesse);
          $('p', this).slideToggle(vitesse);
          
          //On relance le timer
          timer = setTimeout("mini_slide('#mini_slide');", 2000);
        }
      });
     } 
  });
  //Au clique du bouton pause
  $(pause).live('click', function()
  { //Si le bouton à la class pause, on lui change le background-image et on lui donne la class start
    if($(this).hasClass('pause'))
    {
      $(this).css('background-image', 'url(../img/icones/play.png)').removeClass('pause').addClass('start');
    }
    //Sinon on lui redonne la class pause et on lui change le  background-image
    else
    {
      $(this).css('background-image', 'url(../img/icones/pause.png)').removeClass('start').addClass('pause');
    } 
  }); 
};

//--------------------FUNCTION Fonzy Box-----------------------
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
//------------------------ FONCTION CONNEXION AU BACK OFFICE -------------------//
                              
	function connexion(bouton)
		{
			  var formulaire='<div id="fond_login">';
			  formulaire+='<form>';
			  formulaire+='<a href="javascript:void(0)" id="quitter">';
			  formulaire+='<img src="../img/icones/quitter.png" />';
			  formulaire+='</a>';
			  formulaire+='<input class="champ" type="text" placeholder="Login" />';
			  formulaire+='<input class="champ" type="password" placeholder="Mot de passe" />';
			  formulaire+='<input type="button" value="Connexion" />';      
			  formulaire+='</form>';
			  formulaire+='</div>';
	  
			  $(bouton).click(function()
				  {
						$('body').append(formulaire);
				  });
			  
			  $('#quitter').live('click', function()
				  {

						$(this).parent().parent().remove();
				  }); 
			  
			  $('.champ').live('focus', function()
				  {
						$(this).css('border', '1px solid #d4d4d4');
				  });
			  
			  $('#fond_login form input[type=button]').live('click', function() 
					{
						var i=0;
						
						$('.champ').each(function()
							{
								  if(verif_champ(this) == '')
									  {
											$(this).css('border', '1px solid gray');
									  }
								  else
									  {
											$(this).css('border', '1px solid #d4d4d4');
											i++;
									  }
							});
					
						if(i == 2)
							{
								 requete_login(); 
							  
							}
					});
			  $('.champ').live('keypress', function(event) 
					{
						if(event.keyCode==13 || event.which==13)
							{
								requete_login();
							}
					}
				);
		};
			


//-------------- VERIFICATION DES CHAMPS (voir s'ils sont vides) ---------------//

	function verif_champ(champ)
	{
		  var valeur=$(champ).val(); //on récupère la valeur du champ
		  var attr_placeholder=$(champ).attr('placeholder');  //on récupère la valeur du placeholder
		  if(valeur == '' || valeur == attr_placeholder) //si la valeur est égale à rien ou au placeholder alors elle est nulle
			  {
				new_valeur='';
			  }
		  else //sinon la valeur est celle que l'on a saisie
			  {
				new_valeur=valeur;
			  }
		  return new_valeur;
		  // on retourne la valeur
	};
	
	function requete_login()
		{
		
			$.ajax(
				{
					type:'POST', 
					url:'../pages/login.php', 
					data:'login=' + $('.champ').first().val() + '&password=' +
					$('.champ').last().val(), success: function(reponse) 
						{
							if(reponse != false)
								{
									 document.location.href=reponse;
								}
							else
								{
									if($('erreur_login').length == 0)
										{
											$('#fond_login form').prepend('<p id="erreur_login">Identifiant ou mot de passe incorrect</p>');
										}
								}
									  
						}
				});
		
		
		}
	
