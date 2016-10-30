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
if(isset($params['nom']) && isset($params['date_created']) )
{
	$nom = $params['nom'];
	$date_created = $params['date_created'];
	$fournisseur = $params['fournisseur'];
	//on va chercher la commande
	$query3 = "SELECT id, statut_commande, fournisseur FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ? AND date_created = ? AND fournisseur = ?";
	$dbresult3 = $db->Execute($query3, array($nom, $date_created,$fournisseur));
	
	if($dbresult3 && $dbresult3->RecordCount() >0)
	{
		$row3 = $dbresult3->FetchRow();
		$record_id = $row3['id'];
		$fournisseur = $row3['fournisseur'];
	}
	
	
	
}
//echo "le record_id est :".$record_id;
//on va afficher le nom du oropriétaire de la commande
$rowclass2 = '';
$query2 =  "SELECT cl.nom, cc.statut_commande,cl.prenom,cc.date_created,cc.fournisseur FROM ".cms_db_prefix()."module_commandes_cc AS cc, ".cms_db_prefix()."module_commandes_clients AS cl WHERE cl.id = cc.client AND cc.id = ?";
$dbresult2 = $db->Execute($query2, array($record_id));
	
	
	if($dbresult2 && $dbresult2->RecordCount()>0)
	{
		while($row2 = $dbresult2->FetchRow())
		{
			$statut_commande = $row2['statut_commande'];
			$nom= $row2['nom'];
			$fournisseur = $row2['fournisseur'];
			$prenom= $row2['prenom'];
			$date_created= $row2['date_created'];
			$smarty->assign('nom',$nom);
			$smarty->assign('prenom',$prenom);
			$smarty->assign('date_created',$date_created);
		}
	}
	
	
$smarty->assign('status', $statut_commande);	
$smarty->assign('add_edit_cc_item',
		$this->CreateLink($id, 'add_edit_cc_item', $returnid,$contents='Ajouter un article à cette commande', array("commande_id"=>$record_id,"edit"=>"0","fournisseur"=>$fournisseur)));

$result= array ();
$query1 = "SELECT cc.id,it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande FROM ".cms_db_prefix()."module_commandes_cc as cc, ".cms_db_prefix()."module_commandes_cc_items AS it WHERE cc.id = it.fk_id AND cc.id = ? ";


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
				
				if($commande != '1')
				{
					//$onerow->view= $this->createLink($id, 'view_order', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'CC',"record_id"=>$row['fk_id'])) ;
					$onerow->editlink= $this->CreateLink($id, 'add_edit_cc_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['item_id'],"edit"=>"1"));
					$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'], "bdd"=>"cc_items"));
				}
				
				
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