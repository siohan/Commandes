<?php
if (!isset($gCms)) exit;
//debug_display($params,'Parameters');
$feu = cms_utils::get_module('FrontEndUsers');
$userid = $feu->LoggedInId();
$username = $feu->GetUserName($userid);
//echo "FEU : le user est : ".$username." ".$userid;
//$properties = $feu->GetUserProperties($userid);
//$email = $feu->LoggedInEmail();
//echo $email;
//var_dump($email);
$designation = '';
if($username == '')
{
	//echo "pas de résultats, on fait quoi ?";
	//on redirige vers le formulaire de login
	$feu->Redirect($id,"login",$returnid);
	exit;
}

	//on a l'email
	//on peut récupérer les infos du user
	//on va montrer un lie pour ajouter une nouvelle commande
	//mais surtout les commandes passées s'il y en a
	//echo "on continue";
	//echo $email;
	$query = "SELECT id AS client, nom, email, tel, portable FROM ".cms_db_prefix()."module_commandes_clients WHERE licence LIKE ?";
	$dbresult = $db->Execute($query, array($username));
	
	if($dbresult && $dbresult->recordCount() >0)
	{
		$row = $dbresult->FetchRow();
		$client = $row['client'];
		$nom = $row['nom'];
		echo "Bonjour ".$nom;
		//le id est : ".$id_client;
		//deuxième requete pour trouver les commandes
		$query2 = "SELECT id AS commande_id, date_created, libelle_commande, user_validation, commande_number,fournisseur, prix_total, statut_commande, paiement, mode_paiement FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ?";
		$dbresult2 = $db->Execute($query2, array($client));
		//echo $query2;
		if($dbresult2 && $dbresult2->RecordCount()>0)
		{
			$rowarray= array();
			$rowclass = '';
			
			while($row = $dbresult2->FetchRow())
			{
				$user_validation = $row['user_validation'];
				$commande_number = $row['commande_number'];
				$onerow = new StdClass();
				$onerow->commande_id = $row['commande_id'];
				$onerow->date_created = $row['date_created'];
				$onerow->libelle_commande =  $row['libelle_commande'];
				$onerow->fournisseur = $row['fournisseur'];
				$onerow->prix_total = $row['prix_total'];
				$onerow->statut_commande = $row['statut_commande'];
				$onerow->paiement = $row['paiement'];
				$onerow->mode_paiement = $row['mode_paiement'];
				//$onerow->view = $this->CreateFrontendLink($id, $returnid,'feViewCc', $contents='Détails',array("record_id"=>$row['commande_id']),$warn_message='',$onlyhref='',$inline='true');
				$onerow->view = $this->CreateLink($id, 'feViewCc', $returnid,'Détails',array("record_id"=>$row['commande_id'],"commande_number"=>$commande_number,"fournisseur"=>$row['fournisseur']),$warn_message='',$onlyhref='',$inline='true');
				if($user_validation==0)
				{
					$onerow->fe_delete = $this->CreateLink($id, 'default', $returnid,'Supprimer',array("display"=>"delete","commande_number"=>$row['commande_number']),$warn_message='Tous les articles de cette commande seront également supprimés',$onlyhref='',$inline='true');
				}
				
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
			}
		}
		elseif($dbresult2->RecordCount() == 0)
		{
			//echo "Pas de commandes...";
		}
		else
		{
			echo $db->ErrorMsg();
		}
			$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
			$smarty->assign('itemcount', count($rowarray));
			$smarty->assign('items', $rowarray);
			$smarty->assign('fe_add_cc',
			$this->CreateLink($id, 'default', $returnid, 'Ajouter une nouvelle commande', array("client"=>$client,"display"=>"add_cc")));
		
	echo $this->ProcessTemplate('default1.tpl');
	}
	elseif($dbresult->RecordCount() == 0)
	{
		echo "Pas d\'utilisateur ayant cette adresse email";
		$designation.=" Adresse email non reconnue !";
		$this->SetMessage($designation);
		$feu->Redirect($id,"login",$returnid);
	}
	else
	{
		echo $db->ErrorMsg();
	}




/**/
//echo $this->ProcessTemplate('default1.tpl');
#
# EOF
#

?>