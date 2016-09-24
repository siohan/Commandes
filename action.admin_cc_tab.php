<?php
if( !isset($gCms) ) exit;
/*
if (!$this->CheckPermission('Ping Use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
*/
//require_once(dirname(__FILE__).'/include/prefs.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_cc', $returnid,$contents='Ajouter une commande client'));

$result= array();
$parms = array();
$query = "SELECT  cl.id,cc.id AS commande_id,cl.nom, cl.prenom, cl.club, cc.date_created, cc.prix_total, cc.statut_commande,cc.paiement, cc.mode_paiement FROM ".cms_db_prefix()."module_commandes_cc as cc, ".cms_db_prefix()."module_commandes_clients AS cl WHERE cl.id = cc.client ";

if (isset($params['statut_commande']) && $params['statut_commande'] != '')
{
	$statut_commande = $params['statut_commande'];
	$query.= " AND statut_commande = ?";
	$parms['statut_commande'] = $statut_commande;
	
}
	$query .=" ORDER BY cc.id DESC";
	//echo $query;
	$dbresult= $db->Execute($query,$parms);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				$id_commandes = $row['commande_id'];
				
				//on va chercher le nb d'articles de chq commande client
				$query2 = " SELECT count(*) AS nb_items, SUM(Prix_total) AS prix FROM ".cms_db_prefix()."module_commandes_cc_items WHERE fk_id = ?";
				$dbresult2 = $db->Execute($query2, array($id_commandes));
				if($dbresult2)
				{
					while($row2 = $dbresult2->FetchRow())
					{
						$onerow->nb_items = $row2['nb_items'];
						$onerow->prix = $row2['prix'];
					}
				}
				
				
				
				$onerow->commande_id= $row['commande_id'];
				$onerow->nom = $row['nom'];
				$onerow->prenom = $row['prenom'];
				$onerow->club = $row['club'];
				$onerow->date_created = $row['date_created'];
				//$onerow->prix_total = $row['prix_total'];
				$onerow->statut = $row['statut_commande'];
				$onerow->paiement = $row['paiement'];
				$onerow->mode_paiement = $row['mode_paiement'];
				$onerow->view= $this->createLink($id, 'view_cc', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['commande_id'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_cc', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['commande_id']));
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['commande_id'], "bdd"=>"cc"));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('cc.tpl');


#
# EOF
#
?>