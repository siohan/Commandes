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
$smarty->assign('add_edit_items',
		$this->CreateLink($id, 'add_edit_item', $returnid,$contents='Ajouter un article'));

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
		


echo $this->ProcessTemplate('items.tpl');


#
# EOF
#
?>