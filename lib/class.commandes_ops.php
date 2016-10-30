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
		
	
	}
	public static function refresh_stock()
	{
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_commandes_stock WHERE quantite = 0";
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
#
#End of class
#
}
?>