<?php
if( !isset($gCms) ) exit;
//echo "Cool !";
//debug_display($params, 'Parameters');
$designation = '';//le message de fin....
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
	$user_email = $params['email'];
}
else
{
	$error++;
}
//echo $error;
if($error<1)
{
	//on fait le job
	//on ajoute le groupe
	$group_exists = $feu->GroupExistsByName('adherents');
	$feu->SetPreference('username_is_email',0);
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
	//on créé un mot de passe
	$mot1 = $this->random_string(8);
	$motdepasse = $mot1.'1';
	//qqs variables pour le mail
	$smarty->assign('prenom_joueur', $prenom);
	$smarty->assign('nom_joueur' , $nom);
	$smarty->assign('licence', $licence);
	//$motdepasse = 'UxzqsUIM1';
	$smarty->assign('motdepasse', $motdepasse);
	
	//$add_user = $feu->AddUser($licence, $motdepasse,$expires);
	$add_user = $feu->AddUser($licence, $motdepasse,$expires);
	
	//on récupère le userid ($uid)
	$uid = $feu->GetUserId($licence);
	$feu->ForcePasswordChange($uid, $flag = TRUE);	
	$gid = $feu->GetGroupId('adherents');
	/* on peut maintenant assigner cet utilisateur au groupe */
	$feu->AssignUserToGroup($uid,$gid);
	/* on remplit les propriétés de lutilisateur */
	$feu->SetUserPropertyFull('email',$user_email, $uid);
	$feu->SetUserPropertyFull('nom', $nom_complet,$uid);
	
	/* On essaie d'envoyer un message à l'utilisateur pour lui dire qu'il est enregistré */
	
	//$mail_alert = $this->send_mail_alerts($email);
	//echo $mail_alert;
	$query = "UPDATE ".cms_db_prefix()."module_commandes_clients SET account_validation = 1";
	$admin_email = $this->GetPreference('admin_email'); 
	//echo $to;
	$subject = $this->GetPreference('email_activation_subject');
	$message = $this->GetTemplate('newactivationemail_Sample');
	$body = $this->ProcessTemplateFromData($message);
	$headers = "From: ".$admin_email."\n";
	$headers .= "Reply-To: ".$admin_email."\n";
	$headers .= "Content-Type: text/html; charset=\"utf-8\"";
	/*
	$headers = 'From: claude.siohan@gmail.com' . "\r\n" . 'Reply-To: claude.siohan@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
	*/
	$designation.= 'Compte activé ! '.$prenom. ' peut commander en ligne !';
	if(mail($user_email, utf8_encode($subject), $body, $headers))
	{
		$query.=" , email_sent = 1";
		$designation.= ' Email envoyé !';
	}
	$query.=" WHERE email = ?";
	$dbresult = $db->Execute($query,array($user_email));
		$this->SetMessage($designation);
		$this->RedirectToAdminTab('clients');
	
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