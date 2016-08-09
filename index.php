<!--
	Crée par Sapuraizu par pur ennuie,
	vous pouvez réutiliser ce "projet" à titre personnel (Je vois pas ce que vous pourrez en faire autrement de toute manière.),
-->
<!DOCTYPE html>
<html>
	<head>
		<title>MufiBox</title>
		<meta charset="UTF-8"/>

		<!-- CSS -->
		<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/bootstrap/bootstrap-theme.min.css"/>
		<link rel="stylesheet" href="css/fontawesome/fontawesome.min.css"/>
		<link rel="stylesheet" href="css/animate/animate.css"/>
		<link rel="stylesheet" href="css/style.css"/>
	</head>
	<body>
		<div class="container">
			<div class="logo-text"><h1>MufiBox <small id="state">Non connecté</small></h1></div>
			<div class="form-group">
				<textarea id="snd_msg" class="form-control" type="text" placeholder="Saisissez votre message et appuyez sur entrer pour valider"></textarea>
				<small class="input-msg">Vous pouvez également <a href="javascript:void(0)" data-type="snd_cmd" data-command="/plant">poser une bombe</a>, défier quelqu'un au /shifumute ou <a href="javascript:void(0)" data-type="snd_cmd" data-command="/help">avoir plus d'informations</a>.</small>
				<small class="pull-right" data-type="toggleSmileys"><a href="javascript:void(0)" data-default-text="(Afficher les smileys)" data-toggle-text="(Masquer les smileys)">(Masquer les smileys)</a></small>
			</div>
			<div class="form-group well" id="smileys"></div>
			<div class="shoutbox-historic">
				<table id="historic" class="table table-hover table-condensed">
					<thead>
						<tr>
							<!--<th>#</th>-->
							<th>Username</th>
							<th colspan="2">Message</th>
							<th>Date d'envois</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<!-- JavaScript -->
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/javascript.js"></script>
		<script type="text/javascript" src="js/moments.js"></script>

		<!-- Socket IO -->
		<?php
			$uid = (isset($_GET['uid']) && !empty($_GET['uid'])) ? $_GET['uid'] : "";
			$token = (isset($_GET['token']) && !empty($_GET['token'])) ? $_GET['token'] : "";
			echo '<script>var uid = "'. $uid .'", token = "'. $token .'";</script>';
		?>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.min.js"></script>
		<script>
			connectToMufiShout(uid, token, "http://shoutbox.mufibot.net:8080/");
			function connectToMufiShout(uid, token, url){
				var shoutbox = io.connect(url),
					isWritingMultipleLines = false,
					windowIsActive = true,
					unReadMessages = 0;

				shoutbox.on('connect', function(){
					console.log('%cConnecté au serveur MufiBot.', 'color: #16a085');
				})

				shoutbox.on('disconnect', function(){
					console.log('%cDéconnecté du serveur MufiBot.', 'color: #e74c3c');
				})

				shoutbox.on('authentication required', function(){
					console.log('%cConnexion requise.', 'color: #e67e22');
					$('#state').html('<span class="text-orange">Authentification...</span>');
					shoutbox.emit('authenticate', {user_id: uid, token: token});
				})

				shoutbox.on('authentication success', function(data){
					console.log('%cConnexion effecutée.', 'color: #16a085');
					$('#state').html('<span class="text-green">Connecté.</span>');
					shoutbox.emit('get messages', {count: 100});
				})

				shoutbox.on('authentication failure', function(data){
					$('#state').html('<span class="text-red">Vous n\'êtes pas connecté.</span>').add;
					console.log('%cEchec de la connexion.', 'color: #e74c3c');
				})

				shoutbox.on('not authenticated', function(data){
					console.log('%cVous n\'êtes pas connecté.', 'color: #e74c3c');
				})

				shoutbox.on('messages list', function(data){
					console.log('%cHistorique des messages récupéré.', 'color: #16a085');
					//console.log(data);
					$.each(data, function(index, value){
						showMessage(value);
					})
				})

				shoutbox.on('new message', function(data){
					//console.log(data);
					showMessage(data);
				})

				shoutbox.on('delete message', function(data){
					$('tr[data-mid="'+ data.id +'"] td[data-id="content"]').prepend('<span class="text-red">(Supprimé)</span> ');
				})

				shoutbox.on('popup', function(data){
					alert(data.message);
				})

				function showMessage(data){
					var result = "",
						prefix = (data.deleted) ? "<span class=\"text-red\">(Supprimé)</span> " : "" || (data.edited) ? "<span class=\"text-red\">(Édité)</span> " : "",
						rowColor = "";
					
					if(!windowIsActive)
						document.title = "("+ ++unReadMessages +") - Mufibox";

					if(unReadMessages == 1)
						rowColor = "bg-gray";

					result += '<tr class="animated fadeIn '+ rowColor +'" data-mid="'+ data.id +'">';
					//result += '<td>'+ data.id +'</td>';
					result += '<td><span data-type="talk-to" data-uid="'+ data.user_id +'">@</span>'+ (data.username_link || '<a target="_blank" href="http://forum.mufibot.net/user-8385.html">[BOT] Stéphanie</a>') +'</td>';
					result += '<td data-id="content">'+ prefix + data.message +'</td>';
					if(data.user_id == uid)
						result += '<td class="text-right"><i data-type="msg_delete" class="action-icon fa fa-trash"></i> <i data-type="msg_edit" class="action-icon fa fa-pencil"></i></td>';
					else
						result += '<td></td>';
					result += '<td class="text-right">'+ moment.unix(data.timestamp).format('HH:mm:ss') +'</td>';
					result += '</tr>';
					$('#historic tbody').prepend(result);
				}

				/*
					KeyCode:
						Shift: 16
						Enter: 13
				*/

				$(document).on('keydown', '#snd_msg', function(e){
					if(e.keyCode == 16) isWritingMultipleLines = true;
				})

				$(document).on('keyup', '#snd_msg', function(e){
					if(!isWritingMultipleLines && e.keyCode == 13){
						var _this = $(this),
							message = _this.val().trim();
						_this.val('');
						shoutbox.emit('new message', {message: message});
					} else if(e.keyCode == 16) {
						isWritingMultipleLines = false;
					}
				})

				$(document).on('click', '[data-type="snd_cmd"]', function(){
					var command = $(this).attr('data-command');
					shoutbox.emit('new message', {message: command});
				})

				$(document).on('click', '[data-type="talk-to"]', function(){
					var _this = $(this),
						uid = _this.attr('data-uid'),
						username = _this.next().text().trim(),
						userprofile = _this.next().attr('href'),
						talkTo = '[url='+ userprofile +']@'+username+'[/url] ';
					$('#snd_msg').val(talkTo).focus();
				})

				$(document).on('click', '[data-type="msg_delete"]', function(){
					var _this = $(this),
						mid = parseInt(_this.closest('tr').attr('data-mid'));
					shoutbox.emit('delete message', {id: mid});
				})

				$(window).on('focus', function(){
					windowIsActive = true;
					unReadMessages = 0;
					document.title = "Mufibox";
				})

				$(window).on('blur', function(){
					windowIsActive = false;
				})
			}	
		</script>
	</body>
</html>