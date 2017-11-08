<?php
class commandes_ops
{
	function __construct() {}
	
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
	
	
	public static function incremente_stock ($libelle_commande, $quantite, $ep_manche_taille,$couleur)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		
		//on fait un update
		$query3 = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = quantite + ? WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
		$dbresult3 = $db->Execute($query3, array($quantite,$libelle_commande, $ep_manche_taille, $couleur));
		
		if(!$dbresult3)
		{
			echo $db->ErrorMsg();
		}
		
	
	}
	public static function met_en_stock ($libelle_commande, $quantite, $ep_manche_taille,$couleur)
	{
		//cette fonction incrémente ou décrémente le stock
		//on va chercher si le produit existe en stock
		$db = cmsms()->GetDb();
		
		//on fait un update
		$query3 = "UPDATE ".cms_db_prefix()."module_commandes_stock SET quantite = quantite + ? WHERE libelle_commande = ? AND ep_manche_taille = ? AND couleur = ?";
		$dbresult3 = $db->Execute($query3, array($quantite,$libelle_commande, $ep_manche_taille, $couleur));
		
		if(!$dbresult3)
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
/**/	
public function send_mail_alerts($email)
	{
		// See if we have to send something
		

	

			// Process with the mails
			$ping = cms_utils::get_module('Commandes');
			$cmsmailer = cms_utils::get_module('CMSMailer');
			if (!$cmsmailer) return false;

			$cmsmailer->reset();
			$cmsmailer->IsHTML(true);

			// Get the subject
			$subject = $ping->GetPreference('email_activation_subject');
			// The body
			$body = $ping->GetTemplate('newactivationemail_Sample');
			$body = $ping->ProcessTemplateFromData($body);
			
			$cmsmailer->SetSubject($subject);
			$cmsmailer->SetBody($body);

			// Add the addresses
			// Try to find an e-mail
			
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
		$dbresult = $db->Execute($query, array($client_id));
		$row2 = $dbresult2->FetchRow();
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
	
#
#End of class
#
}
?>