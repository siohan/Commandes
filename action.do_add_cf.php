<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('commandesfournisseurs');
    		return;
  	}
//debug_display($params, 'Parameters');
$designation = '';
$db =& $this->GetDb();
$now = date('Y-m-d');
$designation = '';//le message final
$error = 0;//on initie un compteur d'erreur, 0 par défaut

if(isset($params['edition']) && $params['edition'] != '')
{
	$edit = $params['edition'];
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
}



if(isset($params['date_created']) && $params['date_created'] != '')
{
	$date_created = $params['date_created'];
}

if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}

if(isset($params['statut_CF']) && $params['statut_CF'] != '')
{
	$statut_CF = $params['statut_CF'];
}
if(isset($params['fournisseur']) && $params['fournisseur'] != '')
{
	$fournisseur = $params['fournisseur'];
}
else
{
	$fournisseur = 'Autres';
}



if($edit ==0)
{
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cf (id_CF, date_created, fournisseur,  statut_CF) VALUES ('', ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($date_created,$fournisseur, $statut_CF));
	$designation.=" Commande ajoutée";
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('commandesfournisseurs',array("active_tab"=>"commandesfournisseurs","fournisseur"=>$fournisseur, "date_created"=>$date_created),'view_order_CF');
}
else
{
	//il s'agit d'une mise à jour !
	//on regarde aussi si le statut est égal à "Reçue"
	
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cf SET statut_CF = ? WHERE id_CF = ?";
	//echo $query2."<br />";
	$dbresult2 = $db->Execute($query2, array( $statut_CF, $record_id));
	if(!$dbresult2)
	{
		//echo "Pb avec la requete 2";
	}
	else
	{
		$designation.= " Commande modifiée<br />";
		//echo $designation;
	}
	
	/*ci dessous, si la commande fournisseur est reçue, on va chercher à mettre 
	le statut reçue également aux commandes clients avec le même fournisseur
	*/
	$service = new commandes_ops();
	if($statut_CF == 'Reçue')
	{
		$query6 = "SELECT id, id_CF, id_items FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
		//echo $query6."<br />";
		$dbresult6 = $db->Execute($query6, array($record_id));
		//echo $dbresult6->RecordCount();
		if($dbresult6 && $dbresult6->RecordCount()>0)
		{
			while($row= $dbresult6->FetchRow())
			{
				$id_items = $row['id_items'];
				
				$query2 = "SELECT id AS id_items, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE id = ?";
				$dbresult2 = $db->Execute($query2, array($id_items));
				//echo $query2."<br />";
				
				
				
				if($dbresult2)
				{
					
					$row3 = $dbresult2->FetchRow();
					$id_items = $row3['id_items'];
					$fk_id = $row3['fk_id'];
					$libelle_commande = $row3['libelle_commande'];
					$categorie_produit = $row3['categorie_produit'];
					$fournisseur = $row3['fournisseur'];
					$quantite = $row3['quantite'];
					$ep_manche_taille = $row3['ep_manche_taille'];
					$couleur = $row3['couleur'];
					$prix_total = $row3['prix_total'];
					//echo "id_tems -> fk_id = ".$id_items." -> ".$fk_id."<br />";
					
					
					
					
					//on change le statut de la commandes clients				
					$query3 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET statut_commande = 'Reçue' WHERE id = ?";
					//echo $query3;
					$dbresult3 = $db->Execute($query3, array($fk_id));
					
					if($dbresult3)
					{
						$designation.= "Statut mis à Reçue";
						//echo $designation."<br />";
							
							
							$en_stock = $service->en_stock($libelle_commande,$ep_manche_taille,$couleur);
							if($en_stock == "True")
							{
								$increment = $service->incremente_stock($libelle_commande,$quantite,$ep_manche_taille,$couleur);
							}
							else
							{
								//on incrémente différemment
								$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
								$dbresult5 = $db->Execute($query5, array($id_items,$fk_id, $libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));
								
							}
							$designation.=" Stock modifié";
							
					}
					//on met le chp commande à 1,cad on ne peut plus recommander ces articles
					$query = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '1' WHERE id = ?";
					$dbresult = $db->Execute($query, array($id_items));

					if(!$dbresult)
					{
						//ça n'a pas marché, on envoie un message
						$designation.=" Le chp commandes n\'est pas à 1";
					}
					else
					{
						$designation.=" ";
					}
				}
				else
				{
					echo "pb avec dbresult2";
				}
			}
		}
		
	}
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('commandesfournisseurs', '', 'admin_cf_tab');
}
#
# EOF
#
?>