<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Use Commandes'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('articles');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		if (isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			$edit = 1;
		}		
		
		$categorie = '';
		if (isset($params['categorie']) && $params['categorie'] !='')
		{
			$categorie = $params['categorie'];
		}
		else
		{
			$error++;
		}
		
		$fournisseur = '';
		if (isset($params['fournisseur']) && $params['fournisseur'] !='')
		{
			$fournisseur = $params['fournisseur'];
		}
		else
		{
			$error++;
		}
		
		$reference = '';
		if (isset($params['reference']) && $params['reference'] !='')
		{
			$reference = $params['reference'];
		}
		
		if (isset($params['libelle']) && $params['libelle'] !='')
		{
			$libelle = strtoupper($params['libelle']);
		}
		else
		{
			$error++;
		}
		
		
		$marque = '';
		if (isset($params['marque']) && $params['marque'] !='')
		{
			$marque = $params['marque'];
		}
		
		if (isset($params['prix_unitaire']) && $params['prix_unitaire'] !='')
		{
			$prix_unitaire = $params['prix_unitaire'];
		}
		else
		{
			$error++;
		}
		$reduction = '';
		if (isset($params['reduction']) && $params['reduction'] !='')
		{
			$reduction = $params['reduction'];
		}
		$marque = '';
		if (isset($params['marque']) && $params['marque'] !='')
		{
			$marque = strtoupper($params['marque']);
		}
		$statut_item = 1;
		if (isset($params['statut_item']) && $params['statut_item'] !='')
		{
			$statut_item = $params['statut_item'];
		}
		
		
		
		
		
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('CC');
		}
		else // pas d'erreurs on continue
		{
			
			
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_commandes_items (id,categorie,fournisseur, reference, libelle, marque, prix_unitaire, reduction, statut_item) VALUES ('',?, ?, ?, ?, ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($categorie,$fournisseur, $reference, $libelle, $marque, $prix_unitaire, $reduction, $statut_item));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_commandes_items SET categorie = ?, fournisseur = ?, reference = ?,libelle = ?, marque = ?, prix_unitaire = ?, reduction = ?, statut_item = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($categorie,$fournisseur, $reference,$libelle, $marque, $prix_unitaire,$reduction,$statut_item,$record_id));
				
				
			}
			
			
			
		}		
	//	echo "la valeur de edit est :".$edit;
		
		
	
			
		

$this->SetMessage('Article modifié ou ajouté');
$this->RedirectToAdminTab('articles',$params='');

?>