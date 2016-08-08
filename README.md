# MufiBox
MufiBox est un outil permettant d'accéder à la shoutbox [MufiBot](http://forum.mufibot.net) sans avoir à ce rendre sur le forum, de plus, elle occupe une grande partie de l'écran et est facilement utilisable.

Quelques features de base sont actuellement disponibles, d'autres feront sûrement leur apparition avec le temps, à part une ou deux features, toutes les autres sont également disponibles sur le forum officiel.

#Features actuelles
* Connexion à un compte (uid & token)
* Récupération de l'historique des messages
* Ne supprime pas les anciens messages
* Affichage des messages supprimés
* Envois de messages + commandes

#Capture d'écran
![Capture d'écran](http://image.prntscr.com/image/1c23412e2c0d4151973da4774441af0c.png "Capture d'écran")

#Comment l'utiliser ?
Pour commencer, il vous faudra télécharger ce projet puis le déposer en local dans le dossier www/ de [wamp](http://www.wampserver.com/) (Si c'est ce que vous utilisez).

Une fois ça fais, vous devriez pouvoir y acceder depuis [http://localhost/MufiBox/](http://localhost/MufiBox/), mais vous aurez sûrement un message rouge "Vous n'êtes pas connecté." à la place de "Connecté." sur le screen ci-dessus, cependant, vous pourrez toujours voir les nouveaux shouts ! (Mais pas l'historique, il est disponible uniquement une fois connecté.)

Pour remedier à ça, rendez vous sur le [forum MufiBot](http://forum.mufibot.net/), connectez-vous, et faîtes CTRL + U puis cherchez "socketshoutbox(".

![SocketShoubox](http://image.prntscr.com/image/0fb45fd2feb246ecbf55be7db981c7b8.png "UID & Token de connexion")

Voilà, vous avez votre UID (ici 8748) et votre TOKEN (ici ded9bfcbbccf1dd74940fc044ec1e99c) qui vous serviront à vous connecter via MufiBox !
Rendez-vous maintenant sur la page MufiBox, et ajoutez ?uid=VOTRE_UID&token=VOTRE_TOKEN dans la lien ! [http://localhost/MufiBox/?uid=VOTRE_UID&token=VOTRE_TOKEN](http://localhost/MufiBox/?uid=VOTRE_UID&token=VOTRE_TOKEN).

/!\ Attention, le Token diffère à chaque nouvelle connexion sur le forum MufiBot, vous devrez recommencer la manip jusqu'à votre prochaine connexion. /!\
