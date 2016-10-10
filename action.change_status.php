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
		
		$statut_commande = '';
		if(isset($params['statut_commande']) && $params['statut_commande'] !='')
		{
			$statut_commande = $params['statut_commande'];
		}
		
		$i = 0;//on instancie un compteur pour rendre compte
		
		
			foreach($a as $valeur)
			{
				//on va chercher les infos
				
				
				$query = "UPDATE ".cms_db_prefix()."module_commandes_cc SET statut_commande = ? WHERE id = ? ";
				
				$dbresult = $db->Execute($query, array($statut_commande,$valeur));

				if($dbresult)
				// && $dbresult->RecordCount()>0)
				{
					$designation.= "Ok";
					//on vérifie les actions spécifiques pour les statuts
					// 1 - Statut "reçue" -> les éléments partent en stock
					if($statut_commande == "Reçue")
					{
						//Commande reçue, 
						//on change l'aspect (couleur)
						//les boutons de suppression disparaissent
						//Les items ne sont plus disponibles (statut = 0)
						$query3 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '1' WHERE fk_id = ?";
						$dbresult3 = $db->Execute($query3, array($valeur));
						if($dbresult3)
						{
							$designation.="Les articles de cette commande ont changé de statut";
							// on met les articles dans le stock
							$query4 = "SELECT id, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE fk_id = ?";
							$dbresult4 = $db->Execute($query4, array($valeur));

							if($dbresult4 && $dbresult4->RecordCount()>0)
							{
								while($row4 = $dbresult4->FetchRow())
								{
									$id_items = $row4['id'];
									$fk_id = $row4['fk_id'];
									$libelle_commande = $row4['libelle_commande'];
									$categorie_produit = $row4['categorie_produit'];
									$fournisseur = $row4['fournisseur'];
									$quantite = $row4['quantite'];
									$ep_manche_taille = $row4['ep_manche_taille'];
									$couleur = $row4['couleur'];
									$prix_total = $row4['prix_total'];
									//$fk_id = $row4[''];

									$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
									$dbresult5 = $db->Execute($query5, array($id_items,$valeur, $libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));
								}
							}
						}
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
		//faudrait vérifier si une commande a le statut "Reçue" pour stopper l'action
		
		$sel = $params['sel'];
		$tab = explode('-', $sel);
		$a = array();
		$error = 0; //on instancie un compteur d'erreurs
		foreach($tab as $value)
		{
			$query = "SELECT statut_commande FROM ".cms_db_prefix()."module_commandes_cc WHERE id = ?";
			$dbresult = $db->Execute($query, array($value));
			if($dbresult)
			{
				$row = $dbresult->FetchRow();
				$statut_commande = $row['statut_commande'];
				if($statut_commande == "Reçue")
				{
					$error++;
				}
			}
			else
			{
				//$this->$db = ErrorMsg();
				echo $db->ErrorMsg();
			}
			unset($statut_commande);
			
		}
		
		if($error >0)
		{
			$this->SetMessage("une commande a le statut inchangeable");
			$this->redirect($id,'defaultadmin',$returnid,array('active_tab'=>'commandesclients'));
		}
		//on construit le formulaire
		$smarty->assign('formstart',
				    $this->CreateFormStart( $id, 'change_status', $returnid ) );	
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'sel',$sel));
		
	
		$smarty->assign('statut_commande',
				$this->CreateInputDropdown($id,'statut_commande',$items_statut_commande));//,$selectedIndex=$key2_statut_commande,$selectedvalue=$statut_commande));
		
	
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
	
		echo $this->ProcessTemplate('change_status.tpl');
	}
}



#
# EOF
#
?>
