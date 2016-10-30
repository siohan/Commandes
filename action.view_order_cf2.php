<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$designation = '';
$fournisseur = '';
$record_id = '';
$rowarray = array();

	
	if(!isset($params['record_id']) || $params['record_id'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	else
	{
		$record_id = $params['record_id'];
	}
	
	
	
$db = $this->GetDb();
//la requete pour aller chercher tous les articles commandés par les clients dans lea commande
$query = "SELECT it.id_CF, it.id_items,ccit.quantite, CONCAT_WS('-',ccit.libelle_commande,ccit.ep_manche_taille, ccit.couleur) AS commande, ccit.prix_total FROM ".cms_db_prefix()."module_commandes_cf_items AS it, ".cms_db_prefix()."module_commandes_cc_items AS ccit  WHERE it.id_items = ccit.id AND it.id_CF = ?";
//echo $query;
//$query.="  ORDER BY commande ASC ";
$dbresult = $db->Execute($query,array($record_id));

	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	$lignes = $dbresult->RecordCount();
	//echo "<br />le nb de lignes est : ".$lignes."<br />";;

	if($dbresult && $dbresult->RecordCount()>0)
	{
		$rowarray = array();
		$rowclass = '';
		while($row = $dbresult->FetchRow())
		{
			$onerow = new StdClass();
			$onerow->rowclass = $rowclass;
			$onerow->quantite = $row['quantite'];
			//$onerow->items_id = $row['items_id'];
			$onerow->commande = $row['commande'];
			$onerow->prix_total = $row['prix_total'];		
			($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
			$rowarray[]= $onerow;
		}
		//print_r($rowarray);
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
			
	}

/**/
echo $this->ProcessTemplate('view_order_cf2.tpl');
#
#EOF
#
?>