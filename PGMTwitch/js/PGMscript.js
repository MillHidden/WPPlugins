
$(document).ready(function($){
	$("#form_follow").submit(function( event ) {
		event.preventDefault();
		
		var value = $("#follow_submit").data("value");
		$.post(
			"http://localhost/PGMWP/wordpress/wp-content/plugins/PGMTwitch/PGMfollow.php",
			{
				'follow' : value
			}						
		).done(function( data ){			
			if ($("#follow_submit").data("value") == true)
			{
				$("#follow_submit").data("value", false);
				$("#follow_submit").val("Ne plus suivre");
			}
			else
			{
				$("#follow_submit").data("value", true);
				$("#follow_submit").val("Suivre la chaîne");
			}	
			
		})
		.fail(function(){
			$("#result").html("Il y a eu des erreurs...");
		});
	});	
});
