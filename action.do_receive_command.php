<?php
if (!isset($gCms)) exit;
require_once(dirname(__FILE__).'/include/preferences.php');
//debug_display($params, 'Parameters');

$db =cmsms()->GetDb();
if (!$this->CheckPermission('Use Commandes'))
{
	$designation.=$this->Lang('needpermission');
	$this->SetMessage("$designation");
	$this->RedirectToAdminTab('clients');
}
if(isset($params['cancel']))
{
	$this->RedirectToAdminTab('commandesfournisseurs');
}
$now = date('Y-m-d');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
$designation = '';		
$adh_ops = new contact;		
$record_id = '';
if (isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}
else
{
	$error++;
}
		
if($error ==0)
{
	
	$com_ops = new commandes_ops;	
	$id_CF = '';
	if (isset($params['id_CF']) && $params['id_CF'] != '')
	{
		$id_CF = $params['id_CF'];
		$error++;
	}
	foreach($id_CF as $key=>$value)
	{
		
		$query4 = "UPDATE ".cms_db_prefix()."module_commandes_cf_items SET received = '1' WHERE id_items = ? ";
		$dbresult4 = $db->Execute($query4, array($key));
		
		//on change aussi le statut ds la table cc_items ?
		if($dbresult4)
		{
			$query5 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '2' WHERE id = ?";
			$dbresult5 = $db->Execute($query5, array($key));
			
			//on incremente le stock ?
			$details = $com_ops->details_commande_items($key);
			if(false !== $details)
			{
				$a_qui = $details['client'];
				$libelle_commande = $details['libelle_commande'];
				$details['libelle_commande'];
				$fournisseur = $details['fournisseur'];
				$prix_total = $details['prix_total'];
				$ep_manche_taille = $details['ep_manche_taille'];
				$couleur = $details['couleur'];
				$categorie_produit = $details['categorie_produit'];
				$quantite = $details['quantite'];
				
				$en_stock = $com_ops->en_stock($libelle_commande,$ep_manche_taille,$couleur);
				//var_dump($en_stock);
				if(true === $en_stock)
				{
					$increment = $com_ops->incremente_stock($libelle_commande,$quantite,$ep_manche_taille,$couleur);
				}
				else //produit pas existant en stock, on le crée...
				{
					//on incrémente différemment
				//	$com_ops->met_en_stock();
					$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
					$dbresult5 = $db->Execute($query5, array($libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));

				}
				$designation.=" Stock modifié";
			}
			
		}
		
	}
	
}
//on envoie un mail à tous les adhérents concernés
$service = new commandes_ops;
$query = "SELECT DISTINCT client FROM ".cms_db_prefix()."module_commandes_cf_items WHERE received = '1' AND id_CF = ?";
$dbresult = $db->Execute($query, array($record_id));
if ($dbresult && $dbresult->RecordCount()>0)
{
	// on prépare une commande ou une facture avec les articles
	while($row = $dbresult->FetchRow())
	{
		$client = $row['client'];
		$commande_number = $this->random_string(15);
		//
		//On modifie la table cf_items en mettant un enuméro de commande à chaque client différent
		$query6 = "UPDATE ".cms_db_prefix()."module_commandes_cf_items SET commande_number = ? WHERE id_CF = ? AND client = ? AND received = '1'";
		$db->Execute($query6, array($commande_number, $record_id, $client));
		
		//on modifie aussi la table cc_items
		$query = "SELECT id_items FROM ".cms_db_prefix()."module_commandes_cf_items WHERE commande_number = ?";
		$res = $db->Execute($query, array($commande_number));
		if($res && $res->RecordCount()>0)
		{
			while($row = $res->FetchRow())
			{
				$id_items = $row['id_items'];
				$query7 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande_number = ? WHERE id = ? AND commande = '2'";
				$res7 = $db->Execute($query7, array($commande_number, $id_items));
			}
		}
		//on insère ds la table cc
		//on va chercher à faire les calculs si besoin
		$montant = $com_ops->montant_commande($commande_number);
		$statut_commande = "Reçue";
		$user_validation = 1;
		$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cc ( commande_number, date_created, date_modified, client,fournisseur, prix_total, statut_commande, user_validation) VALUES (?, ?, ?, ?, ? ,?, ?, ?)";
		$dbresult1 = $db->Execute($query1, array($commande_number, $now,$now,$client, $fournisseur,$montant, $statut_commande, $user_validation));
		
		//on envoie un email
		$email = $adh_ops->email_address($client);
		var_dump($email);	
		if(FALSE !== $email)
		{
			$send = $service->send_mail_alerts($email);
		}
		else
		{
			
		}
		
		//$details = $com_ops->details_commande_items($commande_number);
		$libelle_commande = 'Commande '.$fournisseur;
		$paiements_ops = new paiementsbis();
		$module = 'Commandes';
		$add_paiement = $paiements_ops->add_paiement($a_qui,$commande_number,$module,$libelle_commande,$montant);
		
		
	}
}

//on change enfin le statut de la commande ds cf
$query = "UPDATE ".cms_db_prefix()."module_commandes_cf SET statut_CF = 'Reçue' WHERE commande_number = ?";
$db->Execute($query, array($record_id));	


//y a t-il des articles non recus ? Si oui que faire ?


$this->SetMessage('articles ajoutés à la commande fournisseur !');
$this->RedirectToAdminTab('commandesfournisseurs');

?>