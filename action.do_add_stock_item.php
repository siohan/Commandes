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
		
			$query2 = "SELECT prix_unitaire, reduction, categorie, fournisseur,id AS id_items FROM ".cms_db_prefix()."module_commandes_items WHERE libelle LIKE ?";
			$dbresult2 = $db->Execute($query2, array($libelle_commande));
			if($dbresult2)
			{
				$row2 = $dbresult2->FetchRow();
				$reduction = $row2['reduction'];
				$prix_unitaire = $row2['prix_unitaire'];
			//	echo $prix_unitaire;
				$fournisseur = $row2['fournisseur'];
				$categorie_produit = $row2['categorie'];
				$id_items = $row2['id_items'];
			}
		
		
			
		//on calcule le nb d'erreur
		//echo "le nb erreur est : ".$error;
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('stock');
		}
		else // pas d'erreurs on continue
		{
			
			//on vérifie si l'article est déjà présent auquel cas on incrémente
			
			$query2 = "SELECT quantite FROM ".cms_db_prefix()."module_commandes_stock WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
			$dbresult2 = $db->Execute($query2, array($libelle_commande, $ep_manche_taille, $couleur));
			$nb_records = $dbresult2->RecordCount();
			//plusieurs cas
			
			if($nb_records > 0)
			{
				$row = $dbresult2->FetchRow();
				$qte = $row['quantite'];
				$new_quantite = $qte + $quantite;
				
				//on fait le calcul du prix total de larticle
				$prix_total = $prix_unitaire*$new_quantite*(1-$reduction/100);
				//on fait un update
				$query3 = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = ? , prix_total = ? WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
				$dbresult3 = $db->Execute($query3, array($new_quantite, $prix_total,$libelle_commande, $ep_manche_taille, $couleur));
				
				if(!$dbresult3)
				{
					echo $db->ErrorMsg();
				}
			}
			else
			{
				//on fait le calcul du prix total de larticle
				$prix_total = $prix_unitaire*$quantite*(1-$reduction/100);
				//$id_items = 0;
				$fk_id = 0;
				$query = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($id_items,$fk_id, $libelle_commande,$categorie_produit,$fournisseur, $quantite,$ep_manche_taille, $couleur, $prix_total));
				
				if(!$dbresult)
				{
					echo $db->ErrorMsg();
				}
			}
			
			
			
		
		
			
			$this->SetMessage('stock modifié');
			$this->Redirect($id,'defaultadmin', $returnid, array("active_tab"=>"stock"));
			
		}		
		//echo "la valeur de edit est :".$edit;
		
		
	
			
		



?>