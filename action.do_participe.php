<?php
if (!isset($gCms)) exit;
require_once(dirname(__FILE__).'/include/preferences.php');
//debug_display($params, 'Parameters');


	if (!$this->CheckPermission('Use Commandes'))
	{
		$designation.=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('clients');
	}

$annee = date('Y');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		
		$record_id = '';
		if (isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
		}
		else
		{
			$error++;
		}
		
		/*
		$date_debut = '';
		if (isset($params['date_created']) && $params['date_created'] != '')
		{
			$date_created = $params['date_created'];
		}
		else
		{
			$error++;
		}
		*/
		if($error ==0)
		{
			//on vire toutes les données de cette compet avant 
			$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
			$dbquery = $db->Execute($query, array($record_id));	
			$id_CF = '';
			if (isset($params['id_CF']) && $params['id_CF'] != '')
			{
				$id_CF = $params['id_CF'];
				$error++;
			}
			foreach($id_CF as $key=>$value)
			{
				$query4 = "SELECT prix_total FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ? ";
				$dbresult4 = $db->Execute($query4, array($key));
				$row4 = $dbresult4->FetchRow();
				$prix_total = $row4['prix_total'];
				
				
				
				$query2 = "INSERT INTO ".cms_db_prefix()."module_commandes_cf_items (id,id_CF,id_items,prix_total) VALUES ('', ?, ?, ?)";//", ?)";
				//echo $query2;
				$dbresultat = $db->Execute($query2, array($record_id,$key, $prix_total)); //$date_debut));
			}
			
		}
		

$this->SetMessage('articles ajoutés à la commande fournisseur !');
$this->RedirectToAdminTab('commandesfournisseurs');

?>