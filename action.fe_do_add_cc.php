<?php
if( !isset($gCms) ) exit;
if(isset($params['client']) && $params['client'] != '')
{
	$client = $params['client'];
}
else
{
	$error++;
}

$fournisseur = '';
if(isset($params['fournisseur']) && $params['fournisseur'] != '')
{
	$fournisseur = $params['fournisseur'];
}
else
{
	$error++;
}
$commande_number = '';
if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
else
{
	$error++;
}

if($error < 1)
{
	$statut_commande = "En cours de traitement";
	$paiement = "Non payée";
	$prix_total = 0;
	$date_created = date('Y-m-d');
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cc (id, date_created, date_modified, client,fournisseur, prix_total, statut_commande, paiement, commande_number) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($date_created,$date_created,$client,$fournisseur,$prix_total, $statut_commande,$paiement,$commande_number));

	if($dbresult1)
	{
		$designation.=" Commande enregistrée, vous pouvez continuer";
	}
	else
	{
		$designation.= $db->ErrorMsg();
		echo $designation;
	}
	//$this->SetMessage($designation);
	$this->RedirectForFrontEnd($id, $returnid, 'default', array("display"=>"add_cc_items","commande_number"=>$commande_number,"fournisseur"=>$fournisseur));
	//$this->Redirect($id, 'default',$returnid,array("nom"=>$client, "date_created"=>$date_created,"fournisseur"=>$fournisseur));
}
else
{
	$this->RedirectForFrontEnd($id, $returnid, 'default', array("display"=>"default","client"=>$client, "date_created"=>$date_created,"fournisseur"=>$fournisseur));
}
#
# EOF
#
?>