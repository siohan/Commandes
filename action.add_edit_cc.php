<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('compets');
    		return;
  	}
//debug_display($params, 'Parameters');
require_once(dirname(__FILE__).'/include/preferences.php');
$db =& $this->GetDb();
$now = date('Y-m-d');
$statut_commande = 'En cours de traitement';//valeur par défaut

$mode_paiement = "Aucun";//Statut par défaut
/*
$items_statut_commande = array("En cours de traitement"=>"En cours de traitement", "Envoyée"=>"Envoyée", "Refusée"=>"Refusée", "Reçue"=>"Reçue");
$items_paiement = array("Non payée"=>"Non payée", "Payée"=>"Payée", "Refusée"=>"Refusée", "Reçue"=>"Reçue","Dotations"=>"Dotations");
$items_mode_paiement = array("Aucun"=>"Aucun","Chèque"=>"Chèque", "Espèces"=>"Espèces", "Autres"=>"Autres");
*/
//s'agit-il d'une modif ou d'une créa ?
$record_id = '';
$index = 0;
//var_dump($items_paiement);

$edit = 0;//pour savoir si on édite ou on créé, 0 par défaut c'est une créa



if(isset($params['record_id']) && $params['record_id'] !="")
	{
		$record_id = $params['record_id'];
		$edit = 1;//on est bien en trai d'éditer un enregistrement
		//ON VA CHERCHER l'enregistrement en question
		$query = "SELECT cc.id as index1, cc.date_created,cc.client,cc.prix_total, cc.libelle_commande,cc.statut_commande,cc.date_modified,cc.paiement, cc.mode_paiement,cc.remarques FROM ".cms_db_prefix()."module_commandes_cc AS cc, ".cms_db_prefix()."module_commandes_clients AS cl WHERE cc.client = cl.id AND cc.id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			
			$commande_id = $row['index1'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$client = $row['client'];
			$libelle_commande = $row['libelle_commande'];
			$prix_total = $row['prix_total'];
			$statut_commande = $row['statut_commande'];
			$paiement = $row['paiement'];
			//echo "le paiement est : ".$paiement;
			$mode_paiement = $row['mode_paiement'];
			
			$remarques = $row['remarques'];	
			$index = $row['index1'] - 1;
			
			
			
		}
	}
if(isset($paiement))	
{
	$key_paiement = array_values($items_paiement);//$index_paiement = $paiement;
	//var_dump($key_paiement);
	$key2 = array_search($paiement,$key_paiement);
	//var_dump($key2);
}
else
{
	$key2 = 0;
	$index_paiement = "AUCUN";
}
if(isset($mode_paiement))	
{
	$key_mode_paiement = array_values($items_mode_paiement);//$index_paiement = $paiement;
	//var_dump($key_mode_paiement);
	$key2_mode_paiement = array_search($mode_paiement,$key_mode_paiement);
	//var_dump($key2_mode_paiement);
}
else
{
	$key2_mode_paiement = 0;
	$mode_paiement = "Aucun";
}
if(isset($statut_commande))	
{
	$key_statut_commande = array_values($items_statut_commande);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_statut_commande = array_search($statut_commande,$key_statut_commande);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_statut_commande = 0;
}
	//on fait une requete pour completer l'input dropdown du formulaire
	$query = "SELECT id as client_id, CONCAT_WS(' ',nom, prenom) AS joueur FROM ".cms_db_prefix()."module_commandes_clients ORDER BY nom ASC, prenom ASC";
	$dbresult = $db->Execute($query);

		if($dbresult && $dbresult->RecordCount() >0)
		{
			while($row= $dbresult->FetchRow())
			{
				$nom[$row['joueur']] = $row['client_id'];
				//$indivs = $row['indivs'];
			}
		}

	//var_dump($nom);
			
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_cc', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$record_id));
		$smarty->assign('commande_id',
				$this->CreateInputHidden($id, 'commande_id',(isset($commande_id)?$commande_id:"")));
		
	}
	else
	{
		$smarty->assign('commande_id',
				$this->CreateInputText($id, 'commande_id',(isset($commande_id)?$commande_id:""),5,15));
		$smarty->assign('nom',
				$this->CreateInputDropdown($id,'nom',$nom,$selectedindex = $index, $selectedvalue=$nom));
		$smarty->assign('statut_commande',
				$this->CreateInputDropdown($id,'statut_commande',$items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
				$statut =0;
		
	}
	if($edit ==1 && $statut_commande != 'Reçue')
	{
		$smarty->assign('statut_commande',
				$this->CreateInputDropdown($id,'statut_commande',$items_statut_commande,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
		$statut = 0;
		
	}
	elseif($edit == 1 && $statut_commande == 'Reçue')
	{
		$smarty->assign('statut_commande',
				$this->CreateInputHidden($id,'statut_commande',$statut_commande));
		$statut = 1;
		
	}
	
	$smarty->assign('statut', $statut);
	$smarty->assign('edit',$edit);
	
	$smarty->assign('edition',
			$this->CreateInputHidden($id,'edition',$edit));

	

	
	$smarty->assign('date_created',
			$this->CreateInputDate($id, 'date_created',(isset($date_created)?$date_created:$now)));
	$smarty->assign('libelle_commande',
			$this->CreateInputText($id,'libelle_commande',(isset($libelle_commande)?$libelle_commande: ""),50,100));
	
	$smarty->assign('paiement',
			$this->CreateInputDropdown($id,'paiement',$items_paiement, $selectedIndex=$key2,$selectedvalue=$index_paiement));
	$smarty->assign('mode_paiement',
			$this->CreateInputDropdown($id,'mode_paiement',$items_mode_paiement,$selectedIndex=$key2_mode_paiement,$selectedvalue = $mode_paiement));		
	$smarty->assign('remarques',
			$this->CreateInputText($id,'remarques',(isset($remarques)?$remarques:''),15,150));			
		
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_cc.tpl');

#
# EOF
#
?>
