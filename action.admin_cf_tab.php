<?php


if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

require_once(dirname(__FILE__).'/include/preferences.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$items_statut_commande = array('Tous'=>'Tous')+$items_statut_commande;
$liste_fournisseurs = array("TOUS"=>"TOUS")+$liste_fournisseurs;
//var_dump($liste_fournisseurs);
if(isset($params['fournisseur']))	
{
	$fournisseur = $params['fournisseur'];
	$key_fournisseur = array_values($liste_fournisseurs);//$index_paiement = $paiement;
	//var_dump($key_paiement);
	$key2 = array_search($fournisseur,$key_fournisseur);
	//var_dump($key2);
}
else
{
	$key2 = 0;
	$fournisseur = 'Tous';
}
if(isset($params['statut_CF']))	
{
	$statut_CF = $params['statut_CF'];
	$key_statut_CF = array_values($liste_statuts_commandes_fournisseurs);//$index_paiement = $paiement;
	//var_dump($key_mode_paiement);
	$key2_statut_CF = array_search($statut_CF,$key_statut_CF);
	//var_dump($key2_mode_paiement);
}
else
{
	$key2_statut_CF = 0;
	$statut_CF = "Tous";
}
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_cf', $returnid,$contents='Ajouter une commande fournisseur', array("edit"=>"0")));
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'commandesfournisseurs')));
$smarty->assign('fournisseur', 
		$this->CreateInputDropdown($id,'fournisseur', $liste_fournisseurs,$selectedIndex=$key2,$selectedvalue=$fournisseur));
$smarty->assign('statut_CF', 
		$this->CreateInputDropdown($id,'statut_CF', $items_statut_commande,$selectedIndex=$key2_statut_CF,$selectedvalue=$statut_CF));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
$result= array();
$query = "SELECT *  FROM ".cms_db_prefix()."module_commandes_cf WHERE date_created > '1970-01-01'";

	if( isset($params['submitfilter'] ))
	{
		$nb_filter = 0;//pour savoir si la req a des paramètres
		
		if(isset($params['fournisseur']) && $params['fournisseur'] != '' && $params['fournisseur'] != 'TOUS')
		{
			$query.=" AND fournisseur LIKE ?";
			$parms['fournisseur'] = $params['fournisseur'];
			$nb_filter++;
		}
		if(isset($params['statut_CF']) && $params['statut_CF'] != '' && $params['statut_CF'] != 'Tous')
		{
			$query.=" AND statut_CF LIKE ?";
			$parms['statut_CF'] = $params['statut_CF'];
			$nb_filter++;
		}
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
		//$query .=" ORDER BY id DESC";
		//echo $query;
		$dbresult= $db->Execute($query);
	}
	
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				$id_cf = $row['id_CF'];
				$statut_CF = $row['statut_CF'];
				//on va chercher le nb d'articles et le prix total de la CF
				$onerow->id_cf= $row['id_CF'];
				$onerow->date_created= $row['date_created'];
				$onerow->fournisseur= $row['fournisseur'];
				$onerow->statut_CF= $row['statut_CF'];
				
				//on va chercher le nb d'articles et pourquoi pas le prix total
				
				$query2 = "SELECT count(*) AS nb_items, SUM(prix_total) AS total_commande FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
				$dbresult2 = $db->Execute($query2,array($id_cf));
				
				if($dbresult2 && $dbresult2->RecordCount()>0)
				{
					$row2 = $dbresult2->FetchRow();
					$nb_items = $row2['nb_items'];
					$total_commande = $row2['total_commande'];
				}
				else
				{
					$nb_items = 0;
					$total_commande = '0.00';
				}
				
				$onerow->nb_items = $nb_items;
				$onerow->total_commande = $total_commande;
				$onerow->view= $this->createLink($id, 'view_order_cf2', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('__active_tab'=>'commandesfournisseurs','fournisseur'=>$row['fournisseur'],"record_id"=>$row['id_CF'])) ;
				
				if($statut_CF != "Reçue")
				{
					$onerow->view= $this->createLink($id, 'view_order_cf', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('__active_tab'=>'commandesfournisseurs','fournisseur'=>$row['fournisseur'],"record_id"=>$row['id_CF'])) ;
					$onerow->editlink= $this->CreateLink($id, 'add_edit_cf', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id_CF']));
					$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['id_CF'], 'bdd'=>'cf','nb_items'=>$nb_items));
				}
				
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('cf.tpl');


#
# EOF
#
?>