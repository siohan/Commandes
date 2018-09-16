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
$tablesorter = '[[3,1]]';

$items_statut_commande = array('Tous'=>'Tous')+$items_statut_commande;
$shopping = '<img src="../modules/Paiements/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$smarty->assign('shopping', $shopping);

if(isset($params['statut_commande']))	
{
	$statut_commande = $params['statut_commande'];
	$key_statut_commande = array_values($items_statut_commande);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_statut_commande = array_search($statut_commande,$key_statut_commande);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_statut_commande = 0;
	$statut_commande = 'Tous';
}
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_cc_item', $returnid,$contents='Ajouter un article'));
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'commandesclients')));

$smarty->assign('statut_commande', 
		$this->CreateInputDropdown($id,'statut_commande', $items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
$result= array();
$parms = array();
$query = "SELECT it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande,it.commande_number FROM ".cms_db_prefix()."module_commandes_cc_items AS it WHERE it.commande <= '1'";

if( isset($params['submitfilter'] ))
{
	$nb_filter = 0;//pour savoir si la req a des paramètres
	
	
	if(isset($params['statut_commande']) && $params['statut_commande'] != '' && $params['statut_commande'] != 'Tous')
	{
		$query.=" AND cc.statut_commande LIKE ?";
		$parms['statut_commande'] = $params['statut_commande'];
		$nb_filter++;
		$query.= " ORDER BY date_created DESC";
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
	$query.= " ORDER BY date_created DESC";
	$dbresult= $db->Execute($query);
}
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$paiements_ops = new paiementsbis();
	$adh_ops = new adherents_spid;

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				//$id_commandes = $row['commande_id'];
				$statut = $row['commande'];
				$commande_number = $row['commande_number'];
				$onerow->client = $adh_ops->get_name($row['fk_id']);
				$onerow->date_created = $row['date_created'];
				$onerow->libelle_commande = $row['libelle_commande'];
				$onerow->categorie_produit = $row['categorie_produit'];
				$onerow->fournisseur = $row['fournisseur'];
				$onerow->quantite = $row['quantite'];
				$onerow->ep_manche_taille = $row['ep_manche_taille'];
				$onerow->couleur = $row['couleur'];
				$onerow->commande_number = $row['commande_number'];
				$onerow->prix_total = $row['prix_total'];
				//$onerow->statut = $row['statut_item'];
				$onerow->fournisseur = $row['fournisseur'];
				//$onerow->nom = $row['nom'];
				//$onerow->prenom = $row['prenom'];
				$onerow->date_created = $row['date_created'];
				
				if($statut >= 1)
				{
					$onerow->view= $this->createLink($id, 'view_cc', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['commande_number'])) ;
				}
				else
				{
					$onerow->is_paid = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
					$onerow->editlink= $this->CreateLink($id, 'add_edit_cc_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('active_tab'=>'commandesclients','record_id'=>$row['item_id']));
					$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'], "bdd"=>"cc_items"));
					
				}
				
				$onerow->statut = $row['commande'];
				//$onerow->paiement = $row['paiement'];
			//	$onerow->mode_paiement = $row['mode_paiement'];
				
				
			
					//$onerow->editlink= $this->CreateLink($id, 'add_edit_cc_item', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('active_tab'=>'commandesclients','record_id'=>$row['item_id']));
				//	$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['item_id'], "bdd"=>"cc_items"));
			
				
				
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		$smarty->assign('tablesorter',$tablesorter);
		//
		$smarty->assign('form2start',
				$this->CreateFormStart($id,'mass_action',$returnid));
		$smarty->assign('form2end',
				$this->CreateFormEnd());
		$articles = array("Changer le statut de la commande"=>"status","Changer le paiement"=>"paiement");
		$smarty->assign('actiondemasse',
				$this->CreateInputDropdown($id,'actiondemasse',$articles));
		$smarty->assign('submit_massaction',
				$this->CreateInputSubmit($id,'submit_massaction',$this->Lang('apply_to_selection'),'','',$this->Lang('areyousure_actionmultiple')));


echo $this->ProcessTemplate('view_cc_items.tpl');


#
# EOF
#
?>