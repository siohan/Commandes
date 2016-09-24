<?php

if( !isset($gCms) ) exit;
/*
	if (!$this->CheckPermission('Ping Manage'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}
*/
	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('CF');
    		return;
  	}
debug_display($params, 'Parameters');
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
	$this->RedirectToAdminTab('CF',array("active_tab"=>"commandesf","fournisseur"=>$fournisseur, "date_created"=>$date_created),'view_order_CF');
}
else
{
	//il s'agit d'une mise à jour !
	//on regarde aussi si le statut est égal à "Reçue"
	
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cf SET date_created = ?, fournisseur = ?, statut_CF = ? WHERE id_CF = ?";
	$dbresult2 = $db->Execute($query2, array($now, $fournisseur, $statut_CF, $record_id));
	
//	if($statut_cf)
	
	$this->RedirectToAdminTab('commandesf', '', 'admin_cf_tab');
}
















#
# EOF
#
?>