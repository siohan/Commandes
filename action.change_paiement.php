<?php

if( !isset($gCms) ) exit;
#################################################################################################
##      Cette page change le statur des paiements                                           #####
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
		
		$paiement = '';
		if(isset($params['paiement']) && $params['paiement'] !='')
		{
			$paiement = $params['paiement'];
		}
		
		$i = 0;//on instancie un compteur pour rendre compte
		
		
			foreach($a as $valeur)
			{
				//on va chercher les infos
				
				
				$query = "UPDATE ".cms_db_prefix()."module_commandes_cc SET paiement = ? WHERE id = ? ";
				
				$dbresult = $db->Execute($query, array($paiement,$valeur));

				if($dbresult)
				// && $dbresult->RecordCount()>0)
				{
					$designation.= "Paiement changé";
					if($paiement == 'Payée et déstockée')
					{
						//la commande est payée et le client l'a reçue, on peut l'effacer du stock
						$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE fk_id = ?";
						$dbresult = $db->Execute($query, array($valeur));
						$designation.= " - Produit(s) déstocké(s) ";


					}
					
					
						
				}
				else
				{
					$designation.="Ko";
				}
				


			}
			
			$this->SetMessage($designation);
			$this->Redirect($id,'defaultadmin', $returnid='', array("active_tab"=>"commandesclients"));
		
		
		
		
		
		
	}
}
else
{
	if(isset($params['sel']) && $params['sel'] !="")
	{			
		$sel = $params['sel'];
		
		//on construit le formulaire
		$smarty->assign('formstart',
				    $this->CreateFormStart( $id, 'change_paiement', $returnid ) );	
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'sel',$sel));
		
	
		$smarty->assign('paiement',
				$this->CreateInputDropdown($id,'paiement',$items_paiement));//,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
		
	
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
	
		echo $this->ProcessTemplate('change_paiement.tpl');
	}
}



#
# EOF
#
?>
