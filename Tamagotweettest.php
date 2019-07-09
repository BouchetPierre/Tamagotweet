<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link href="https://fonts.googleapis.com/css?family=Bungee+Inline&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="tama.css">
    <title>TAMAGOTWEET</title>
  </head>
  <header>
    <h1>TAMAGOTWEET</h1>
  </header>
  <body>
      <div class="envoi">
        <form  class="envoiForm" method="post">
          <textarea class="envoiText" type="text" name="tweet" value="Ecrir le tweet"></textarea>
          <input id="bouton" class="envoiInput"type="submit" name="compteur" value="Publier le tweets" >
        </form>
      </div>
<!----------------------------------------------------------------------->
      <div  class="container">
        <img id="sky" src="tama.gif" alt="">
      </div>
<!----------------------------------------------------------------------->
<!--insertion modal----------------------------------->
      <div id="id01" class="w3-modal">
        <div class="w3-modal-content w3-animate-zoom">
          <div class="w3-container">
            <span onclick="enlever()"
            class="w3-button w3-display-topright">&times;</span>
            <p>Tu tweet trop déconnecte toi bruuhh!</p>
            <img id="attention" src="images/hendek.png" alt="">
          </div>
        </div>
      </div>

<!--insertion modal----------------------------------->
<div class="hero">


        <?php
/*---------fonction authentification-----------------------------*/
function Authenti(){
  require_once('TwitterAPIExchange.php');

  return array('oauth_access_token'=>'3238659609-6X9uGUkxpYhmqQSQqqix4Ewc2uTPdD9nskI45F8',/*On retourne la réponse de l'API*/
  'oauth_access_token_secret'=>'QEY4nk2YMueFDACd5nVZiqGkdT8HI5LiW4dKHoi7ZiYth',
  'consumer_key'=>'w3S89uW36ocTHuxTG7nfCjoVK',
  'consumer_secret'=>'fHcG0TZCbF8KRPl7uhyKi1lVhJxQnGm3tc3C2XcXgCVOsjbgjJ');

}
/*-----fin----fonction authentification-------------------------------*/

/*---------Sauvegarde du nombre de tweet---et fonction "vomis"-----------------------------*/

              $nbTweet=0;
               if(!empty($_POST['compteur'])) {
                      $monfichier = fopen("/var/www/html/MesProjetsPHP/MesExos/TamagoTweet/SauvegardeTamago.txt", 'r+');
                      $nbTweet =(int)fgets($monfichier); // On lit la première ligne
                      if ($nbTweet<7){
                      $nbTweet += 1; // On augmente de 1
                      fseek($monfichier, 0); // On remet le curseur au début du fichier
                      fputs($monfichier, $nbTweet); // On écrit le nouveau nombre
                      fclose($monfichier);
                    }else if($nbTweet==7){
                      $nbTweet=0; // On initialise a 0
                      fseek($monfichier, 0); // On remet le curseur au début du fichier
                      fputs($monfichier, $nbTweet); // On écrit le 0
                      fclose($monfichier);

                      $settings=Authenti();

                      $url = "https://api.twitter.com/1.1/statuses/user_timeline.json"; /*creation de l'adresse HTTP*/

                      $requestMethod = "GET";

                      $getfield = "?screen_name=kaa_sha&count=5";

                      $twitter = new TwitterAPIExchange($settings);

                      $str = json_decode(
                                      $twitter -> setGetfield($getfield)
                                      ->buildOauth($url, $requestMethod)
                                      ->performRequest(), $assoc=TRUE);

                          if (array_key_exists("errors", $str)){
                            echo "Vous avez une erreur:".$str[errors];
                          }

                          $i=0;
                          $textvomis;
                          foreach($str as $items){
                                  $textTweet=$items['text'];
                                  $lesMots = explode(" ", $textTweet);
                                  if (isset($lesMots[$i])){
                                  echo "<font class=\"vomis\" style =\"font-size:100px; margin-left:15px; \">$lesMots[$i]</font>";
                                  $textvomis[]=$lesMots[$i];
                                  $i++;
                                }
                            }
      /*---------tweet _du "vomis"-----------------------------*/
                          $textvomis2=implode(" ",$textvomis);

                          $settings=Authenti();

                          $url = "https://api.twitter.com/1.1/statuses/update.json";
                          $requestMethod = "POST";
                          $postfields = ["status"=>"$textvomis2"]; /*Ecrire un tweet*/
                          $twitter = new TwitterAPIExchange($settings);
                           $twitter->buildOauth($url, $requestMethod)
                                       ->setPostfields($postfields)
                                       ->performRequest();

                    }else{
                      $nbTweet=0; // On initialise a 0
                      fseek($monfichier, 0); // On remet le curseur au début du fichier
                      fputs($monfichier, $nbTweet); // On écrit le 0
                      fclose($monfichier);
                    }
                 }

  /*--fin-------Sauvegarde du nombre de tweet---et fonction "vomis"-----------------------------*/

  /*--------post des tweet----------------------------------------------------------------------*/
            if (isset($_POST["tweet"])){
            $text=$_POST["tweet"];

            $settings=Authenti();

            /*construction URL pour envoi de tweet*/
            $url = "https://api.twitter.com/1.1/statuses/update.json";
            $requestMethod = "POST";
            $postfields = ["status"=>"$text"]; /*Ecrire un tweet*/
            $twitter = new TwitterAPIExchange($settings);
             $twitter->buildOauth($url, $requestMethod)
                         ->setPostfields($postfields)
                         ->performRequest();
            }
/*---fin-----post des tweet----------------------------------------------------------------------*/

/*--------Gestion de la taille du Tamago---------------------------------------------------------------------*/
        $taille=150;
       if(!empty($_POST['compteur'])) {
           $monfichierTaille = fopen("/var/www/html/MesProjetsPHP/MesExos/TamagoTweet/SauvegardeTamagoTaille.txt", 'r+');
           $taille =(int) fgets($monfichierTaille); // On lit la première ligne
           if ($taille<850){
           $taille = $taille + 100; // On augmente de 1
           fseek($monfichierTaille, 0); // On remet le curseur au début du fichier
           fputs($monfichierTaille, $taille); // On écrit le nouveau nombre
           fclose($monfichierTaille);
         }else{
           $taille=150; // On initialise a 0
           fseek($monfichierTaille, 0); // On remet le curseur au début du fichier
           fputs($monfichierTaille, $taille); // On écrit le 0
           fclose($monfichierTaille);
        }
      }
/*--fin------Gestion de la taille du Tamago---------------------------------------------------------------------*/

        ?>
  </div>

<!--partie du code en JS pour la gestion de l'affichage------------------------------------------------------------------->

        <script>
/*--------Gestion de la taille du Tamago--JS-------------------------------------------------------------------*/
        var taille = <?php echo json_encode($taille); ?>;
        console.log (taille);
        var myImg = document.getElementById("sky");

    		if(taille==950){
                myImg.style.height= 150 + "px";
                myImg.style.width= 150 + "px";

    		} else {
                myImg.style.height = taille + "px";
                myImg.style.width =  taille + "px";
                myImg.src ="images/tama.gif";
    		}
/*--fin------Gestion de la taille du Tamago--JS-------------------------------------------------------------------*/

/*--------Gestion du madal JS------------------------------------------------------------------*/
        var nbtweet = <?php echo json_encode($nbTweet); ?>;
        console.log (nbtweet);
        // Get the modal
        var modal = document.getElementById("id01");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        if (nbtweet ==5){
        modal.style.display = "block";
        <?php
        $message="Tu tweet trop";                             /*envoi d'un mail*/
        mail('bouchet.hp@gmail.com', 'Alert Tweet',$message); ?>;
        }
        if (nbtweet==7){
          myImg.src ="images/tama_explosion.gif";
          document.getElementById("bouton").value= "RETRY";
          document.getElementById("bouton").classList.add("retry");
        }

      // When the user clicks on <span> (x), close the modal
        function enlever() {
          modal.style.display = "none";
        }
/*---fin-----Gestion du madal JS------------------------------------------------------------------*/
      </script>
  </body>
</html>
