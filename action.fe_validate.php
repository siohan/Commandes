<?php
if( !isset($gCms)) exit;


$db =& $this->GetDb();
//debug_display($params, 'Parameters');
if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
$adherents = new adherents();
$query = "UPDATE ".cms_db_prefix()."module_commandes_cc SET user_validation =  1 WHERE commande_number = ?";
$dbresult= $db->Execute($query, array($commande_number));
if($dbresult)
{
	
	//on va chercher les infos pour les mettre dans le message au gestionnaire des commandes
	
	$query2 = "SELECT cc.commande_number FROM ".cms_db_prefix()."module_commandes_cc AS cc, ".cms_db_prefix()."module_commandes_cc_items AS it WHERE cc.commande_number = it.commande_number AND cc.commande_number = ?";
	$dbresult2 = $db->Execute($query2, array($commande_number));
	if(!$dbresult2)
	{
		echo $this->ErrorMsg();
	}
	$row = $dbresult2->FetchRow();
	$commande_number2 = $row['commande_number'];
	//echo $commande_number2;
	
	$smarty->assign('commande_number', $commande_number);
	$user_email = $feu->LoggedInEmail();
	$admin_email = $adherents->GetPreference('admin_email'); 
	//echo $to;
	$subject = $adherents->GetPreference('new_command_subject');
	$message = $adherents->GetTemplate('newcommandemail_Sample');
	$body = $adherents->ProcessTemplateFromData($message);
	$headers = "From: ".$user_email."\n";
	$headers .= "Reply-To: ".$user_email."\n";
	$headers .= "Content-Type: text/html; charset=\"utf-8\"";
	mail($admin_email, $subject, $body, $headers);
	
}

$this->Redirect($id, 'default', $returnid, array("display"=>"validation_message", "commande_number"=>$commande_number));
