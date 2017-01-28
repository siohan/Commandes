<?php
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

$db =& $this->GetDb();
$error = 0;//on instancie un compteur d'erreurs
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
	//echo "le record_id est :".$record_id;
}

if(isset($params['fournisseur']) && $params['fournisseur'] !='')
{
	$fournisseur = $params['fournisseur'];
	//echo "le record_id est :".$record_id;
}
else
{
	$error++;
}
if(isset($params['commande_number']) && $params['commande_number'] !='')
{
	$commande_number = $params['commande_number'];
	//echo "le record_id est :".$record_id;
}
else
{
	$error++;
}

$result= array ();
$query1 = "SELECT cc.id,cc.user_validation,cc.commande_number,it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande FROM ".cms_db_prefix()."module_commandes_cc as cc, ".cms_db_prefix()."module_commandes_cc_items AS it WHERE cc.commande_number = it.commande_number AND cc.commande_number = ? ";


	//$query .=" ORDER BY id DESC";
	//echo $query1;
	$dbresult= $db->Execute($query1,array($commande_number));
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->item_id= $row['item_id'];
				$id_commandes = $row['fk_id'];
				$user_validation = $row['user_validation'];
				$commande_number = $row['commande_number'];
				$commande = $row['commande']; //gère si l'item doit être modifiable ou non
				$fournisseur = $row['fournisseur'];
				$onerow->commande_id= $row['fk_id'];
				$onerow->date_created = $row['date_created'];
				$onerow->libelle_commande = $row['libelle_commande'];
				$onerow->categorie_produit = $row['categorie_produit'];
				$onerow->fournisseur = $row['fournisseur'];
				//$onerow->prix_unitaire = $row['prix_unitaire'];
				$onerow->quantite = $row['quantite'];
				$onerow->ep_manche_taille = $row['ep_manche_taille'];
				$onerow->couleur = $row['couleur'];
				//$onerow->reduction = $row['reduction'];
				$onerow->prix_total = $row['prix_total'];
				$onerow->statut = $row['statut_item'];
				
				if($user_validation == 0)
				{
					$onerow->edit = $this->CreateLink($id, 'default', $returnid,'Modifier', array("display"=>"add_cc_items","record_id"=>$row['item_id'], "commande_number"=>$commande_number,"edit"=>"1" ));
					$onerow->delete = $this->CreateLink($id, 'default', $returnid,'Supprimer', array("display"=>"delete","record_id"=>$row['item_id'], "commande_number"=>$commande_number ));
				}
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}
		$smarty->assign('user_validation', $user_validation);
		$smarty->assign('validate',
			$this->CreateLink($id, 'default',$returnid, 'Terminer ma commande', array("display"=>"validate", "commande_number"=>$commande_number),$warn_message="Votre commande va devenir définitive : vous ne pourrez plus la modifier."));
		$smarty->assign('new_command', 
			$this->CreateLink($id, 'default', $returnid, 'Ajouter un article',array("display"=>"add_cc_items","commande_number"=>$commande_number, "commande_id"=>$row['fk_id'],"fournisseur"=>$fournisseur)));
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		$smarty->assign('lienRetour',
		$this->CreateReturnLink($id, $returnid, '<< Revenir'));
		$smarty->assign('commande_num', $commande_number);
		


echo $this->ProcessTemplate('fe_view_order.tpl');


#
# EOF
#
?>