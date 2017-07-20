<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

//require_once(dirname(__FILE__).'/include/preferences.php');
$service = new commandes_ops();
$liste_fournisseurs = $service->liste_fournisseurs();
$liste_fournisseurs = array("TOUS"=>"TOUS")+$liste_fournisseurs;
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$liste_categories = array('Tous'=>'Tous')+$liste_categories;
//
if(isset($params['categorie']))	
{
	$categorie = $params['categorie'];
	$key_categorie = array_values($liste_categories);//$index_paiement = $paiement;
	//var_dump($key_paiement);
	$key2 = array_search($categorie,$key_categorie);
	//var_dump($key2);
}
else
{
	$key2 = 0;
	$index_category = 'Tous';
}

if(isset($params['fournisseur']))	
{
	$fournisseur = $params['fournisseur'];
	$key_fournisseur = array_values($liste_fournisseurs);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_fournisseur = array_search($fournisseur,$key_fournisseur);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_fournisseur = 0;
	$fournisseur = 'Tous';
}
//
$smarty->assign('add_edit_fournisseur',
		$this->CreateLink($id, 'add_edit_fournisseur', $returnid,$contents='Ajouter un catalogue fournisseur'));
$result= array ();
$query = "SELECT * FROM ".cms_db_prefix()."module_commandes_fournisseurs";


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
				
				$onerow->id= $row['id'];
				$onerow->nom_fournisseur = $row['nom_fournisseur'];
				$onerow->description = $row['description'];
				$onerow->actif = $row['actif'];
				$onerow->ordre = $row['ordre'];				
				
				//$onerow->view= $this->createLink($id, 'view_item', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'CC',"record_id"=>$row['client_id'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_fournisseur', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				//$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'],'bdd'=>'item'));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('fournisseurs.tpl');


#
# EOF
#
?>