<?php
if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
require_once(dirname(__FILE__).'/include/preferences.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
/*
	deux cas : 
	1 - directement après la soumission d'une commande
	2 - Depuis le recap admin
	
*/
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
}
else
{
	// pas de record_id, tu dégages !!
}
$rowarray= array();
$rowclass = '';
$rowclass2 = '';
$query =  "SELECT cl.nom, cc.statut_commande,cc.id AS commande_id,cc.libelle_commande,cc.prix_total,cc.paiement, cc.mode_paiement,cc.remarques,cl.prenom,cc.date_created FROM ".cms_db_prefix()."module_commandes_cc AS cc, ".cms_db_prefix()."module_commandes_clients AS cl WHERE cl.id = cc.client AND cl.id = ?";
$dbresult = $db->Execute($query, array($record_id));
	
	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$onerow= new StdClass();
			$onerow->rowclass= $rowclass;
			$onerow->commande_id = $row['commande_id'];
			$onerow->date_created = $row['date_created'];
			$onerow->libelle_commande = $row['libelle_commande'];
			$onerow->statut_commande = $row['statut_commande'];
			$onerow->prix_total = $row['prix_total'];
			$onerow->paiement = $row['paiement'];
			$onerow->mode_paiement = $row['mode_paiement'];
			$onerow->remarques = $row['remarques'];
			$onerow->view= $this->createLink($id, 'view_cc', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['commande_id'])) ;
			
			$smarty->assign('nom', $row['nom']);
			$smarty->assign('prenom', $row['prenom']);
			($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
			$rowarray[]= $onerow;
		}
	}
	
		
	$smarty->assign('add_edit_cc_item',
			$this->CreateLink($id, 'add_edit_cc_item', $returnid,$contents='Ajouter un article à cette commande', array("commande_id"=>$record_id,"edit"=>"0")));
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray));
	$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('view_client_orders.tpl');


#
# EOF
#
?>