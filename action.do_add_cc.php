<?php

if( !isset($gCms) ) exit;
/*
	if (!$this->CheckPermission('Ping Manage'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}
*/
	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('compets');
    		return;
  	}

debug_display($params, 'Parameters');
$db =& $this->GetDb();
$now = date('Y-m-d');
$designation = '';//le message final
$error = 0;//on initie un compteur d'erreur, 0 par défaut

if(isset($params['edition']) && $params['edition'] != '')
{
	$edit = $params['edition'];
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
}

if(isset($params['commande_id']) && $params['commande_id'] != '')
{
	$commande_id = $params['commande_id'];
}

if(isset($params['date_created']) && $params['date_created'] != '')
{
	$date_created = $params['date_created'];
}

if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}
if(isset($params['nom']) && $params['nom'] != '')
{
	$client = $params['nom'];
}
$libelle_commande = '';
if(isset($params['libelle_commande']) && $params['libelle_commande'] != '')
{
	$libelle_commande = $params['libelle_commande'];
}

$prix_total = 0;
if(isset($params['prix_total']) && $params['prix_total'] != '')
{
	$prix_total = $params['prix_total'];
}


if(isset($params['statut_commande']) && $params['statut_commande'] != '')
{
	$statut_commande = $params['statut_commande'];
}
else
{
	$statut_commande = 'En cours de traitement' ;
}

if(isset($params['paiement']) && $params['paiement'] != '')
{
	$paiement = $params['paiement'];
}
else
{
	$paiement = 'Non payé';
}

if(isset($params['mode_paiement']) && $params['mode_paiement'] != '')
{
	$mode_paiement = $params['mode_paiement'];
}
else
{
	$mode_paiement = 'Aucun';
}

$remarques = '';
if(isset($params['remarques']) && $params['remarques'] != '')
{
	$remarques = $params['remarques'];
}

if($edit ==0)
{
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cc (id, date_created, date_modified, client, libelle_commande, prix_total, statut_commande, paiement, mode_paiement, remarques) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ? ,?)";
	$dbresult1 = $db->Execute($query1, array($date_created,$date_created,$client, $libelle_commande,$prix_total, $statut_commande,$paiement, $mode_paiement, $remarques));
	$this->RedirectToAdminTab('commandesclients',array("nom"=>$client, "date_created"=>$date_created),'view_cc');
}
else
{
	//il s'agit d'une mise à jour !
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET date_modified = ?, libelle_commande = ?, statut_commande = ?, paiement = ?, mode_paiement = ?, remarques = ? WHERE id = ?";
	$dbresult2 = $db->Execute($query2, array($now, $libelle_commande, $statut_commande, $paiement, $mode_paiement, $remarques, $record_id));
	
	if($statut_commande == "Reçue")
	{
		//Commande reçue, 
		//on change l'aspect (couleur)
		//les boutons de suppression disparaissent
		//Les items ne sont plus disponibles (statut = 0)
		$query3 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '1' WHERE fk_id = ?";
		$dbresult3 = $db->Execute($query3, array($record_id));
		if($dbresult3)
		{
			$designation.="Les articles de cette commande ont changé de statut";
			// on met les articles dans le stock
			$query4 = "SELECT id, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE fk_id = ?";
			$dbresult4 = $db->Execute($query4, array($record_id));
			
			if($dbresult4 && $dbresult4->RecordCount()>0)
			{
				while($row4 = $dbresult4->FetchRow())
				{
					$id_items = $row4['id'];
					$fk_id = $row4['fk_id'];
					$libelle_commande = $row4['libelle_commande'];
					$categorie_produit = $row4['categorie_produit'];
					$fournisseur = $row4['fournisseur'];
					$quantite = $row4['quantite'];
					$ep_manche_taille = $row4['ep_manche_taille'];
					$couleur = $row4['couleur'];
					$prix_total = $row4['prix_total'];
					//$fk_id = $row4[''];
					
					$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$dbresult5 = $db->Execute($query5, array($id_items,$record_id, $libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));
				}
			}
		}
		
	}
	if($paiement == 'Payée et déstockée')
	{
		//la commande est payée et le client l'a reçue, on peut l'effacer du stock
		$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE fk_id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		
		
	}
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('commandesclients', '', 'admin_cc_tab');
}
















#
# EOF
#
?>