<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Use Commandes'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('clients');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		$nom = '';
		if (isset($params['nom']) && $params['nom'] !='')
		{
			$nom = strtoupper($params['nom']);
		}
		else
		{
			$error++;
		}
		$licence = '';
		if (isset($params['licence']) && $params['licence'] !='')
		{
			$licence = $params['licence'];
		}
		else
		{
			$error++;
		}
		
		$prenom = '';
		if (isset($params['prenom']) && $params['prenom'] !='')
		{
			$prenom = ucfirst($params['prenom']);
		}
		
		
		$club = '';
		if (isset($params['club']) && $params['club'] !='')
		{
			$club = ucfirst($params['club']);
		}
		
		
		$email = '';
		if (isset($params['email']) && $params['email'] !='' && True === is_email($params['email']))
		{
			$email = $params['email'];
		}
		else
		{
			$error++;
		}
		$tel = '';
		if (isset($params['tel']) && $params['tel'] !='')
		{
			$tel = $params['tel'];
		}
		
		
		$portable = '';
		if (isset($params['portable']) && $params['portable'] !='')
		{
			$portable = $params['portable'];
		}
		
		
	
		
		//s'agit-il d'une édition ou d'un ajout ?
		$record_id = '';
		if(isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			$edit = 1;//c'est un update
		}
		
		
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('clients');
		}
		else // pas d'erreurs on continue
		{
			
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_commandes_clients (id,date_created, date_maj, nom, prenom, club, email, tel, portable, licence) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($aujourdhui, $aujourdhui,$nom,$prenom,$club, $email, $tel, $portable,$licence));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_commandes_clients SET nom = ?, prenom = ?, licence = ?, date_maj = ?, club = ?, email = ?,tel = ? , portable = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($nom,$prenom, $licence, $aujourdhui, $club,$email,$tel,$portable,$record_id));
				
				
			}
			
		}		
		
		
		
	
			
		

$this->SetMessage('Client modifié');
$this->RedirectToAdminTab('clients');

?>