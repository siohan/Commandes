<?php
if (!isset($gCms)) exit;

debug_display($params, 'Parameters');

if (!$this->CheckPermission('Use Commandes'))
	{
	$params = array('message'=>Lang('needpermission'), 'active_tab' => 'commandesfournisseurs');
	$this->Redirect($id, 'defaultadmin','', $params);
	}
	
$error = 0;
$designation = '';
	$commande_number = '';
	if (isset($params['commande_number']) && $params['commande_number'] != '')
	{
		$commande_number = $params['commande_number'];
	}
	$record_id = '';
	if (isset($params['record_id']) && $params['record_id'] != '')
	{
		$record_id = $params['record_id'];
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

					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
					$dbresult = $db->Execute($query, array($commande_number));
					
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

					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cc WHERE commande_number = ?";
					$dbresult = $db->Execute($query, array($commande_number));
					
					if(!$dbresult)
					{
						$designation.= $db->ErrorMsg();
						
					}
					else
					{
						
						$designation.= "Commande supprimée. ";
						//on supprime aussi les items de cette commande
						$query = "DELETE FROM ".cms_db_prefix()."module_commandes_CC_items WHERE commande_number = ?";
						$dbresult = $db->Execute($query, array($commande_number));
						$designation.= "Articles de la commande supprimés ";
					}
					//$designation.="Résultat supprimé";
					$this->SetMessage("$designation");
					$this->RedirectToAdminTab('commandesclients');			

				break;
				
			
				case "item" :


					//Now remove the article 
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_items WHERE id = ?";
					$db->Execute($query, array($record_id));

					$this->SetMessage('Article supprimé');
					$this->RedirectToAdminTab('articles');
				break;
				case "cf" :
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cf WHERE id_CF = ?";
					$db->Execute($query, array($record_id));
					
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
					$db->Execute($query, array($record_id));
					
					$designation = "Commande supprimée - articles en attente de commande";
					$this->SetMessage("$designation");
					$this->RedirectToAdminTab('commandesfournisseurs');
					
				break;
				case "stock" :
					$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE id = ?";
					$db->Execute($query, array($record_id));
					
					$designation = "Stock modifié";
					$this->SetMessage("$designation");
					$this->RedirectToAdminTab('stock');
					
				break;
				
			}
		}
		
		

?>