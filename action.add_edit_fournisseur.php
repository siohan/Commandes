<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('commandesclients');
    		return;
  	}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
//s'agit-il d'une modif ou d'une créa ?
$record_id = '';
$index = 0;
$libelle = '';
$actif = 0;
$service = new commandes_ops();
$liste_fournisseurs = $service->liste_fournisseurs();
if(isset($params['edit']) && $params['edit']=='1')
{
	$edit =1;
}
else
{
	$edit = 0;//pour savoir si on édite ou on créé, 0 par défaut c'est une créa
}

if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;//on est bien en trai d'éditer un enregistrement
		//ON VA CHERCHER l'enregistrement en question
		$query = "SELECT * FROM ".cms_db_prefix()."module_commandes_fournisseurs WHERE id = ? ORDER BY ordre ASC";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$nom_fournisseur = $row['nom_fournisseur'];
			//$commande_number = $row['commande_number'];
			$description = $row['description'];
			$actif = $row['actif'];
			$ordre = $row['ordre'];
		}
}
$OuiNon = array('Oui'=>'1', 'Non'=>'0');	
	
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_fournisseur', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$record_id));
		
	}
	
	
	$smarty->assign('nom_fournisseur',
			$this->CreateInputText($id,'nom_fournisseur',(isset($nom_fournisseur)?$nom_fournisseur:""),50,200));
	$smarty->assign('description',
			$this->CreateInputText($id,'description',(isset($description)?$description:""),50,200));
			
	$smarty->assign('actif',
			$this->CreateInputDropdown($id,'actif',$OuiNon,$selectedindex = $index, $selectedvalue=$actif));
	$smarty->assign('ordre',
			$this->CreateInputText($id,'ordre',(isset($ordre)?$ordre:""),50,200));

	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_fournisseur.tpl');

#
# EOF
#
?>
