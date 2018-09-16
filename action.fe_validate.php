<?php
if( !isset($gCms)) exit;
//debug_display($params,'Parameters');
$feu = cms_utils::get_module('FrontEndUsers');
$userid = $feu->LoggedInId();
$username = $feu->GetUserName($userid);

$db =& $this->GetDb();
debug_display($params, 'Parameters');
if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}
$adherents = new adherents;
$query = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET user_validation =  1 WHERE id = ? AND fk_id = ?";
$dbresult= $db->Execute($query, array($record_id, $username));
if($dbresult)
{
	
	//on va chercher les infos pour les mettre dans le message au gestionnaire des commandes
	
	$query2 = "SELECT it.fournisseur, it.categorie_produit, it.libelle_commande, it.quantite, it.ep_manche_taille, it.couleur, it.prix_total FROM ".cms_db_prefix()."module_commandes_cc_items AS it WHERE id = ? AND fk_id = ?";
	$dbresult2 = $db->Execute($query2, array($record_id, $username));
	$rowclass= 'row1';
	$rowarray= array();
	if(!$dbresult2)
	{
		echo $this->ErrorMsg();
	}
	else
	{
		while($row = $dbresult2->FetchRow())
		{
			$onerow= new StdClass();
			$onerow->rowclass= $rowclass;
			$onerow->fournisseur = $row['fournisseur'];		
			$onerow->categorie_produit = $row['categorie_produit'];			
			$onerow->libelle_commande = $row['libelle_commande'];		
			$onerow->quantite = $row['quantite'];		
			$onerow->ep_manche_taille = $row['ep_manche_taille'];		
			$onerow->couleur = $row['couleur'];		
			$onerow->prix_total = $row['prix_total'];
			$rowarray[]= $onerow;
		
		}
		

		
	}
	$commandes = new commandes;
	$smarty->assign('items', $rowarray);
	$smarty->assign('itemcount', count($rowarray));
	//$smarty->assign('commande_number', $commande_number);
	$user_email = $feu->LoggedInEmail();
	$admin_email = $commandes->GetPreference('admin_email'); 
	//echo $to;
	$subject = $commandes->GetPreference('new_command_subject');
	$message = $commandes->GetTemplate('newcommandemail_Sample');
	
	$body = $commandes->ProcessTemplateFromData($message);
	var_dump($body);
	$headers = "From: ".$user_email."\n";
	$headers .= "Reply-To: ".$admin_email."\n";
	$headers .= "Content-Type: text/html; charset=\"utf-8\"";

	$cmsmailer = new \cms_mailer();
	$cmsmailer->reset();
	$cmsmailer->AddAddress($user_email);
	$cmsmailer->SetBody($body);
	$cmsmailer->SetSubject($this->Lang('new_command_subject'));
	$cmsmailer->IsHTML(true);
	$cmsmailer->SetPriority(1);
	$cmsmailer->Send();
	$this->Redirect($id, 'default', $returnid, array("display"=>"fe_commandes"));
}
else
{
	echo "pas cool !";
}
