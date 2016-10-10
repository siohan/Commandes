<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Use Commandes'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('compets');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		
		
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
		
		
		$couleur = '';
		if (isset($params['couleur']) && $params['couleur'] !='')
		{
			$couleur = $params['couleur'];
		}
		
		
		
		
		$fournisseur = '';
		if (isset($params['fournisseur']) && $params['fournisseur'] !='')
		{
			$fournisseur = $params['fournisseur'];
		}
		
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
		
		
			
		//on calcule le nb d'erreur
		echo "le nb erreur est : ".$error;
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('stock');
		}
		else // pas d'erreurs on continue
		{
			
			//on fait le calcul du prix total de larticle
			$prix_total = $prix_unitaire*$quantite*(1-$reduction/100);
			$id_items = 0;
			$fk_id = 0;
			$query = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', '', ?, ?, ?, ?, ?, ?, ?, ?)";
			$dbresult = $db->Execute($query, array($fk_id, $libelle_commande,$categorie_produit,$fournisseur, $quantite,$ep_manche_taille, $couleur, $prix_total));
			if(!$dbresult)
			{
					echo $db->ErrorMsg();
			}
		
			
			$this->SetMessage('stock modifié');
			$this->Redirect($id,'defaultadmin', $returnid, array("active_tab"=>"stock"));
			
		}		
		//echo "la valeur de edit est :".$edit;
		
		
	
			
		



?>