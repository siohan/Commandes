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
$adh_ops = new adherents_spid;
	
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
$query = "SELECT it.id AS items_id, fk_id, CONCAT_WS(' - ',quantite, libelle_commande,ep_manche_taille, couleur) AS commande, it.prix_total,fk_id FROM ".cms_db_prefix()."module_commandes_cc_items AS it, ".cms_db_prefix()."module_commandes_cf_items AS cfi WHERE cfi.id_items = it.id  AND cfi.id_CF = ? AND cfi.received = '0'";
//echo $query;
//$query.="  ORDER BY commande ASC ";
$dbresult = $db->Execute($query,array($record_id));

	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	$lignes = $dbresult->RecordCount();
	$smarty->assign('formstart',
			$this->CreateFormStart( $id, 'do_receive_command', $returnid ) );
	$smarty->assign('record_id',
			$this->CreateInputHidden($id,'record_id',$record_id));

	if($dbresult && $dbresult->RecordCount()>0)
	{
		$rowarray = array();
		while($row = $dbresult->FetchRow())
		{
					
			$fk_id = $row['fk_id'];
			$items_id = $row['items_id'];
			$commande = $row['commande'];
			$prix_total = $row['prix_total'];
			$rowarray[$items_id]['id'] = $id;
			$rowarray[$items_id]['commande'] = $commande;
			$rowarray[$items_id]['participe'] = false;
			$rowarray[$items_id]['prix_total'] = $prix_total;
			$rowarray[$items_id]['client'] = $adh_ops->get_name($fk_id);
		
			//on va chercher si la commande est déjà dans la table participe
			
			$query1 = "SELECT id_CF FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_items = ?";
			$dbresult1 = $db->Execute($query1,array( $items_id));
			//on va compter le nb de résultats
			$nb_res = $dbresult1->RecordCount();
			//echo "nb results :".$nb_res;
			$row1 = $dbresult1->FetchRow();
			$id_CF = $row1['id_CF'];
			
		
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

						$rowarray[$items_id]['received'] = false;
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

echo $this->ProcessTemplate('receive_command.tpl');
#
#EOF
#
?>