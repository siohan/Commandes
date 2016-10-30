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
$smarty->assign('add_edit_items',
		$this->CreateLink($id, 'add_edit_item', $returnid,$contents='Ajouter un article'));
//formulaire de tri
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'articles')));
$smarty->assign('categorie', 
		$this->CreateInputDropdown($id,'categorie', $liste_categories, $selectedIndex=$key2,$selectedvalue=$index_categorie));
$smarty->assign('fournisseur', 
		$this->CreateInputDropdown($id,'fournisseur', $liste_fournisseurs,$selectedIndex=$key2_fournisseur,$selectedvalue=$fournisseur));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
//fin du formulaire de tri
$result= array ();
$query = "SELECT id AS item_id, categorie, fournisseur, reference, libelle, marque, prix_unitaire, reduction, statut_item FROM ".cms_db_prefix()."module_commandes_items";

if( isset($params['submitfilter'] ))
{
	$nb_filter = 0;//pour savoir si la req a des paramètres
	
	if(isset($params['categorie']) && $params['categorie'] != '' && $params['categorie'] != 'Tous')
	{
		$nb_filter++;
		
		$query.=" WHERE categorie LIKE ?";
		$parms['categorie'] = $params['categorie'];
		
	}
	if(isset($params['fournisseur']) && $params['fournisseur'] != '' && $params['fournisseur'] != 'TOUS')
	{
		$nb_filter++;
		if($nb_filter > 1)
		{
			$query.=" AND fournisseur LIKE ?";
		}
		else
		{
			$query.=" WHERE fournisseur LIKE ?";
		}
		
		$parms['fournisseur'] = $params['fournisseur'];
		
		
	}
	//on met de l'ordre qd même !
	$query.=" ORDER BY categorie ASC, fournisseur ASC, libelle ASC";
	
	if($nb_filter >0)
	{
		$dbresult= $db->Execute($query,$parms);
	}
	else
	{
		$dbresult= $db->Execute($query);
	}	
}
else
{
	$query .=" ORDER BY categorie ASC, fournisseur ASC, libelle ASC";
	//echo $query;
	$dbresult= $db->Execute($query);
}

/*
	$query .=" ORDER BY categorie ASC, fournisseur ASC, libelle ASC";
	//echo $query;
	$dbresult= $db->Execute($query);
*/	
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