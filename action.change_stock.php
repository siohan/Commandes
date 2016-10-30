<?php
##############################################################################
##         Cette page incrémente/décrémente le stock                        ##
##############################################################################
if( !isset($gCms) ) exit;

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

//debug_display($params, 'Parameters');
$db =& $this->GetDb();
if(isset($params['id_items']) && $params['id_items'] != '')
{
	$id_items = $params['id_items'];
}
if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}
else
{
	$this->SetMessage('il manque des paramètres !');
	$this->RedirectToAdminTab('stock');
}
if(isset($params['qte']))
{
	$qte = $params['qte'];
}

if(isset($params['credit']) && $params['credit'] == 'plus')
{
	$new_qte = $qte + 1;	
}
else
{
	$new_qte = $qte - 1;
	
}
if($new_qte <= 0)
{
	$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	
}
else
{
	//faudrait recalculer le montant du stock restant...
	$query1 = "SELECT prix_unitaire FROM ".cms_db_prefix()."module_commandes_items WHERE id = ?";
	$dbresult1 = $db->Execute($query1, array($id_items));
	$row = $dbresult1->FetchRow();
	$prix_unitaire = $row['prix_unitaire'];
	$prix_total = $new_qte*$prix_unitaire;
	$query = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = ?, prix_total = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($new_qte,$prix_total,$record_id));
}
if($dbresult)
{
	$this->SetMessage('Stock modifié');
	$this->RedirectToAdminTab('stock');
}


#
#EOF
#
?>