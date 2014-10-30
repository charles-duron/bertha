<?php
/************************************************************************************************************************







	FONCTIONNALITES DONT L'UTILITE N'EST PAS EVIDENTE
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*************************

	camembert accueil backoff
	
***********************/
	function camembert_accueil($requete, $connexion)
	{
		$resultat=mysql_query($requete, $connexion);
		$nb=mysql_num_rows($resultat);
		$taille_base=0;
		$ligne_base=0;
		while($ligne1=mysql_fetch_array($resultat))
		{  
			$taille_base += $ligne1['Data_length'] + $ligne1['Index_length'];
			$ligne_base += $ligne1['Rows'];
		} 
		$taille_ko=file_size_info($taille_base);


		$resultat2=mysql_query($requete, $connexion);
		$tab_table=array();

		while($ligne2=mysql_fetch_array($resultat2))
		{ 
			$addition=$ligne2['Data_length'] + $ligne2['Index_length']; 
			$addition2=($addition * $taille_base ) / 100;
			$tab_table[].="['" . ucfirst($ligne2['Name']) . "', " . $addition2 . "]"; 
		}  
		$table=implode(",",$tab_table);

		$camembert="<script type=\"text/javascript\">
		$(function () {
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
				chart: {
					renderTo: 'graph_camembert',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					backgroundColor:'rgba(255,255,255,0)'
				},
				title: {
					text: 'Base de données Taille " . $taille_ko['size'] . " " . $taille_ko['type'] . "- " . $nb . " Tables',
					style: { color: 'gray'}
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							color: '#000000',
							connectorColor: 'gray',
							formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: '',
					data: [ " . $table . " ]
				}]
				});
			});    
		});
		</script>";
		$camembert.="<script src=\"../js/highcharts.js\"></script>";
		$camembert.="<div id=\"graph_camembert\" ></div>";
		return $camembert;
	}
?>