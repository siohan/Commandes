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
$smarty->assign('add_edit_items',
		$this->CreateLink($id, 'add_edit_item', $returnid,$contents='Ajouter un article'));
//formulaire de tri
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'commandesclients')));
$smarty->assign('paiement', 
		$this->CreateInputDropdown($id,'paiement', $items_paiement, $selectedIndex=$key2,$selectedvalue=$index_paiement));
$smarty->assign('statut_commande', 
		$this->CreateInputDropdown($id,'statut_commande', $items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
//fin du formulaire de tri
$result= array ();
$query = "SELECT id AS item_id, categorie, fournisseur, reference, libelle, marque, prix_unitaire, reduction, statut_item FROM ".cms_db_prefix()."module_commandes_items";


	$query .=" ORDER BY categorie ASC, fournisseur ASC, libelle ASC";
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
				
				$onerow->item_id= $row['item_id'];
				$onerow->categorie = $row['categorie'];
				$onerow->fournisseur = $row['fournisseur'];
				$onerow->reference = $row['reference'];
				$onerow->libelle = $row['libelle'];
				$onerow->marque = $row['marque'];				
				$onerow->prix_unitaire = $row['prix_unitaire'];
				$onerow->reduction = $row['reduction'];
				$onerow->statut_item = $row['statut_item'];
				
				//$onerow->view= $this->createLink($id, 'view_item', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'CC',"record_id"=>$row['client_id'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['item_id']));
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'],'bdd'=>'item'));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		$smarty->assign('form2start',
				$this->CreateFormStart($id,'mass_action',$returnid));
		$smarty->assign('form2end',
				$this->CreateFormEnd());
		$articles = array("Changer la catégorie"=>"item_categorie","Changer le fournisseur"=>"item_fournisseur","Changer la marque"=>"item_marque","Changer le prix"=>"item_prix","Changer la réduction"=>"item_reduction","Changer le statut"=>"item_statut");
		$smarty->assign('actiondemasse',
				$this->CreateInputDropdown($id,'actiondemasse',$articles));
		$smarty->assign('submit_massaction',
				$this->CreateInputSubmit($id,'submit_massaction',$this->Lang('apply_to_selection'),'','',$this->Lang('areyousure_actionmultiple')));
		


echo $this->ProcessTemplate('items.tpl');


#
# EOF
#
?>