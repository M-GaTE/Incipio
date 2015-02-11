$(document).ready(function(){
	$('#sidebar a').each(function(i,e){
		var parent = $(e).attr("data-target");
		if(typeof(parent) != "undefined"){
			$(e).click(function(){
				console.log("T'as cliqué !");
				$('#sidebar a[data-parent='+parent+']').each(function(index,element){
					$(element).toggle();
				});
			});
		}else{
			var enfant = $(e).attr("data-parent");
			if(typeof(enfant) != "undefined"){
				$(e).hide();
			}
		}
	});
});