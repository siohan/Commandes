<?php
if( !isset($gCms) ) exit;
//echo "Cool !";
//debug_display($params, 'Parameters');

//$this->RedirectForFrontEnd($id, $returnid, 'default', array("nom"=>$client, "date_created"=>$date_created,"fournisseur"=>$fournisseur));

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
$display = 'default';
if(isset($params['display']) && $params['display'] !='')
{
	$display = $params['display'];
}
switch($display)
{
	case 'add_cc' :
		require(__DIR__.'/action.fe_add_cc.php');
	break;
	
	case 'do_add_cc' :
		
		if(isset($params['client']) && $params['client'] != '')
		{
			$client = $params['client'];
		}
		else
		{
			$error++;
		}

		$fournisseur = '';
		if(isset($params['fournisseur']) && $params['fournisseur'] != '')
		{
			$fournisseur = $params['fournisseur'];
		}
		else
		{
			$error++;
		}
		$commande_number = '';
		if(isset($params['commande_number']) && $params['commande_number'] != '')
		{
			$commande_number = $params['commande_number'];
		}
		else
		{
			$error++;
		}

		if($error < 1)
		{
			$statut_commande = "En cours de traitement";
			$paiement = "Non payée";
			$prix_total = 0;
			$date_created = date('Y-m-d');
			//on fait d'abord l'insertion 
			$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cc (id, date_created, date_modified, client,fournisseur, prix_total, statut_commande, paiement, commande_number) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
			$dbresult1 = $db->Execute($query1, array($date_created,$date_created,$client,$fournisseur,$prix_total, $statut_commande,$paiement,$commande_number));

			if($dbresult1)
			{
				$designation.=" Commande enregistrée, vous pouvez continuer";
			}
			else
			{
				$designation.= $db->ErrorMsg();
				echo $designation;
			}
			//$this->SetMessage($designation);
			$this->RedirectForFrontEnd($id, $returnid, 'default', array("display"=>"add_cc_items","commande_number"=>$commande_number,"fournisseur"=>$fournisseur));
			//$this->Redirect($id, 'default',$returnid,array("nom"=>$client, "date_created"=>$date_created,"fournisseur"=>$fournisseur));
		}
		else
		{
			$this->RedirectForFrontEnd($id, $returnid, 'default', array("display"=>"default","client"=>$client, "date_created"=>$date_created,"fournisseur"=>$fournisseur));
		}
		/**/
	break;
	case 'add_cc_items' :
		require(__DIR__.'/action.fe_add_cc_items.php');	
	break;
	
	case 'do_add_cc_items' :
		require(__DIR__.'/action.fe_do_add_cc_item.php');
	break;
	
	case 'view_cc' :
		//faire un lien pour rediriger vers une commande particulière
		require(__DIR__.'/action.feViewCc.php');
	break;
	
	case 'delete' :
		require(__DIR__.'/action.fe_delete.php');
	break;
	
	case 'validate' :
		require(__DIR__.'/action.fe_validate.php');
	break;
	
	case 'validation_message' :
		require(__DIR__.'/action.validate_message.php');
	break;
	
	case 'default' :
		require(__DIR__.'/action.moncompte.php');
	break;
	
	default:
	require(__DIR__.'/action.moncompte.php');
	break;
	
}
#
# EOF
#

?>