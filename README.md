<p align="center"><img src="http://image.prntscr.com/image/ffb89563426c4dbebe869c12985e5378.png" alt="Sapuraizu"/></p>
# MufiBox
MufiBox est un outil permettant d'accéder à la shoutbox [MufiBot](http://forum.mufibot.net) sans avoir à ce rendre sur le forum, de plus, elle occupe une grande partie de l'écran et est facilement utilisable.

Quelques features de base sont actuellement disponibles, d'autres feront sûrement leur apparition avec le temps, à part une ou deux features, toutes les autres sont également disponibles sur le forum officiel.

Version d'essaie disponible : [Cliquez ici](http://mufibox.sapuraizu.netne.net/)

#Features actuelles
* Connexion à un compte (uid & token)
* Récupération de l'historique des messages
* Ne supprime pas les anciens messages
* Affichage des messages supprimés
* Envois de messages + commandes
* Envois de messages sur plusieurs lignes
* Affichage de l'heure où le message a été envoyé
* Suppression de ses propres messages
* Ajout des smileys

#Capture d'écran
![Capture d'écran](http://image.prntscr.com/image/dde2006d83484bceb95eae60c3f3a11f.png "Capture d'écran")

#Comment l'utiliser ?
Pour commencer, il vous faudra télécharger ce projet puis le déposer en local dans le dossier www/ de [wamp](http://www.wampserver.com/) (Si c'est ce que vous utilisez).

Une fois ça fais, vous devriez pouvoir y acceder depuis [http://localhost/MufiBox/](http://localhost/MufiBox/), mais vous aurez sûrement un message rouge "Vous n'êtes pas connecté." à la place de "Connecté." sur le screen ci-dessus, cependant, vous pourrez toujours voir les nouveaux shouts ! (Mais pas l'historique, il est disponible uniquement une fois connecté.)

Pour remedier à ça, rendez vous sur le [forum MufiBot](http://forum.mufibot.net/), connectez-vous, et utilisez une de ces 2 méthodes.

##Méthode 1:
Faîtes CTRL + U puis cherchez "socketshoutbox(".

![SocketShoubox](http://image.prntscr.com/image/0fb45fd2feb246ecbf55be7db981c7b8.png "UID & Token de connexion")

Voilà, vous avez votre UID (ici 8748) et votre TOKEN (ici ded9bfcbbccf1dd74940fc044ec1e99c) qui vous serviront à vous connecter via MufiBox !
Rendez-vous maintenant sur la page MufiBox, et ajoutez ?uid=VOTRE_UID&token=VOTRE_TOKEN dans le lien ! [http://localhost/MufiBox/?uid=VOTRE_UID&token=VOTRE_TOKEN](http://localhost/MufiBox/?uid=VOTRE_UID&token=VOTRE_TOKEN).

##Méthode 2:
Ouvrez la console à l'aide de F12, saisissez le code ci-dessous dedans.
```javascript
//Récupération des informations
var scriptContent = $('script[src="http://shoutbox.mufibot.net:8080/socket.io/socket.io.js"]').next().next().next().html(),
    step1 = scriptContent.substring(scriptContent.indexOf("socketshoutbox("), scriptContent.indexOf(":8080\")")),
    step2 = step1.split('(')[1].split(','),
    user_uid = step2[0].replace('"', '').replace('"', '').trim(),
    user_token = step2[1].replace('"', '').replace('"', '').trim(),
    getLink = "?uid="+ user_uid +"&token="+ user_token;
console.clear();
console.log("Votre UID : " + user_uid);
console.log("Votre TOKEN : " + user_token);
console.log("Votre LIEN : " + getLink);
```
(Si vous avez un problème du genre ".next is not a function", raffraîchissez votre page et recommencez.)
![ConsoleMethod](http://image.prntscr.com/image/267ec9d60db14276b04bbbd2ed2ec0e4.png "UID, TOKEN & Url de connexion")

Il ne vous reste qu'à récupérer le résultat ?uid=UID&token=TOKEN puis le mettre au bout de votre lien localhost (cf. Méthode 1)

/!\ Attention, le Token diffère à chaque nouvelle connexion sur le forum MufiBot, vous devrez recommencer la manip jusqu'à votre prochaine connexion. /!\
