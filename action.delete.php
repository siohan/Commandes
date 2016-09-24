<?php
if (!isset($gCms)) exit;

debug_display($params, 'Parameters');
/*
if (!$this->CheckPermission('Ping Delete'))
	{
	$params = array('message'=>Lang('needpermission'), 'active_tab' => 'users');
	$this->Redirect($id, 'defaultadmin','', $params);
	}
*/	
$error = 0;
$designation = '';
	$record_id = '';
	if (isset($params['record_id']) && $params['record_id'] != '')
	{
		$record_id = $params['record_id'];
	}
	else
	{
			$error++;
	}
	$bdd = '';
	if(isset($params['bdd']) && $params['bdd'] !='')
	{
			 $bdd = $params['bdd'];
	}
	else
	{
			$error++;
	}
		
		if ($error==0)
		{
			switch($bdd)
			{
				case  "cc_items" :

					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ?";
					$dbresult = $db->Execute($query, array($record_id));
					
					if(!$dbresult)
					{
						$designation.= $db->ErrorMsg();
						
					}
					else
					{
						$designation.="Résultat supprimé";
						$this->SetMessage("$designation");
						$this->RedirectToAdminTab('cc');
					}			

				break;
				
				case  "cc" :

					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cc WHERE id = ?";
					$dbresult = $db->Execute($query, array($record_id));
					
					if(!$dbresult)
					{
						$designation.= $db->ErrorMsg();
						
					}
					else
					{
						
						$designation.= "Commande supprimée. ";
						//on supprime aussi les items de cette commande
						$query = "DELETE FROM ".cms_db_prefix()."module_commandes_CC_items WHERE fk_id = ?";
						$dbresult = $db->Execute($query, array($record_id));
						$designation.= "Articles de la commande supprimés ";
					}
					//$designation.="Résultat supprimé";
					$this->SetMessage("$designation");
					$this->RedirectToAdminTab('cc');			

				break;
				
				case "clients" :


					//Now remove the article
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_clients WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Résultat supprimé');
					$this->RedirectToAdminTab('cc');
				break;
				case "item" :


					//Now remove the article 
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_items WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Article supprimé');
					$this->RedirectToAdminTab('articles');
				break;
				case "teams" : 
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_equipes WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Equipe supprimée');
					$this->RedirectToAdminTab('equipes');
				break;
				
				case "calendrier" : 
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_calendrier WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Date supprimée');
					$this->RedirectToAdminTab('calendrier');
				break;
				
				case "poules" : 
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_poules_rencontres WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Match supprimé');
					$this->RedirectToAdminTab('poules');
				break;
				
				case "sit_mens" :
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_sit_mens WHERE id = ?";
					$db->Execute($query, array($record_id));
					
					$this->SetMessage('situation supprimée');
					$this->RedirectToAdminTab('situation');
				break;
				
				case "division" :
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_divisions WHERE iddivision = ?";
					$db->Execute($query, array($record_id));
					
					//on supprime aussi les autres tours , parties, et classement affiliés
					//on commence par les tours
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_div_tours WHERE iddivision = ?";
					$db->Execute($query, array($record_id));
					//on continue par les parties
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_div_parties WHERE iddivision = ?";
					$db->Execute($query, array($record_id));
					//enfin les classements
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_div_classement WHERE iddivision = ?";
					$db->Execute($query, array($record_id));
					
					$this->SetMessage('Division supprimée');
					$this->Redirect('defaultadmin');
				break;
				case "classement" : 
					$query = "DELETE FROM ".cms_db_prefix()."module_ping_div_classement WHERE tableau = ?";
					$db->Execute($query, array($record_id));
					//le classement est effacé, il faut rétablir uploaded_classement à NULL
					$query = "UPDATE ".cms_db_prefix()."module_ping_div_tours SET uploaded_classement = NULL WHERE tableau = ?";
					$db->Execute($query, array($record_id));
					$this->SetMessage('Classement supprimé');
					$this->Redirect('defaultadmin');
					
				case "demo" :
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_joueurs";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_participe";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_equipes";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_parties_spid";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_parties";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_recup_parties";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_poules_rencontres";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_sit_mens";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_calendrier";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_comm";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_recup";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_adversaires";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_classement";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_divisions";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_div_classement";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_div_parties";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_div_tours";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_feuilles_rencontres";
					$db->Execute($query);
					
					$query = "TRUNCATE ".cms_db_prefix()."module_ping_rencontres_parties";
					$db->Execute($query);
					
				break;
				
			}
		}
		
		

?>