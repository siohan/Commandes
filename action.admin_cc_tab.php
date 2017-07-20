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
		$this->CreateLink($id, 'add_edit_cc', $returnid,$contents='Ajouter une commande client'));
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'commandesclients')));

$smarty->assign('statut_commande', 
		$this->CreateInputDropdown($id,'statut_commande', $items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
$result= array();
$parms = array();
$query = "SELECT  cl.licence,cc.id AS commande_id,cc.commande_number,cl.nom, cl.prenom, cc.date_created, cc.prix_total, cc.statut_commande,cc.fournisseur FROM ".cms_db_prefix()."module_commandes_cc as cc, ".cms_db_prefix()."module_adherents_adherents AS cl WHERE cl.licence = cc.client AND cc.user_validation = 1 ";

if( isset($params['submitfilter'] ))
{
	$nb_filter = 0;//pour savoir si la req a des paramètres
	
	
	if(isset($params['statut_commande']) && $params['statut_commande'] != '' && $params['statut_commande'] != 'Tous')
	{
		$query.=" AND cc.statut_commande LIKE ?";
		$parms['statut_commande'] = $params['statut_commande'];
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
	
	$dbresult= $db->Execute($query);
}
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$paiements_ops = new paiementsbis();

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				$id_commandes = $row['commande_id'];
				$statut = $row['statut_commande'];
				$commande_number = $row['commande_number'];
				//$paiement = $row['paiement'];
				
				//on va chercher le nb d'articles de chq commande client
				$query2 = " SELECT count(*) AS nb_items, SUM(prix_total) AS prix FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
				$dbresult2 = $db->Execute($query2, array($commande_number));
				if($dbresult2)
				{
					while($row2 = $dbresult2->FetchRow())
					{
						$onerow->nb_items = $row2['nb_items'];
						
						if($row2['nb_items'] == 0 || is_null($row2['prix']))
				//	include '../Paiements/action.admin_paiements_tab.php';
						{
							$onerow->prix = '0.00';
						}
						else
						{
							$onerow->prix = $row2['prix'];
						}
					}
				}
				
				
				$onerow->commande_number= $row['commande_number'];
				$onerow->commande_id= $row['commande_id'];
				$onerow->fournisseur = $row['fournisseur'];
				$onerow->nom = $row['nom'];
				$onerow->prenom = $row['prenom'];
				$onerow->date_created = $row['date_created'];
				if($statut == "Reçue")
				{
					$is_paid = $paiements_ops->is_paid($row['commande_number']);
					//var_dump($is_paid);
					if(true === $is_paid)
					{
						$onerow->is_paid = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
					}
					else
					{
							$false = false;
							$onerow->is_paid = $false;
					}
				
				}
				else
				{
					$onerow->is_paid = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
				}
				
				$onerow->statut = $row['statut_commande'];
				//$onerow->paiement = $row['paiement'];
				//$onerow->mode_paiement = $row['mode_paiement'];
				$onerow->view= $this->createLink($id, 'view_cc', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"commande_number"=>$row['commande_number'])) ;
				
				if($statut !="Payée et déstockée")
				{
					$onerow->editlink= $this->CreateLink($id, 'add_edit_cc', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('active_tab'=>'commandesclients','commande_number'=>$row['commande_number']));
					$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('commande_number'=>$row['commande_number'], "bdd"=>"cc"));
				}
				
				
				
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


echo $this->ProcessTemplate('cc.tpl');


#
# EOF
#
?>