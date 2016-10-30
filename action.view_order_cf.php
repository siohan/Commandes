<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$designation = '';
$fournisseur = '';
$record_id = '';
$rowarray = array();

	if(!isset($params['fournisseur']) || $params['fournisseur'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	else
	{
		$fournisseur = $params['fournisseur'];
	}
	if(!isset($params['record_id']) || $params['record_id'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	else
	{
		$record_id = $params['record_id'];
	}
	
	
	
$db = $this->GetDb();
//la requete pour aller chercher tous les articles commandés par les clients dont le fournisseur est ...
$query = "SELECT id AS items_id, fk_id, CONCAT_WS(' - ',quantite, libelle_commande,ep_manche_taille, couleur) AS commande, prix_total FROM ".cms_db_prefix()."module_commandes_cc_items AS it  WHERE it.fournisseur =  ? AND it.commande = '0'";
//echo $query;
$query.="  ORDER BY commande ASC ";
$dbresult = $db->Execute($query,array($fournisseur));

	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	$lignes = $dbresult->RecordCount();
	//echo "<br />le nb de lignes est : ".$lignes."<br />";;
	$smarty->assign('formstart',
			$this->CreateFormStart( $id, 'do_participe', $returnid ) );
	$smarty->assign('record_id',
			$this->CreateInputText($id,'record_id',$record_id,10,15));
	/*
	$smarty->assign('date_fin',
			$this->CreateInputText($id,'date_fin',$date_fin,10,15));
	*/	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		$rowarray = array();
		while($row = $dbresult->FetchRow())
		{
			//var_dump($row);
			/*
			$id = $row['id'];
			$joueur = $row['joueur'];
			$rowarray[$licence]['name'] = $joueur;
			$rowarray[$licence]['participe'] = false;
			*/
			
			
			$fk_id = $row['fk_id'];
			$items_id = $row['items_id'];
			$commande = $row['commande'];
			$prix_total = $row['prix_total'];
			//echo $commande."<br />";
			$rowarray[$items_id]['id'] = $id;
			$rowarray[$items_id]['commande'] = $commande;
			$rowarray[$items_id]['participe'] = false;
			$rowarray[$items_id]['prix_total'] = $prix_total;
			//var_dump($rowarray);
			
			//on va chercher si la commande est déjà dans la table participe
			
			$query1 = "SELECT id_CF FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_items = ?";
			$dbresult1 = $db->Execute($query1,array( $items_id));
			//on va compter le nb de résultats
			$nb_res = $dbresult1->RecordCount();
			//echo "nb results :".$nb_res;
			$row1 = $dbresult1->FetchRow();
			$id_CF = $row1['id_CF'];
			
			if($nb_res ==0 )
			{
					//$query2 = "SELECT licence, idepreuve FROM ".cms_db_prefix()."module_ping_participe WHERE licence = ? AND idepreuve = ? AND date_debut BETWEEN ? AND ?";
					$query2 = "SELECT * FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ? AND id_items = ?";
					//echo $query2;
					$dbresultat = $db->Execute($query2, array($record_id,$items_id));

					if($dbresultat->RecordCount()>0)
					{
						while($row2 = $dbresultat->FetchRow())
						{

							$rowarray[$items_id]['participe'] = true;
						}
					}
			}
			elseif( $nb_res >0)
			{
					if($id_CF == $record_id)
					{
						//$query2 = "SELECT licence, idepreuve FROM ".cms_db_prefix()."module_ping_participe WHERE licence = ? AND idepreuve = ? AND date_debut BETWEEN ? AND ?";
						$query2 = "SELECT * FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ? AND id_items = ?";
						//echo $query2;
						$dbresultat = $db->Execute($query2, array($record_id,$items_id));

						if($dbresultat->RecordCount()>0)
						{
							while($row2 = $dbresultat->FetchRow())
							{

								$rowarray[$items_id]['participe'] = true;
							}
						}
					}
			}
			
				
			
			
		
			
			
		}
		//print_r($rowarray);
		$smarty->assign('rowarray',$rowarray);	
			
	}
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
/**/
echo $this->ProcessTemplate('view_order_cf.tpl');
#
#EOF
#
?>