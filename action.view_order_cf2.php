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
global $themeObject;
	
	if(!isset($params['record_id']) || $params['record_id'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	else
	{
		$record_id = $params['record_id'];
	}
	
	
//$commandes = new commandes_ops;	
$db = $this->GetDb();
//la requete pour aller chercher tous les articles commandés par les clients dans lea commande
$query = "SELECT it.id_CF,it.received, it.id_items,ccit.quantite, CONCAT_WS('-',ccit.libelle_commande,ccit.ep_manche_taille, ccit.couleur) AS commande, ccit.prix_total FROM ".cms_db_prefix()."module_commandes_cf_items AS it, ".cms_db_prefix()."module_commandes_cc_items AS ccit  WHERE it.id_items = ccit.id AND it.id_CF = ?";
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
			$received = $row['received'];
			$onerow->rowclass = $rowclass;
			$onerow->quantite = $row['quantite'];
			//$onerow->items_id = $row['items_id'];
			$onerow->commande = $row['commande'];
			$onerow->prix_total = $row['prix_total'];
			//$is_missing = $commandes->is_missing($record_id);
			if($received == '0')
			{
				//on "libère" le ou les article(s) non reçus
				$onerow->action = $themeObject->DisplayImage('icons/Notifications/1.gif', $this->Lang('missing_items'), '', '', 'systemicon');
				$onerow->delete = $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array("record_id"=>$row['id_items']));
				$onerow->received = $this->CreateLink($id, 'module_actions', $returnid,$themeObject->DisplayImage('icons/system/sort_down.gif', $this->Lang('received'), '', '', 'systemicon'), array("obj"=>"reçue", "record_id"=>$row['id_items']));
				//$onerow->action = $themeObject->DisplayImage('icons/Notifications/1.gif', $this->Lang('missing_items'), '', '', 'systemicon');

			}
			else
			{
				$onerow->action = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
			}		
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