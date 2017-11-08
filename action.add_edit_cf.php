<?php
if( !isset($gCms) ) exit;


if (!$this->CheckPermission('Use Commandes'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;   
}

require_once(dirname(__FILE__).'/include/preferences.php');
if( isset($params['cancel']) )
{
    	$this->RedirectToAdminTab('commandesF');
    	return;
}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
$now = date('Y-m-d');
$statut_commande = 'En cours de traitement';//valeur par défaut

$commandes = new commandes_ops();
$liste_fournisseurs = $commandes->liste_fournisseurs();
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
		$query = "SELECT id_cf, date_created, fournisseur, statut_CF FROM ".cms_db_prefix()."module_commandes_cf AS cf WHERE  cf.id_cf = ?";
		$dbresult = $db->Execute($query, array($record_id));
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			
			$id_cf = $row['id_cf'];
			$date_created = $row['QJ468AZ21'];
			$fournisseur = $row['fournisseur'];
			$statut_CF = $row['statut_CF'];
		
		}
	}
if(isset($fournisseur))	
{
	$key_fournisseur = array_values($liste_fournisseurs);//$index_paiement = $paiement;
	//var_dump($key_paiement);
	$key2 = array_search($fournisseur,$key_fournisseur);
	//var_dump($key2);
}
else
{
	$key2 = 0;
	$fournisseur = "AUTRES";
}
if(isset($statut_CF))	
{
	$key_statut_CF = array_values($liste_statuts_commandes_fournisseurs);//$index_paiement = $paiement;
	//var_dump($key_mode_paiement);
	$key2_statut_CF = array_search($statut_CF,$key_statut_CF);
	//var_dump($key2_mode_paiement);
}
else
{
	$key2_statut_CF = 0;
	$statut_CF = "En cours de traitement";
}

	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_cf', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$id_cf));
		
	}
	
	$smarty->assign('edit',$edit);
	
	$smarty->assign('edition',
			$this->CreateInputHidden($id,'edition',$edit));

	

	
	$smarty->assign('date_created',
			$this->CreateInputDate($id, 'date_created',(isset($date_created)?$date_created:$now)));
	
	$smarty->assign('fournisseur',
			$this->CreateInputDropdown($id,'fournisseur',$liste_fournisseurs,$selectedIndex=$key2,$selectedvalue=$fournisseur));
	$smarty->assign('statut_CF',
			$this->CreateInputDropdown($id,'statut_CF',$liste_statuts_commandes_fournisseurs, $selectedIndex=$key2_statut_CF,$selectedvalue=$statut_CF));
				
		
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_cf.tpl');

#
# EOF
#
?>
