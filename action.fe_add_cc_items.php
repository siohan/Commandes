<?php
if( !isset($gCms) ) exit;
$db =& $this->GetDb();
$record_id = '';
$edit = 0; //par défaut il s'agit d'un ajout
if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
	$edit = 1;
	//ON VA CHERCHER l'enregistrement en question
	$query = "SELECT it.id AS item_id,items.id AS index1, it.fk_id ,it.commande_number, it.date_created,it.libelle_commande,it.fournisseur,it.couleur, it.ep_manche_taille, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item FROM ".cms_db_prefix()."module_commandes_cc_items AS it, ".cms_db_prefix()."module_commandes_items AS items  WHERE it.libelle_commande LIKE items.libelle  AND it.id = ?  ORDER BY items.categorie, items.libelle ASC";
	$dbresult = $db->Execute($query, array($record_id));
	$compt = 0;
	while ($dbresult && $row = $dbresult->FetchRow())
	{
		$compt++;
		$item_id = $row['item_id'];
		$commande_id = $row['fk_id'];
		//$commande_number = $row['commande_number'];
		$date_created = $row['date_created'];
		$libelle_commande = $row['libelle_commande'];
		$fournisseur = $row['fournisseur'];
		$categorie_produit = $row['categorie_produit'];
		$fournisseur = $row['fournisseur'];
		$quantite = $row['quantite'];
		$prix_total = $row['prix_total'];
		$statut_item = $row['statut_item'];
		$ep_manche_taille = $row['ep_manche_taille'];
		$couleur = $row['couleur'];
		$produit_final = $categorie_produit.'-'.$libelle_commande;
	}
	
}

$commande_number = '';
if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
if(isset($params['fournisseur']) && $params['fournisseur'] != '')
{
	$fournisseur = $params['fournisseur'];
}

//on fait une requete pour completer l'input dropdown du formulaire
$query = "SELECT CONCAT_WS('-', categorie, libelle) AS libelle_form,libelle  FROM ".cms_db_prefix()."module_commandes_items WHERE statut_item = '1' AND fournisseur = ? ORDER BY categorie ASC,fournisseur ASC, libelle ASC";
$dbresult = $db->Execute($query, array($fournisseur));

	if($dbresult) 
	{
		if($dbresult->RecordCount() >0)
		{
			$a = 0;
			while($row= $dbresult->FetchRow())
			{
				$a++;
				
				$libelle[$row['libelle_form']] = $row['libelle_form'];
			
				//echo $a;
				if(isset($libelle_commande) && $row['libelle_form'] == $libelle_commande)
				{
					$index = $a-1;
				}
			}
		}
	}
	else
	{
		echo "erreur de requete";
	}
	//echo $produit_final;
	
			
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('fe_add_cc_item.tpl'),null,null,$smarty);
	$tpl->assign('form_start',$this->CGCreateFormStart($id,'fe_do_add_cc_items',$returnid,$params,$inline));
	
	if($edit == 1)
	{
		$tpl->assign('libelle_selected', $produit_final);
	}
	
	$tpl->assign('record_id', $record_id);// c'est l'id de chq article
	$tpl->assign('commande_number', $commande_number);
	$tpl->assign('produits',$libelle);
	//var_dump($libelle);
	$tpl->assign('fournisseur', $fournisseur);//$liste_fournisseurs);
	$tpl->assign('ep_manche_taille', $ep_manche_taille);
	$tpl->assign('couleur', $couleur);
	$tpl->assign('display', 'do_add_cc_items');
	//$tpl->assign('categorie_produit', $categorie_produit);
	$tpl->assign('quantite', $quantite);
	//$tpl->assign('prix_unitaire', $prix_unitaire);
	//$tpl->assign('reduction', $reduction);
	//$tpl->assign('statut_item', $statut_item);
	
	$tpl->assign('form_end', $this->CreateFormEnd());
	$tpl->display();

# EOF
#
?>
