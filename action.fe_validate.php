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
	
	$query2 = "SELECT cc.commande_number,it.fournisseur, it.categorie_produit, it.libelle_commande, it.quantite, it.ep_manche_taille, it.couleur, it.prix_total FROM ".cms_db_prefix()."module_commandes_cc AS cc, ".cms_db_prefix()."module_commandes_cc_items AS it WHERE cc.commande_number = it.commande_number AND cc.commande_number = ?";
	$dbresult2 = $db->Execute($query2, array($commande_number));
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
	
/**/
}
$smarty->assign('items', $rowarray);
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('commande_number', $commande_number);
$user_email = $feu->LoggedInEmail();
$admin_email = $adherents->GetPreference('admin_email'); 
//echo $to;
$subject = $adherents->GetPreference('new_command_subject');
$message = $adherents->GetTemplate('newcommandemail_Sample');
$body = $adherents->ProcessTemplateFromData($message);
$headers = "From: ".$user_email."\n";
$headers .= "Reply-To: ".$admin_email."\n";
$headers .= "Content-Type: text/html; charset=\"utf-8\"";

$cmsmailer = new \cms_mailer();
$cmsmailer->reset();
$cmsmailer->AddAddress($email);
$cmsmailer->SetBody($body);
$cmsmailer->SetSubject($this->Lang('new_command_subject'));
$cmsmailer->IsHTML(true);
$cmsmailer->SetPriority(1);
$cmsmailer->Send();
$this->Redirect($id, 'default', $returnid, array("display"=>"validation_message", "commande_number"=>$commande_number));
