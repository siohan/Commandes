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
if(isset($params['client']) && $params['client'] !='')
{
	$client = $params['client'];
}
if(isset($params['genid']) && $params['genid'] !='')
{
	$genid = $params['genid'];
	$adh_ops = new adherents_spid;
	$nom = $adh_ops->get_name($genid);
}


$rowclass2 = '';

	
	
$smarty->assign('nom', $nom);	


$result= array ();
$query1 = "SELECT it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande,it.commande_number FROM ".cms_db_prefix()."module_commandes_cc_items AS it WHERE  it.commande_number = ? ";


	//$query .=" ORDER BY id DESC";
	//echo $query;
	$dbresult= $db->Execute($query1,array($record_id));
	
	//echo $query;
	$rowarray= array();
	$rowarray2= array();
	$rowclass = '';
	$array_chpt = array();
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->item_id= $row['item_id'];
				$id_commandes = $row['fk_id'];
				$commande = $row['commande']; //gère si l'item doit être modifiable ou non
				$onerow->commande_id= $row['fk_id'];
				$onerow->commande_number= $row['commande_number'];
				$onerow->date_created = $row['date_created'];
				$onerow->libelle_commande = $row['libelle_commande'];
				$onerow->categorie_produit = $row['categorie_produit'];
				$onerow->fournisseur = $row['fournisseur'];
				//$onerow->prix_unitaire = $row['prix_unitaire'];
				$onerow->quantite = $row['quantite'];
				$onerow->ep_manche_taille = $row['ep_manche_taille'];
				$onerow->couleur = $row['couleur'];
				//$onerow->reduction = $row['reduction'];
				$onerow->prix_total = $row['prix_total'];
				$onerow->statut = $row['statut_item'];
				/*
				if($commande != '1')
				{
					//$onerow->view= $this->createLink($id, 'view_order', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'CC',"record_id"=>$row['fk_id'])) ;
					$onerow->editlink= $this->CreateLink($id, 'add_edit_cc_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('commande_number'=>$row['commande_number'],'record_id'=>$row['item_id'],"edit"=>"1"));
					$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'], "bdd"=>"cc_items", "commande_number"=>$row['commande_number']));
				}
				*/
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('view_order.tpl');


#
# EOF
#
?>