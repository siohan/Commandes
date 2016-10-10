<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

//require_once(dirname(__FILE__).'/include/prefs.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$smarty->assign('add',
		$this->CreateLink($id, 'add_stock_item', $returnid,$contents='Ajouter un article dans le stock'));

$result= array ();
$query = "SELECT  id, id_items, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total FROM ".cms_db_prefix()."module_commandes_stock ";


	$query .=" ORDER BY id ASC";
	//echo $query;
	$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				//les champs disponibles : 
				
				
				//on va chercher le nb d'articles de chq commande client
				
				
				$id = $row['id'];
			//	echo "le client_id est : ".$client_id;
				
				$onerow->id= $row['id'];
				
				/*
				//on va chercher le nombre de commandes toutes confondues de chq client
				$query2 = "SELECT count(*) AS nb_commandes  FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ?";
				$dbresult2 = $db->Execute($query2, array($client_id));
				$row2 = $dbresult2->FetchRow();
				$nb_commandes = $row2['nb_commandes'];
				*/
				
				$onerow->id_items = $row['id_items'];
				$onerow->fk_id = $row['fk_id'];
				$onerow->libelle_commande = $row['libelle_commande'];
				$onerow->categorie_produit = $row['categorie_produit'];
				$onerow->fournisseur = $fournisseur;
				$onerow->quantite = $row['quantite'];				
				$onerow->ep_manche_taille = $row['ep_manche_taille'];
				$onerow->couleur = $row['couleur'];
				$onerow->prix_total = $row['prix_total'];
				/*
				$onerow->view= $this->createLink($id, 'view_client', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['client_id']));				
				$onerow->editlink= $this->CreateLink($id, 'add_edit_stock_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['client_id']));
				*/
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['client_id'], 'bdd'=>'stock'));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('stock.tpl');


#
# EOF
#
?>