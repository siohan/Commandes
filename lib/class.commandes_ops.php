<?php
class commandes_ops
{
	function __construct() {}
	
	function details_cc($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT it.id AS item_id, it.fk_id , it.date_created,it.libelle_commande,it.ep_manche_taille, it.couleur, it.categorie_produit, it.fournisseur,it.quantite, it.prix_total, it.statut_item,it.commande,it.commande_number FROM ".cms_db_prefix()."module_commandes_cc_items AS it WHERE  it.commande_number = ? ";
		$dbresult= $db->Execute($query,array($commande_number));
		if($dbresult && $dbresult->recordCount()>0)
		{
			$details_cc = array();
			while($row = $dbresult->FetchRow())
			{
				$item_id= $row['item_id'];
				$id_commandes = $row['fk_id'];
				$commande = $row['commande']; //gère si l'item doit être modifiable ou non
				$commande_id= $row['fk_id'];
				$commande_number= $row['commande_number'];
				$date_created = $row['date_created'];
				$libelle_commande = $row['libelle_commande'];
				$categorie_produit = $row['categorie_produit'];
				$fournisseur = $row['fournisseur'];
				//$onerow->prix_unitaire = $row['prix_unitaire'];
				$quantite = $row['quantite'];
				$ep_manche_taille = $row['ep_manche_taille'];
				$couleur = $row['couleur'];
				$reduction = $row['reduction'];
				$prix_total = $row['prix_total'];
				$statut = $row['statut_item'];
			}
			return $details_cc;
		}
	}	
	public static function en_stock($libelle_commande,$ep_manche_taille,$couleur)
	{
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		$query = "SELECT quantite FROM ".cms_db_prefix()."module_commandes_stock WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
		$dbresult = $db->Execute($query, array($libelle_commande, $ep_manche_taille, $couleur));
		$nb_records = $dbresult->RecordCount();
		//plusieurs cas
		
		if($nb_records > 0)
		{
			//le produit est en stock
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	//increment le stock pour un article déjà existant en bdd
	public static function incremente_stock ($libelle_commande, $quantite, $ep_manche_taille,$couleur)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		
		//on fait un update
		$query = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = quantite + ? WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
		$dbresult = $db->Execute($query, array($quantite,$libelle_commande, $ep_manche_taille, $couleur));
		
		if(!$dbresult)
		{
			echo $db->ErrorMsg();
		}
		
	
	}
	//incrémente le stock avec un nouveau produit non existant en bdd
	public static function met_en_stock ($libelle_commande, $categorie_produit,$fournisseur, $quantite, $ep_manche_taille,$couleur,$prix_total)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		
		//on fait un update
		$query = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total ) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$dbresult = $db->Execute($query, array($libelle_commande, $categorie_produit, $fournisseur, $quantite,$ep_manche_taille, $couleur, $prix_total));
		
		if(!$dbresult)
		{
			echo $db->ErrorMsg();
		}
		
	
	}
	public static function decremente_stock ($libelle_commande, $quantite, $ep_manche_taille,$couleur)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		
		//on fait un update
		$query3 = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = quantite - ? WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
		$dbresult3 = $db->Execute($query3, array($quantite,$libelle_commande, $ep_manche_taille, $couleur));
		
		if(!$dbresult3)
		{
			echo $db->ErrorMsg();
		}
		else
		{return true;}
		
	
	}
	public static function decremente_stock_commande ($ref_action)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		$commandes_ops = new commandes_ops();
		//on va chercher les infos de la ref-action pour faire l'update
		$query = "SELECT libelle_commande, quantite, ep_manche_taille, couleur FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$error = 0;
			while($row = $dbresult->FetchRow())
			{
				$libelle_commande = $row['libelle_commande'];
				$quantite = $row['quantite'];
				$ep_manche_taille = $row['ep_manche_taille'];
				$couleur = $row['couleur'];
				
				$decremente = $commandes_ops->decremente_stock($libelle_commande, $quantite, $ep_manche_taille,$couleur);
				if(false === $decremente)
				{
					$error++;
				}
			
			}
			if($error>0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		//$commandes_ops->refresh_stock();
		
	
	}
	public static function refresh_stock()
	{
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE quantite <= 0";
		$dbresult = $db->Execute($query);
	}
	public static function montant_stock()
	{
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(prix_total) AS montant FROM ".cms_db_prefix()."module_commandes_stock";
		$dbresult = $db->Execute($query);
		$row = $dbresult->FetchRow();
		$montant =$row['montant'];
		return $montant;
	}
	//supprime un article de la table cf_items
	function delete_item ($record_id)
	{
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_items = ?";
		$db->Execute($query, array($record_id));
	}
/**/	
public function send_mail_alerts($email)
	{
		// Process with the mails
			$ping = cms_utils::get_module('Commandes');
			$cmsmailer = new \cms_mailer;//cms_utils::get_module('CMSMailer');
			if (!$cmsmailer) return false;

			$cmsmailer->reset();
			$cmsmailer->IsHTML(true);
		//	$cmsmailer->SetFrom($ping->GetPreference('admin_email'));
			// Get the subject
			$subject = $ping->GetPreference('new_status_subject');
			// The body
			$body = $ping->GetTemplate('newstatusemail_Sample');
			$body = $ping->ProcessTemplateFromData($body);
			
			$cmsmailer->SetSubject($subject);
			$cmsmailer->SetBody($body);
			$cmsmailer->AddAddress($email);
			$cmsmailer->Send();
			$res = true;
			if ($cmsmailer->IsError())
			{
				$res = false;
				@trigger_error('Problem sending email: '.$cmsmailer->GetErrorInfo());
			}
			$cmsmailer->reset();

			return $res;
	}
	function liste_fournisseurs()
	{
		$db = cmsms()->GetDb();
		$query = "SELECT CONCAT_WS(' : ', nom_fournisseur, description) AS boutique, nom_fournisseur FROM ".cms_db_prefix()."module_commandes_fournisseurs WHERE actif = 1";
		$dbresult = $db->Execute($query);
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[$row['boutique']] = $row['nom_fournisseur'];				
				
			}
			return $retour;
		}
	}
	function liste_fournisseurs_sans_description()
	{
		$db = cmsms()->GetDb();
		$query = "SELECT nom_fournisseur AS boutique FROM ".cms_db_prefix()."module_commandes_fournisseurs WHERE actif = 1";
		$dbresult = $db->Execute($query);
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[$row['boutique']] = $row['boutique'];				
				
			}
			return $retour;
		}
	}
	
	function get_record_id($licence)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id FROM ".cms_db_prefix()."module_adherents_adherents WHERE licence = ?";
		$dbresult = $db->Execute($query, array($licence));
		$row = $dbresult->FetchRow();
		$record_id = $row['id'];
		return $record_id;
	}
	function nb_commandes_per_user($licence)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT count(*) AS nb_commandes  FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ?";
		$dbresult = $db->Execute($query, array($licence));
		$row = $dbresult->FetchRow();
		$nb_commandes = $row['nb_commandes'];
		return $nb_commandes;
	}
	function details_commande($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT client,libelle_commande, fournisseur,prix_total  FROM ".cms_db_prefix()."module_commandes_cc WHERE commande_number = ?";
		$dbresult = $db->Execute($query, array($commande_number));
		$row = $dbresult->FetchRow();
		$details['client'] = $row['client'];
		$details['libelle_commande'] = $row['libelle_commande'];
		$details['fournisseur'] = $row['fournisseur'];
		$details['prix_total'] = $row['prix_total'];
		return $details;
	}
	//récupère le détail d'un article commandé depuis son id
	function details_commande_items($record_id)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT fk_id,libelle_commande,ep_manche_taille,couleur, categorie_produit,quantite, fournisseur,prix_total  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ? AND commande = 2";
		$dbresult = $db->Execute($query, array($record_id));
		$row = $dbresult->FetchRow();
		$details['client'] = $row['fk_id'];
		$details['libelle_commande'] = $row['libelle_commande'];
		$details['fournisseur'] = $row['fournisseur'];
		$details['prix_total'] = $row['prix_total'];
		$details['ep_manche_taille'] = $row['ep_manche_taille'];
		$details['couleur'] = $row['couleur'];
		$details['categorie_produit'] = $row['categorie_produit'];
		$details['quantite'] = $row['quantite'];
		return $details;
	}
	//récupère le client de l'adhérent par le numéro de commande
	function customer($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT client FROM ".cms_db_prefix()."module_commandes_cc WHERE commande_number = ?";
		$dbresult = $db->Execute($query, array($commande_number));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$licence = $row['client'];
			return $licence;
		}
		else
		{
			return false;
		}
	}
	
	function change_statut_cc($statut, $commande_number)
	{
		$db = cmsms()->getDb();
		$query = " UPDATE ".cms_db_prefix()."module_commandes_cc SET statut_commande = ? WHERE commande_number = ?";
		$dbresult  = $db->Execute($query, array($statut, $commande_number));
	}
	//change le statut à "Reçue" (commande = 2) ds la table cc_items pour chq id d'article
	function change_status_cc_items($record_id)
	{
		$db = cmsms()->getDb();
		$query = " UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '2' WHERE id = ?";
		$dbresult  = $db->Execute($query, array($record_id));
	}
	//change le statut ds la table cc_items pour chaq commande_number
	function cc_items_status($statut,$record_id)
	{
		$db = cmsms()->getDb();
		$query = " UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = ? WHERE commande_number = ?";
		$dbresult  = $db->Execute($query, array($statut,$record_id));
	}
	//change le statut à "Reçue" (received = 1) d'un article ds la table cf_items
	function change_status_cf_items($record_id)
	{
		$db = cmsms()->getDb();
		$query = " UPDATE ".cms_db_prefix()."module_commandes_cf_items SET received = '1' WHERE id_items = ?";
		$dbresult  = $db->Execute($query, array($record_id));
	}
	//calcule le montant de chq commande client
	function montant_commande($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(prix_total) AS total FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
		$dbresult = $db->Execute($query, array($commande_number));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$montant= $row['total'];				
				
			}
			return $montant;
		}
	}
	//calcule le nb d'articles de chaque commande client
	function items_per_commande($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT count(quantite) AS qt FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
		$dbresult = $db->Execute($query, array($commande_number));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$quantite= $row['qt'];				
				
			}
			return $quantite;
		}
	}
	//calcule le nb d'articles de chaque commande fournisseur
	function nb_items_cf($id_CF)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT count(id_items) AS qt FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
		$dbresult = $db->Execute($query, array($id_CF));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$quantite= $row['qt'];				
				
			}
			return $quantite;
		}
	}
	//calcule le montant total de chq commande fournisseur
	function montant_commande_cf($id_CF)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(prix_total) AS total FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
		$dbresult = $db->Execute($query, array($id_CF));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$montant= $row['total'];				
				
			}
			return $montant;
		}
	}
	//renvoie vrai s'il manque des articles ds une commande cf
	function is_missing($commande_number)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT count(id_items) as total FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ? AND received = '0'";
		$dbresult = $db->Execute($query, array($commande_number));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$total = $row['total'];
			if($total > 0)
			{
				return true; //il manque des articles !!
			}
			else
			{
				return false;
			}
		}
	}
	//détermine l'id de l'article manquant d'une commande
	function missing_items($id_items)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id_items FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_items = ? AND received = '0'";
		$dbresult = $db->Execute($query, array($commande_number));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				//on va d'abord changer le stautut de l'item ds la table cc_items (commande = 0)
				$id_items = $row['id_items'];
				
			}
			
		}
	}
	//ajoute une commande client (table cc)
	function add_cc($commande_number, $client,$libelle_commande, $fournisseur, $prix_total)
	{
		$db = cmsms()->GetDb();
		$now = date('Y-m-d');
		$query = "INSERT INTO ".cms_db_prefix()."module_commandes_cc (commande_number, date_created, date_modified, client, libelle_commande, fournisseur, prix_total) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$db->Execute($query, array($commande_number, $now, $now, $client, $libelle_commande, $fournisseur, $prix_total));
	}

	
#
#End of class
#
}
?>