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


$index = 0;

	
	//on fait une requete pour completer l'input dropdown du formulaire
	$query = "SELECT CONCAT_WS('-', categorie, libelle) AS libelle_form,libelle  FROM ".cms_db_prefix()."module_commandes_items ORDER BY categorie ASC, libelle ASC";
	$dbresult = $db->Execute($query);

		if($dbresult && $dbresult->RecordCount() >0)
		{
			$a = 0;
			while($row= $dbresult->FetchRow())
			{
				$a++;
				//$libelle = $row['libelle_form'];
				$libelle[$row['libelle_form']] = $row['libelle'];
				/*
				$type_compet[$row['name']] = $row['idepreuve'];
				$indivs = $row['indivs'];
				*/
				//echo $a;
				if(isset($libelle_commande) && $row['libelle'] == $libelle_commande)
				{
					$index = $a-1;
				}
			}
		}

	/**/
			
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_stock_item', $returnid ) );
	;
	$smarty->assign('libelle_commande',
			$this->CreateInputDropdown($id,'libelle_commande',$libelle,$selectedindex = $index, $selectedvalue=$libelle));
			
	$smarty->assign('ep_manche_taille',
			$this->CreateInputText($id,'ep_manche_taille',(isset($ep_manche_taille)?$ep_manche_taille:""),50,200));
			
	$smarty->assign('couleur',
			$this->CreateInputText($id,'couleur',(isset($couleur)?$couleur:""),50,200));
			
	$smarty->assign('quantite',
			$this->CreateInputText($id,'quantite',(isset($quantite)?$quantite:""),5,10));
	
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_stock_item.tpl');

#
# EOF
#
?>
