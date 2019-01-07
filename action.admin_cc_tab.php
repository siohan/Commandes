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
/*
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_cc_item', $returnid,$contents='Ajouter un article'));
*/
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'commandesclients')));

$smarty->assign('statut_commande', 
		$this->CreateInputDropdown($id,'statut_commande', $items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
$result= array();
$parms = array();
$query = "SELECT date_created,client,genid, libelle_commande, statut_commande,commande_number, fournisseur, prix_total, paiement FROM ".cms_db_prefix()."module_commandes_cc";
$query.= " ORDER BY date_created DESC";
$dbresult= $db->Execute($query);

	
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
				$statut_commande = $row['statut_commande'];
				$commande_number = $row['commande_number'];
				$onerow->client = $adh_ops->get_name($row['genid']);
				$onerow->date_created = $row['date_created'];
				$onerow->libelle_commande = $row['libelle_commande'];				
				$onerow->commande_number = $row['commande_number'];
				$onerow->prix_total = $row['prix_total'];
				$onerow->statut_commande = $statut_commande;
				$onerow->fournisseur = $row['fournisseur'];
			
				if($statut_commande != "Non envoyée")
				{
					$is_paid = $paiements_ops->is_paid($row['commande_number']);
				
					if(true === $is_paid)
					{
						$onerow->is_paid = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
					}
					else
					{
						$false = false;
						$onerow->is_paid = $false;//$this->CreateLink($id, 'add_edit_reglement', $returnid, $shopping, array('record_id'=>$row['commande_number']));//$false;//$themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
					}
					$onerow->view= $this->createLink($id, 'view_cc', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['commande_number'], "genid"=>$row['genid'])) ;
				}
				else
				{
					$onerow->is_paid = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
				}		
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		$smarty->assign('tablesorter',$tablesorter);
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