<?php
if( !isset($gCms)) exit;

if(isset($params['commande_number']) && $params['commande_number'] != '')
{
	$commande_number = $params['commande_number'];
}
$query = "UPDATE ".cms_db_prefix()."module_commandes_cc SET user_validation =  1 WHERE commande_number = ?";
$dbresult= $db->Execute($query, array($commande_number));

$this->Redirect($id, 'default',$returnid);