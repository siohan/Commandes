<?php
//le message de validation de la commande
if( !isset($gCms) ) exit;

$feu = cms_utils::get_module('FrontEndUsers');
$userid = $feu->LoggedInId();
$properties = $feu->GetUserProperties($userid);
$email = $feu->LoggedInEmail();
//var_dump($email);
if($email == '')
{
	//echo "pas de résultats, on fait quoi ?";
	//on redirige vers le formulaire de login
	$feu->Redirect($id,"login",$returnid);
	exit;
}
$error = 0;//un compteur d'erreurs...
if(isset($params['commande_number']) && $params['commande_number'] !='')
{
	$commande_number = $params['commande_number'];
	//echo "le record_id est :".$record_id;
}
else
{
	$error++;
}
$smarty->assign('commande_number', $commande_number);
$smarty->assign('retour_compte',
		$this->CreateLink($id, 'moncompte', $returnid, 'Retour à mon compte'));
echo $this->ProcessTemplate('validate_message.tpl');
#
#EOF
#
?>