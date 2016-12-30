<?php
if( !isset($gCms) ) exit;

$feu = cms_utils::get_module('FrontEndUsers');
$userid = $feu->LoggedInId();
$properties = $feu->GetUserProperties($userid);
$email = $feu->LoggedInEmail();
//var_dump($email);
if($email == '')
{
	echo "pas de résultats, on fait quoi ?";
	//on redirige vers le formulaire de login
	$feu->Redirect($id,"login",$returnid);
	exit;
}

$db =& $this->GetDb();

if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
	echo "le record_id est :".$record_id;
}
else
{
	echo "Une erreur est arrivée !";
}

$result= array ();
$query1 = "SELECT cc.id,it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande FROM ".cms_db_prefix()."module_commandes_cc as cc, ".cms_db_prefix()."module_commandes_cc_items AS it WHERE cc.id = it.fk_id AND cc.id = ? ";


	//$query .=" ORDER BY id DESC";
	echo $query1;
	$dbresult= $db->Execute($query1,array($record_id));
	
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
				$commande = $row['commande']; //gère si l'item doit être modifiable ou non
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
				
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('fe_view_order.tpl');


#
# EOF
#
?>