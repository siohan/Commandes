<?php

if( !isset($gCms) ) exit;
#################################################################################################
##      Cette page change le statut d'une sélection                                         #####
##      Le formulaire se retourne sur lui même pour traitement                              #####
#################################################################################################

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

$error = 0; //on instancie un compteur d'erreur
//debug_display($params, 'Parameters');
$designation = ''; //le message de sortie
require_once(dirname(__FILE__).'/include/preferences.php');
//le formulaire a t-il été soumis ?
if(isset($params['submit']))
{
	//on fait les traitements
	//on vérifie que tt est là
	$db =& $this->GetDb();
	if(isset($params['sel']) && $params['sel'] != '')
	{
		$sel = $params['sel'];
		$tab = explode('-', $sel);
		$a = array();
		foreach($tab as $value)
		{
			array_push($a,$value);
		}
		//var_dump($a);
		
		$item_marque = '';
		if(isset($params['item_marque']) && $params['item_marque'] !='')
		{
			$item_marque = strtoupper($params['item_marque']);
		}
		
		$i = 0;//on instancie un compteur pour rendre compte
		
		
			foreach($a as $valeur)
			{
				//on va chercher les infos
				
				
				$query = "UPDATE ".cms_db_prefix()."module_commandes_items SET marque = ? WHERE id = ? ";
				
				$dbresult = $db->Execute($query, array($item_marque,$valeur));

				if($dbresult)
				// && $dbresult->RecordCount()>0)
				{
					$designation.= "Ok->Marque changée ";
					//on vérifie les actions spécifiques pour les statuts
					// 1 - Statut "reçue" -> les éléments partent en stock
					
					
						
				}
				else
				{
					$designation.="Ko";
				}
				


			}
			
			$this->SetMessage($designation);
			$this->Redirect($id,'defaultadmin', $returnid='', array("active_tab"=>"articles"));
		
		
		
		
		
		
	}
}
else
{
	if(isset($params['sel']) && $params['sel'] !="")
	{			
		$sel = $params['sel'];
		//faudrait vérifier si une commande a le statut "Reçue" pour stopper l'action
		
		
		//on construit le formulaire
		$smarty->assign('formstart',
				    $this->CreateFormStart( $id, 'change_item_marque', $returnid ) );	
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'sel',$sel));
		
	
		$smarty->assign('item_marque',
				$this->CreateInputText($id,'item_marque','',50, 150));//,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
		
	
		$smarty->assign('submit',
				$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
		$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
							$this->Lang('cancel')));
		$smarty->assign('back',
				$this->CreateInputSubmit($id,'back',
							$this->Lang('back')));

		$smarty->assign('formend',
				$this->CreateFormEnd());
	
		echo $this->ProcessTemplate('change_item_marque.tpl');
	}
}



#
# EOF
#
?>
