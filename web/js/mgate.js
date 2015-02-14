$(document).ready(function(){
	// Prepare sidebar
	$('#sidebar a').each(function(i,e){
		var parent = $(e).attr("data-target");
		if(typeof(parent) != "undefined"){
			$(e).click(function(){
				$('#sidebar .enfant,div.floatContainer').each(function(index,element){
					if($(element).attr('data-parent') == parent)
						$(element).toggle("fast");
					else
						$(element).hide("fast");
				});
			});
		}else{
			var enfant = $(e).attr("data-parent");
			if(typeof(enfant) != "undefined"){
				$(e).hide();
			}
		}
	});
	$("#sidebar .enfant").hide();
	$("#sidebar .floatContainer").hide();

	current = $("#sidebar").attr("data-current");
	pages = {
		'mgatePersonne_membre_homepage' : "gestAsso",
		'mgatePersonne_intervenants_homepage' : "gestAsso",
		'mgatePersonne_membre_ajouter' : "gestAsso",
		'mgate_formations_lister' : "formations",
		'mgate_formations_index_admin' : "formations",
		'mgate_formation_ajouter' : "formations",
		'mgate_formation_participation' : "formations",
		'mgateSuivi_etude_homepage' : "suiviEtudes",
		'mgateSuivi_etude_ajouter' : "suiviEtudes",
		'mgateSuivi_etude_suiviQualite' : "suiviEtudes",
		'mgateSuivi_clientcontact_index' : "suiviEtudes",
		'mgateSuivi_vu_ca' : "suiviEtudes",
		'mgateTreso_Facture_index' : "tresorerie",
		'mgateTreso_Facture_ajouter' : "tresorerie",
		'mgateTreso_BV_index' : "tresorerie",
		'mgateTreso_BV_ajouter' : "tresorerie",
		'mgateTreso_NoteDeFrais_index' : "tresorerie",
		'mgateTreso_NoteDeFrais_ajouter' : "tresorerie",
		'mgateTreso_Declaratif_TVA' : "tresorerie",
		'mgateTreso_Declaratif_BRC' : "tresorerie",
		'mgateTreso_Compte_index' : "tresorerie",
		'mgateTreso_Compte_ajouter' : "tresorerie",
		'mgateTreso_BaseURSSAF_index' : "tresorerie",
		'mgateTreso_BaseURSSAF_ajouter' : "tresorerie",
		'mgateTreso_CotisationURSSAF_index' : "tresorerie",
		'mgateTreso_CotisationURSSAF_ajouter' : "tresorerie",
		'mgatePersonne_prospect_homepage' : "prospection",
		'mgatePersonne_prospect_ajouter' : "prospection",
		'mgatePersonne_annuaire' : "prospection",
		'mgatePersonne_listeDiffusion' : "prospection",
		'mgate_indicateurs_index' : "pilotageJE",
		'mgate_publi_documenttype_index' : 'administration',
		'mgate_publi_documenttype_upload' : 'administration',
		'mgatePersonne_poste_homepage' : 'administration',
		'mgatePersonne_poste_ajouter' : 'administration',
		'mgate_user_lister' : 'administration',
		'mgateSuivi_domaine_index' : 'administration'
	};
	showCurrent();
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $(function() {
        $(document).on("click", ".bootbox-confirm", function(e) {
            e.preventDefault();
            var _this = $(this);
            return bootbox.confirm($(this).data('content'), function(result) {
                if(result) _this.closest('form').submit();
            });
        });
    });
    $(".loading").hide();
});

var pages;
var current;


function showCurrent(){
	if(typeof(pages[current]) != "undefined"){
		$('#sidebar a[data-target="'+pages[current]+'"]').addClass("actuel");
		$('#sidebar .enfant,div.floatContainer').each(function(index,element){
			if($(element).attr('data-parent') == pages[current])
				$(element).toggle();
			else
				$(element).hide("fast");
		});
	}
};