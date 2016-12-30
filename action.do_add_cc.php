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

if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
else
{
	exit;
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

$fournisseur = '';
if(isset($params['fournisseur']) && $params['fournisseur'] != '')
{
	$fournisseur = $params['fournisseur'];
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
	$paiement = 'Non payée';
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
	$user_validation = 1;
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cc (id, date_created, date_modified, client, libelle_commande,fournisseur, prix_total, statut_commande, paiement, mode_paiement, remarques, commande_number) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?)";
	$dbresult1 = $db->Execute($query1, array($date_created,$date_created,$client, $libelle_commande,$fournisseur,$prix_total, $statut_commande,$paiement, $mode_paiement, $remarques, $commande_number));
	$this->RedirectToAdminTab('commandesclients',array("nom"=>$client, "date_created"=>$date_created,"commande_number"=>$commande_number,"fournisseur"=>$fournisseur),'add_edit_cc_item');
}
else
{
	//il s'agit d'une mise à jour !
	//on récupère les éléments d'origine des articles de cette commande
	$query4 = "SELECT id, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total,commande,commande_number  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
	$dbresult4 = $db->Execute($query4, array($commande_number));

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
			$commande = $row4['commande'];
			//$commande_number = $row4['commande_number'];
			//$fk_id = $row4[''];
			
			if ($commande == 0)//les articles ne sont pas en stock
			{
				if($statut_commande == "Reçue")
				{
			
					$service = new commandes_ops();
					$en_stock = $service->en_stock($libelle_commande,$ep_manche_taille,$couleur);
					//var_dump($en_stock);
					
					if($en_stock === FALSE)
					{
						$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						$dbresult5 = $db->Execute($query5, array($id_items,$record_id, $libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));
					}
					else //le type d'article est déjà en stock, on incrémente le stock
					{
						$increment = $service->incremente_stock($libelle_commande, $quantite, $ep_manche_taille, $couleur);
					}
					$query3 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '1' WHERE commande_number = ?";
					$dbresult3 = $db->Execute($query3, array($commande_number));
				}
			}
			elseif($commande == 1)
			{
				if($paiement == 'Payée et déstockée')
				{
					/*
					//la commande est payée et le client l'a reçue, on peut l'effacer du stock
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE fk_id = ?";
					$dbresult = $db->Execute($query, array($record_id));
					*/
					//$new_quantite = $qua
					$service = new commandes_ops();
					$en_stock = $service->en_stock($libelle_commande,$ep_manche_taille,$couleur);
					$decrement = $service->decremente_stock($libelle_commande, $quantite, $ep_manche_taille, $couleur);
					$refresh = $service->refresh_stock();


				}
			}
		}

	}
		//les articles sont en stock
			
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET date_modified = ?, libelle_commande = ?, statut_commande = ?, paiement = ?, mode_paiement = ?, remarques = ? WHERE commande_number = ?";
	$dbresult2 = $db->Execute($query2, array($now, $libelle_commande, $statut_commande, $paiement, $mode_paiement, $remarques, $commande_number));
	
	
	
		
		
		
	
	
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('commandesclients', '', 'admin_cc_tab');
}
#
# EOF
#
?>