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
		
	
		
		$nom_fournisseur = '';
		if (isset($params['nom_fournisseur']) && $params['nom_fournisseur'] !='')
		{
			$nom_fournisseur = $params['nom_fournisseur'];
		}
		else
		{
			$error++;
		}
		
		$description = '';
		if (isset($params['description']) && $params['description'] !='')
		{
			$description = $params['description'];
		}
		
		
		
		
		$actif = 0;
		if (isset($params['actif']) && $params['actif'] !='')
		{
			$actif = $params['actif'];
		}
		
		if (isset($params['ordre']) && $params['ordre'] !='')
		{
			$ordre = $params['ordre'];
		}
		else
		{
			$error++;
		}
				
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('fournisseurs');
		}
		else // pas d'erreurs on continue
		{
			
			
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_commandes_fournisseurs (nom_fournisseur, description, actif, ordre) VALUES ( ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($nom_fournisseur, $description, $actif, $ordre));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_commandes_fournisseurs SET nom_fournisseur = ?, description = ?, actif = ?,ordre = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($nom_fournisseur, $description, $actif, $ordre,$record_id));
				
				
			}
			
			
			
		}		
	//	echo "la valeur de edit est :".$edit;
		
		
	
			
		

$this->SetMessage('Article modifié ou ajouté');
$this->RedirectToAdminTab('fournisseurs',$params='');

?>