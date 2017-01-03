<?php
if( !isset($gCms) ) exit;
//echo "Cool !";
//debug_display($params, 'Parameters');
$feu = cms_utils::get_module('FrontEndUsers');
//$feu = new FrontEndUsers();
$error = 0;//on instancie un compteur d'erreurs
// variables à contrôler :  l'email , la licence
if(isset($params['nom']) && $params['nom'] !='')
{
	$nom = $params['nom'];
}
if(isset($params['prenom']) && $params['prenom'] !='')
{
	$prenom = $params['prenom'];
}
$nom_complet = $prenom. ' '.$nom;
if(isset($params['licence']) && $params['licence'] !='')
{
	$licence = $params['licence'];
}
else
{
	$error++;
}
if(isset($params['email']) && $params['email'] !='')
{
	$email = $params['email'];
}
else
{
	$error++;
}
echo $error;
if($error<1)
{
	//on fait le job
	//on ajoute le groupe
	$group_exists = $feu->GroupExistsByName('adherents');
	
	if(FALSE === $group_exists)
	{
		$feu->AddGroup('adherents', 'les adhérents du club');
		
		/* On récupère l'id du group créé à savoir adherents */
		$gid = $feu->GetGroupId('adherents');
		
		/*
		on créé les propriétés de ce groupe à savoir
		1- Ton email
		2- Ton nom
		*/
		$name = "email";
		$prompt = "Ton email";
		$type = 2;
		$length = 80;
		$maxlength = 255;		
		$feu->AddPropertyDefn($name, $prompt, $type, $length,$maxlength,$attribs = '', $force_unique = 0, $encrypt = 0 );
		
		$sortkey = 0;
		$required = 2; //2= requis, 1 optionnel, 0 = off
		/* on peut assigner les propriétés au groupe adhérents */
		$feu->AddGroupPropertyRelation($gid,$name,$sortkey, -1, $required);
		
		/*On fait la même chose pour la deuxième propriété */
		$name = "nom";
		$prompt = "Ton nom";
		$type = 0;
		$length = 80;
		$maxlength = 255;		
		$feu->AddPropertyDefn($name, $prompt, $type, $length,$maxlength,$attribs = '', $force_unique = 0, $encrypt = 0 );
		
		$sortkey = 1;
		$required = 2; //2= requis, 1 optionnel, 0 = off
		/* on peut assigner les propriétés au groupe adhérents */
		$feu->AddGroupPropertyRelation($gid,$name,$sortkey, -1, $required);
		
		
		
	}
	
	$day = date('j');
	$month = date('n');
	$year = date('Y')+5;
	$expires = mktime(0,0,0,$month, $day,$year);
	
	$feu->AddUser($licence, 'UxzqsUIM1',$expires);
	//on récupère le userid ($uid)
	$uid = $feu->GetUserId($licence);
	$feu->ForcePasswordChange($uid, $flag = TRUE);	
	$gid = $feu->GetGroupId('adherents');
	/* on peut maintenant assigner cet utilisateur au groupe */
	$feu->AssignUserToGroup($uid,$gid);
	/* on remplit les propriétés de lutilisateur */
	$feu->SetUserPropertyFull('email',$email, $uid);
	$feu->SetUserPropertyFull('nom', $nom_complet,$uid);
	
	/* On essaie d'envoyer un message à l'utilisateur pour lui dire qu'il est enregistré */
	
	
	$subject = "Ping : ton accès pour commander en ligne";
	$body = "<p>".$nom_complet.",</p>
	<p>Notre club possède </p>
	<p>identifiant : ton numéro de licence</p>
	<p>Ton mdp provisoire : UxzqsUIM1</p>
	<p>Important : lors de ta prochaine connexion, le système te demandera de changer de mot de passe : celui-ci devra contenir entre 6 et 20 caractères et au moins un chiffre</p>";

	# Send the mail
		$to      = 'claude.siohan@gmail.com';
		     $subject = 'le sujet';
		     $message = 'Bonjour !';
		     $headers = 'From: claude@agi-webconseil.fr' . "\r\n" .
		     'Reply-To: claude@agi-webconseil.fr' . "\r\n" .
		     'X-Mailer: PHP/' . phpversion();

		     mail($to, $subject, $message, $headers);
		$this->SetMessage('adhérent ajouté !');
		$this->Redirect($id, 'defaultadmin', $returnid);
	
}
else
{
	//les conditions ne sont pas remplis, on renvoit à la page précédente
	//echo $error;
	$this->SetMessage('parametres manquants');
	$this->Redirect($id, 'defaultadmin',$returnid);
}


# EOF
#

?>