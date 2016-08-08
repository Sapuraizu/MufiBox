<!--
	Crée par Sapuraizu par pur ennuie,
	vous pouvez réutiliser ce "projet" à titre personnel (Je vois pas ce que vous pourrez en faire autrement de toute manière.),
-->
<?php
	$uid = (isset($_GET['uid']) && !empty($_GET['uid'])) ? $_GET['uid'] : "";
	$token = (isset($_GET['token']) && !empty($_GET['token'])) ? $_GET['token'] : "";
?>
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
			</div>
			<div class="shoutbox-historic">
				<table id="historic" class="table table-hover table-condensed">
					<thead>
						<tr>
							<th>#</th>
							<th>User ID</th>
							<th>Username</th>
							<th>Message</th>
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

		<!-- Socket IO -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.min.js"></script>
		<script>
			connectToMufiShout("<?php echo $uid; ?>", "<?php echo $token; ?>", "http://shoutbox.mufibot.net:8080/");
			function connectToMufiShout(uid, token, url){
				var shoutbox = io.connect(url),
					isWritingMultipleLines = false;

				shoutbox.on('connect', function(){
					console.log('%cConnected.', 'color: #16a085');
				})

				shoutbox.on('disconnect', function(){
					console.log('%cDisconnected.', 'color: #e74c3c');
				})

				shoutbox.on('authentication required', function(){
					console.log('%cAuthentification required.', 'color: #e67e22');
					$('#state').html('<span class="text-orange">Authentification...</span>');
					shoutbox.emit('authenticate', {user_id: uid, token: token});
				})

				shoutbox.on('authentication success', function(data){
					console.log('%cAuthentification success.', 'color: #16a085');
					$('#state').html('<span class="text-green">Connecté.</span>');
					shoutbox.emit('get messages', {count: 100});
				})

				shoutbox.on('authentication failure', function(data){
					$('#state').html('<span class="text-red">Vous n\'êtes pas connecté.</span>').add;
					console.log('%cAuthentification failure.', 'color: #e74c3c');
				})

				shoutbox.on('not authenticated', function(data){
					console.log('%cNot authentificated.', 'color: #e74c3c');
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

				shoutbox.on('popup', function(data){
					alert(data.message);
				})

				function showMessage(data){
					var result = "",
						prefix = (data.deleted) ? "<span class=\"text-red\">(Supprimé)</span> " : "" || (data.edited) ? "<span class=\"text-red\">(Édité)</span> " : "";
					result += '<tr class="animated fadeIn">';
					result += '<td>'+ data.id +'</td>';
					result += '<td>'+ data.user_id +'</td>';
					result += '<td>'+ (data.username_link || '<a target="_blank" href="http://forum.mufibot.net/user-8385.html">[BOT] Stéphanie</a>') +'</td>';
					result += '<td>'+ prefix + data.message +'</td>';
					result += '<td>'+ data.timestamp +'</td>';
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
							message = _this.val();
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
			}	
		</script>
	</body>
</html>