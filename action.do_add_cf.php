<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('CF');
    		return;
  	}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
$now = date('Y-m-d');
$designation = '';//le message final
$error = 0;//on initie un compteur d'erreur, 0 par défaut

if(isset($params['edition']) && $params['edition'] != '')
{
	$edit = $params['edition'];
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
}



if(isset($params['date_created']) && $params['date_created'] != '')
{
	$date_created = $params['date_created'];
}

if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}

if(isset($params['statut_CF']) && $params['statut_CF'] != '')
{
	$statut_CF = $params['statut_CF'];
}
if(isset($params['fournisseur']) && $params['fournisseur'] != '')
{
	$fournisseur = $params['fournisseur'];
}
else
{
	$fournisseur = 'Autres';
}



if($edit ==0)
{
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cf (id_CF, date_created, fournisseur,  statut_CF) VALUES ('', ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($date_created,$fournisseur, $statut_CF));
	$this->RedirectToAdminTab('commandesfournisseurs',array("active_tab"=>"commandesfournisseurs","fournisseur"=>$fournisseur, "date_created"=>$date_created),'view_order_CF');
}
else
{
	//il s'agit d'une mise à jour !
	//on regarde aussi si le statut est égal à "Reçue"
	
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cf SET  fournisseur = ?, statut_CF = ? WHERE id_CF = ?";
	$dbresult2 = $db->Execute($query2, array( $fournisseur, $statut_CF, $record_id));
	
	
	/*ci dessous, si la commande fournisseur est reçue, on va chercher à mettre 
	le statut reçue également aux commandes clients avec le même fournisseur
	*/
	
	if($statut_CF == 'Reçue')
	{
		$query = "SELECT id_CF,id_items FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
		$dbresult = $db->Execute($query, array($record_id));
		
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$id_items = $row['id_items'];
				
				$query2 = "SELECT fk_id,id FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ? GROUP BY fournisseur";
				$dbresult2 = $db->Execute($query2, array($id_items));
				$row = $dbresult2->FetchRow();
				$fk_id = $row['fk_id'];
				$id = $row['id'];
				echo "id -> fk_id = ".$id." -> ".$fk_id."<br />";
				
				if($dbresult2->RecordCount() <1)
				{
					//il n'y a qu'un fournisseur !
					$query3 = "UPDATE ".cms_db_prefix()."module_commande_cc SET statut_commande = 'Reçue' WHERE id = ?";
					$dbresult3 = $db->Execute($query3, array($fk_id));
				}
			}
		}
	}
	
	$this->RedirectToAdminTab('commandesfournisseurs', '', 'admin_cf_tab');
}
















#
# EOF
#
?>