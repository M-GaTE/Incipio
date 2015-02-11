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

	
});
