<?php
if( !isset($gCms) ) exit;
$db =& $this->GetDb();
$error = 0;//on instancie un compteur d'erreurs
//on commence par vérifier que ts les éléments sont là
if(isset($params['commande_number']) && $params['commande_number'] != '' )
{
	$commande_number = $params['commande_number'];
	
}
else
{
	$error++;
}
if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}
else
{
	$error++;
}
if($error <1)
{
	$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ? AND commande_number = ?";
	$dbresult = $db->Execute($query, array($record_id, $commande_number));
	if($dbresult)
	{
		$this->SetMessage('Article supprimé');
		$this->Redirect($id, 'default', $returnid, array("display"=>"view_cc", "commande_number"=>$commande_number));
	}
}
else
{
	$this->SetMessage('une erreur est survenue');
	$this->Redirect($id, 'default', $returnid, array("display"=>"view_cc", "commande_number"=>$commande_number));
}
#
#EOF
#
?>