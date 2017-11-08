 <?php
if( !isset($gCms) ) exit;


$db =& $this->GetDb();
$id_client = '';
if(isset($params['client']) && $params['client'] != '')
{
	$id_client = $params['client'];
}

//debug_display($params, 'Parameters');
require_once(dirname(__FILE__).'/include/preferences.php');
$service = new commandes_ops();
$liste_fournisseurs = $service->liste_fournisseurs_sans_description();
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
