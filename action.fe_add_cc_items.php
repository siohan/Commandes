<?php
if( !isset($gCms) ) exit;
$db =& $this->GetDb();
$record_id = '';
$edit = 0; //par défaut il s'agit d'un ajout
$commande_number = '';
if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
$fournisseur = '';
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
				if(isset($libelle_commande) && $row['libelle'] == $libelle_commande)
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

			
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('fe_add_cc_item.tpl'),null,null,$smarty);
	$tpl->assign('form_start',$this->CGCreateFormStart($id,'fe_do_add_cc_items',$returnid,$params,$inline));
	$tpl->assign('record_id', $item_id);// c'est l'id de chq article
	$tpl->assign('commande_number', $commande_number);
	$tpl->assign('produits',$libelle);
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
