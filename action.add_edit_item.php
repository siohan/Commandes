<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('articles');
    		return;
  	}
//debug_display($params, 'Parameters');
require_once(dirname(__file__).'/include/preferences.php');
/*
$liste_fournisseurs = array("WACK SPORT"=>"WACK SPORT", "BUTTERFLY"=>"BUTTERFLY", "AUTRES"=>"AUTRES");
$liste_categories = array("BOIS"=>"BOIS","REVETEMENTS"=>"REVETEMENTS","AUTRES"=>"AUTRES");
*/
$liste_dispo = array("DISPONIBLE"=>"1","NON DISPONIBLE"=>"0");

$db =& $this->GetDb();
//s'agit-il d'une modif ou d'une créa ?
$record_id = '';
$index = 0;
if(isset($params['edit']) && $params['edit']=='1')
{
	$edit =1;
}
else
{
	$edit = 0;//pour savoir si on édite ou on créé, 0 par défaut c'est une créa
}
//
if(isset($params['commande_id']) && $params['commande_id'] != '')
{
	$commande_id = $params['commande_id'];
}

if(isset($params['record_id']) && $params['record_id'] !="")
	{
		$record_id = $params['record_id'];
		$edit = 1;//on est bien en trai d'éditer un enregistrement
		//ON VA CHERCHER l'enregistrement en question
		$query = "SELECT id, categorie, fournisseur, reference, libelle, marque, prix_unitaire, reduction, statut_item FROM ".cms_db_prefix()."module_commandes_items WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$categorie = $row['categorie'];
			$fournisseur = $row['fournisseur'];
			$reference = $row['reference'];
			$libelle = $row['libelle'];
			$marque = $row['marque'];
			$prix_unitaire = $row['prix_unitaire'];
			$reduction = $row['reduction'];
			$statut_item = $row['statut_item'];
				
		}
	}

	if(isset($fournisseur))	
	{
		$key_fournisseur = array_values($liste_fournisseurs);
		//var_dump($key_statut_commande);
		$key2_fournisseur = array_search($fournisseur,$key_fournisseur);
		//var_dump($key2_statut_commande);
	}
	else
	{
		$key2_fournisseur = 0;
		$fournisseur = "WACK SPORT";
	}
	if(isset($categorie))	
	{
		$key_categorie = array_values($liste_categories);
		//var_dump($key_statut_commande);
		$key2_categorie = array_search($categorie,$key_categorie);
		//var_dump($key2_statut_commande);
	}
	else
	{
		$key2_categorie = 0;
		$categorie = "REVETEMENTS"; //par défaut
	}
	if(isset($statut_item))
	{
		$key_statut_item = array_values($liste_dispo);
	}
	else
	{
		$statut_item = 1;
	}
	//$key = array_values($liste_fournisseur);
	 
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_item', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$record_id));
	}
	/*
	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id,'idepreuve',$type_compet,$selectedindex = $index, $selectedvalue=$name));
	*/
	$smarty->assign('categorie',
			$this->CreateInputDropdown($id, 'categorie',$liste_categories,$selectedIndex=$key2_categorie,$selectedvalue=$categorie));
	$smarty->assign('fournisseur',
			$this->CreateInputDropdown($id, 'fournisseur',$liste_fournisseurs,$selectedindex=$key2_fournisseur,$selectedalue=$fournisseur));
	$smarty->assign('reference',
			$this->CreateInputText($id,'reference',(isset($reference)?$reference:"000000"),7,10));
			
	$smarty->assign('libelle',
			$this->CreateInputText($id,'libelle',(isset($libelle)?$libelle:""),50,200));
			
			
	$smarty->assign('marque',
			$this->CreateInputText($id,'marque',(isset($marque)?$marque:""),30,150));
	$smarty->assign('prix_unitaire',
			$this->CreateInputText($id,'prix_unitaire',(isset($prix_unitaire)?$prix_unitaire:""),7,15));
	$smarty->assign('reduction',
			$this->CreateInputText($id,'reduction',(isset($reduction)?$reduction:""),5,10));					
	$smarty->assign('statut_item',
			$this->CreateInputDropdown($id,'statut_item',$liste_dispo,$selectedindex='',$selectedalue=$statut_item));	
				
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_item.tpl');

#
# EOF
#
?>
