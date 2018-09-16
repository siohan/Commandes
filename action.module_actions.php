<?php

if( !isset($gCms) ) exit;


if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db = cmsms()->GetDb();
$commandes = new commandes_ops;
if( isset($params['record_id']) && $params['record_id'] != '')
{
	switch($params['obj'])
	{
		case "delete" : 
			$commandes->delete_item($params['record_id']);
		break;
		
		case "reçue":
			$commandes->change_status_cf_items($params['record_id']);
			$commandes->change_status_cc_items($params['record_id']);
			//attention il faut faire plusieurs choses !
			//&- incrémenter l'article ds le stock
			//2 - signaler au client que son article est bien arrivé
			$details = $commandes->details_commande_items($params['record_id']);
			//le produit en déjà en stock ?
			$en_stock = $commandes->en_stock($details['libelle_commande'], $details['ep_manche_taille'],$details['couleur']);
			if(true === $en_stock)
			{
				$commandes->incremente_stock($details['libelle_commande'], $details['quantite'],$details['ep_manche_taille'],$details['couleur']);
			}
			else
			{
				$commandes->met_en_stock($details['libelle_commande'], $details['categorie_produit'], $details['fournisseur'], $details['quantite'], $details['ep_manche_taille'], $details['couleur'], $details['prix_total']);
			}
			//maintenant on créé la nouvelle commande
			$commande_number = $this->random_string(15);
			
			//on prépare une commande pour l'article manquant
			$query = "UPDATE ".cms_db_prefix()."module_commandes_cf_items SET commande_number  = ? WHERE id_items = ?";
			$db->Execute($query, array($commande_number, $params['record_id']));
			
			//on fait pareil pour la table cc_items
			$query = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande_number  = ? WHERE id_items = ?";
			$db->Execute($query, array($commande_number, $params['record_id']));
			$commandes->add_cc($commande_number, $details['client'], $details['libelle_commande'], $details['fournisseur'], $details['prix_total'] );
			
			//on créé un paiement
			$libelle_commande = 'Commande '.$details['fournisseur'];
			$paiements_ops = new paiementsbis();
			$module = 'Commandes';
			$add_paiement = $paiements_ops->add_paiement($details['client'],$commande_number,$module,$libelle_commande,$details['prix_total']);
			
			//maintenant, on envoie un mail au client
			$contact = new contact;
			$email_client = $contact->email_address($details['client']);//on récupère l'email du possesseur de l'article
			if(FALSE !== $email_client)
			{
				$send = $commandes->send_mail_alerts($email);
			}
				
			$this->SetMessage('Article mis à Reçu');
			$this->Redirect($id, 'defaultadmin', $returnid);
		break;
	}
}
