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
if(isset($params['edit']) && $params['edit']=='1')
{
	$edit =1;
}
else
{
	$edit = 0;//pour savoir si on édite ou on créé, 0 par défaut c'est une créa
}
if(isset($params['commande_id']) && $params['commande_id'] != '')
{
	$commande_id = $params['commande_id'];
}

if(isset($params['record_id']) && $params['record_id'] !="")
	{
		$record_id = $params['record_id'];
		$edit = 1;//on est bien en trai d'éditer un enregistrement
		//ON VA CHERCHER l'enregistrement en question
		$query = "SELECT it.id AS item_id,items.id AS index1, it.fk_id , it.date_created,it.libelle_commande,it.couleur, it.ep_manche_taille, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item FROM ".cms_db_prefix()."module_commandes_cc_items AS it, ".cms_db_prefix()."module_commandes_items AS items  WHERE it.libelle_commande LIKE items.libelle  AND it.id = ?  ORDER BY items.categorie, items.libelle ASC";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$item_id = $row['item_id'];
			$commande_id = $row['fk_id'];
			$date_created = $row['date_created'];
			$libelle_commande = $row['libelle_commande'];
			$categorie_produit = $row['categorie_produit'];
			$fournisseur = $row['fournisseur'];
			$quantite = $row['quantite'];
			$prix_total = $row['prix_total'];
			$statut_item = $row['statut_item'];
			$ep_manche_taille = $row['ep_manche_taille'];
			$couleur = $row['couleur'];
				
			
			
		}
	}
	
	//on fait une requete pour completer l'input dropdown du formulaire
	$query = "SELECT CONCAT_WS('-', categorie, libelle) AS libelle_form,libelle  FROM ".cms_db_prefix()."module_commandes_items WHERE statut_item = '1' ORDER BY categorie ASC, libelle ASC";
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
			    $this->CreateFormStart( $id, 'do_add_cc_item', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$item_id));
	}
	/*
	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id,'idepreuve',$type_compet,$selectedindex = $index, $selectedvalue=$name));
	*/
	$smarty->assign('commande_id',
			$this->CreateInputHidden($id, 'commande_id',(isset($commande_id)?$commande_id:"")));
	$smarty->assign('date_created',
			$this->CreateInputDate($id, 'date_created',(isset($date_created)?$date_created:"")));
	$smarty->assign('libelle_commande',
			$this->CreateInputDropdown($id,'libelle_commande',$libelle,$selectedindex = $index, $selectedvalue=$libelle));
			
	$smarty->assign('ep_manche_taille',
			$this->CreateInputText($id,'ep_manche_taille',(isset($ep_manche_taille)?$ep_manche_taille:""),50,200));
			
	$smarty->assign('couleur',
			$this->CreateInputText($id,'couleur',(isset($couleur)?$couleur:""),50,200));
			

			
			
	$smarty->assign('categorie_produit',
			$this->CreateInputText($id,'categorie_produit',(isset($categorie_produit)?$categorie_produit:""),30,150));
	$smarty->assign('quantite',
			$this->CreateInputText($id,'quantite',(isset($quantite)?$quantite:""),5,10));
	$smarty->assign('prix_unitaire',
			$this->CreateInputText($id,'prix_unitaire',(isset($prix_unitaire)?$prix_unitaire:""),5,10));		
	$smarty->assign('reduction',
			$this->CreateInputText($id,'reduction',(isset($reduction)?$reduction:""),5,10));			
	$smarty->assign('statut_item',
			$this->CreateInputText($id,'statut_item',(isset($statut_item)?$statut_item:""),5,10));	
				
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_cc_item.tpl');

#
# EOF
#
?>
