<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Use Commandes'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('commandesclients');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		$commande_id = '';
		if (isset($params['commande_id']) && $params['commande_id'] !='')
		{
			$commande_id = $params['commande_id'];
		}
		else
		{
			$error++;
		}
		
		$libelle_commande = '';
		if (isset($params['libelle_commande']) && $params['libelle_commande'] !='')
		{
			$libelle_commande = $params['libelle_commande'];
		}
		
		
		$ep_manche_taille = '';
		if (isset($params['ep_manche_taille']) && $params['ep_manche_taille'] !='')
		{
			$ep_manche_taille = $params['ep_manche_taille'];
		}
		else
		{
			$error++;
		}
		
		$couleur = '';
		if (isset($params['couleur']) && $params['couleur'] !='')
		{
			$couleur = $params['couleur'];
		}
		else
		{
			$error++;
		}
		if (isset($params['categorie_produit']) && $params['categorie_produit'] !='')
		{
			$categorie_produit = $params['categorie_produit'];
		}
		
		
		$fournisseur = '';
		if (isset($params['fournisseur']) && $params['fournisseur'] !='')
		{
			$fournisseur = $params['fournisseur'];
		}
		/*
		else
		{
			$error++;
		}
		*/
		$quantite = '';
		if (isset($params['quantite']) && $params['quantite'] !='')
		{
			$quantite = $params['quantite'];
		}
		else
		{
			$error++;
		}
		//on va faire le calcul du prix de la ligne
		//on va d'abord chercher le prix unitaire de l'article
		
			$query2 = "SELECT prix_unitaire, reduction, categorie, fournisseur FROM ".cms_db_prefix()."module_commandes_items WHERE libelle LIKE ?";
			$dbresult2 = $db->Execute($query2, array($libelle_commande));
			if($dbresult2)
			{
				$row2 = $dbresult2->FetchRow();
				$reduction = $row2['reduction'];
				$prix_unitaire = $row2['prix_unitaire'];
			//	echo $prix_unitaire;
				$fournisseur = $row2['fournisseur'];
				$categorie_produit = $row2['categorie'];
			}
		
		
		$statut_item = '1';
		if (isset($params['statut_item']) && $params['statut_item'] !='')
		{
			$statut_item = $params['statut_item'];
		}
		
		
	
		
		//s'agit-il d'une édition ou d'un ajout ?
		//$record_id = '';
		if(isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			$edit = 1;//c'est un update
		}
		
		
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('CC');
		}
		else // pas d'erreurs on continue
		{
			
			//on fait le calcul du prix total de larticle
			$prix_total = $prix_unitaire*$quantite*(1-$reduction/100);
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_commandes_cc_items (id,fk_id,date_created, date_modified,libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total, statut_item) VALUES ('',?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($commande_id,$aujourdhui, $aujourdhui,$libelle_commande,$categorie_produit,$fournisseur, $quantite,$ep_manche_taille, $couleur, $prix_total, $statut_item));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET libelle_commande = ?, date_modified =?, quantite = ?,ep_manche_taille = ?, couleur = ?, prix_total = ?,statut_item = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($libelle_commande,$aujourdhui, $quantite,$ep_manche_taille, $couleur, $prix_total,$statut_item,$record_id));
				
				
			}
			
			//on modifie aussi le prix total de la commande client
			$query2 = "SELECT SUM(prix_total) AS prix_definitif FROM ".cms_db_prefix()."module_commandes_cc_items WHERE fk_id = ?";
			$dbresult2 = $db->Execute($query2, array($commande_id));
			
			if($dbresult2)
			{
				while($dbresult2 && $row = $dbresult2->FetchRow())
				{
					$prix_definitif = $row['prix_definitif'];
					$query3 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET prix_total = ? WHERE id = ?";
					$dbresult3 = $db->Execute($query3, array($prix_definitif,$commande_id));
				}
			}
			else
			{
				//pb avec la requete
			}
			
		}		
		//echo "la valeur de edit est :".$edit;
		
		
	
			
		

$this->SetMessage('Article modifié');
$this->Redirect($id,'view_cc', $returnid, array("record_id"=>$commande_id));

?>