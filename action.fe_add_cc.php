 <?php
if( !isset($gCms) ) exit;


$db =& $this->GetDb();
//on récupère le id de la base Commandes
//on a l'email
//on peut récupérer les infos du user
//on va montrer un lie pour ajouter une nouvelle commande
//mais surtout les commandes passées s'il y en a
//echo "on continue";
//echo $email;
$id_client = '';
if(isset($params['client']) && $params['client'] != '')
{
	$id_client = $params['client'];
}
/*
else
{
	$error++;
}
$query = "SELECT licence AS id_client, nom FROM ".cms_db_prefix()."module_commandes_clients WHERE email LIKE ?";
$dbresult = $db->Execute($query, array($email));
if($dbresult)
{
	$row = $dbresult->FetchRow();
	$id_client = $row['id_client'];
}
*/

//debug_display($params, 'Parameters');
require_once(dirname(__FILE__).'/include/preferences.php');
$now = date('Y-m-d');
$statut_commande = 'En cours de traitement';//valeur par défaut
$mode_paiement = "Aucun";//Statut par défaut
$inline = 0;

//s'agit-il d'une modif ou d'une créa ?
$record_id = '';
$index = 0;
//var_dump($items_paiement);
$commande_number = $this->random(15);
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('fe_add_cc.tpl'),null,null,$smarty);
$tpl->assign('form_start',$this->CGCreateFormStart($id,'fe_do_add_cc',$returnid,$params,$inline));
$tpl->assign('display', 'do_add_cc');
$tpl->assign('commande_number', $commande_number);
$tpl->assign('client', $id_client);
$tpl->assign('fournisseur', $liste_fournisseurs);
$tpl->assign('form_end', $this->CreateFormEnd());

$tpl->display();
#
# EOF
#
?>
