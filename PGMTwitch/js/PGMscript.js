$(document).ready(function($){
	var timecall = 3000;  // temps entre deux récupération datas twitch en millisecondes
	var channelname = "puregamemedia"; // nom de la chaine à suivre
	var timecookie = 15;  // durée de vie du cookie en jours	
	var accesstoken = readCookie("PGMCookieuser"); // lecture du cookie
	var redirect = "http://localhost/PGMWP/wordpress/"; // redirection défini dans l'api
	var client_id = 'aaxnzxzwi2a9hsggbbqwipn27ip7ev9'; // client id définie dans l'api"
	var username; 	
	

	/* affichage de base */
	$("#datas").html("TV online viewers :  views :  followers :  titre : jeu :"); 
	$("#labelrm").html("remember me");
	$('#follow').html("").hide();

	/* lecture datas du stream */
	getDatasCall(channelname);
	setInterval(getDatasCall, timecall, channelname);

	/* initiatlisation api twitch */		
	Twitch.init({
			clientId: client_id,
			redirect_uri: redirect
		}, function(error, status){

		if (error){
			$('#login').html("fail");
		}else {
			
			if ((accesstoken == null) ||  (accesstoken == "undefined") || (accesstoken == "rm")){			
				if (status.authenticated){
					$('#login').html("logout");
					$("#rm").remove();
					$("#labelrm").remove();
					Twitch.api({method: 'user'}, function(error, user) {
						username = user.display_name;
						getFollow(username, channelname);
						
						$('#login').html("logout " + username);         		
		          		$('#follow').show();

		          		if (accesstoken == "rm") {
		          			accesstoken = Twitch.getToken();
		          			createCookie("PGMCookieuser", accesstoken, timecookie);	
		          		} 
								          				          		
		          	});          	         	       	
				}
				
				$('#login').click(function(){

					if ($('#login').html() == "Se connecter"){
						
						Twitch.login({
							scope: ['user_read', 'user_follows_edit']					
						});
						
		          		if ($('#rm').is(':checked')) {		          			
		          			createCookie("PGMCookieuser", "rm", timecookie);						
						}
						else {
							eraseCookie("PGMCookieuser");										
						}
					}else{
						Twitch.logout(function(error){
							$('#login').html("Se connecter");
							$('#follow').html("").hide();
							$("<label id=\"labelrm\"><input type=\"checkbox\" id=\"rm\" checked>remember me</label>").insertBefore('#login');
						});
					}
				});
				
			}else{	
				
				url = "https://api.twitch.tv/kraken/?oauth_token=" + accesstoken;
				$.ajax({
					url : url,
					dataType : "json"
				})
				.done(function (data, status, xhr){

					if (data.token.valid) {
						$('#login').html("logout");
						$("#rm").remove();
						$("#labelrm").remove();
						username = data.token.user_name;
						getFollow(username, channelname);
		          		$('#login').html("logout " + username);         		
		          		$('#follow').show();
		          		          		
					}else{
						eraseCookie("PGMCookieuser");							
					}

				})
				.fail(function(){
					
				});

		        $('#login').click(function(){

					if ($('#login').html() == "Se connecter"){
						
						Twitch.login({
							scope: ['user_read', 'user_follows_edit']					
						});
						if ($('#rm').is(':checked')) {
							accesstoken = Twitch.getToken();	  
							createCookie("PGMCookieuser", accesstoken, timecookie);					
						}
						else {
							eraseCookie("PGMCookieuser");
						}
					}else{
						Twitch.logout(function(error){
							$('#login').html("Se connecter");
							$('#follow').html("").hide();
							$("<label id=\"labelrm\"><input type=\"checkbox\" id=\"rm\" checked>remember me</label>").insertBefore('#login');
							eraseCookie("PGMCookieuser");
						});
					}
				});		
			}
		}	
	});

	$("#follow").click(function(){
		var value = $("#follow").attr("value");
		
		var url = "https://api.twitch.tv/kraken/users/"+username+"/follows/channels/"+channelname+"?oauth_token="+accesstoken;
		var meth;

		if (value == "true"){				
			meth = "PUT";		
			
		}else if (value == "false"){
			meth = "DELETE";
		}

		$.ajax({
			url : url,
			method : meth
		})
		.done(function(){
			getFollow(username, channelname);
		})
		.fail(function(){
			$('#follow').html("Erreur...");
		});			
	});
});

function getDatasCall(channelname){
	var url = "https://api.twitch.tv/kraken/streams/"+channelname;
		
	$.ajax({
		url : url,
		dataType : "json"
	})
	.done(function (data, status, xhr){		
		stream = data.stream;

		if (stream != null) {
			$("#datas").html("TV online" + " viewers : " + stream.viewers + " views : " + stream.channel.views + " followers : " + stream.channel.followers + " titre : " + stream.channel.status + " jeu : " + stream.game);
		}
		else {
			url = "https://api.twitch.tv/kraken/channels/"+channelname;
			$.ajax({
				url : url,
				dataType : "json"
			})
			.done(function (data, status, xhr){
				$("#datas").html("TV offline" + " views : " + data.views + " followers : " + data.followers);
			})
			.fail(function(){
				$("#datas").html("il y a eu des erreurs...");
			});
		}
	})
	.fail(function(){
		$("#datas").html("il y a eu des erreurs...");
	});

}

function getFollow(username, channelname){	
	url = "https://api.twitch.tv/kraken/users/"+username+"/follows/channels/"+channelname;
	$.get(url, {format: "json"})
	.done(function(){
		$('#follow').attr("value", false).html("Ne plus suivre la chaîne !");						
	})
	.fail(function (){
		$('#follow').attr("value", true).html("Suivre la chaîne !");
	});	
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}
