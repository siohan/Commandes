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

if(isset($params['record_id']) && $params['record_id'] != '')
{
	$edit = 1;
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
	$commande_number = $this->random(15);
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
else
{
	$statut_CF = "Non envoyée";
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
	
	$query1 = "INSERT INTO ".cms_db_prefix()."module_commandes_cf (commande_number, date_created, fournisseur,  statut_CF) VALUES (?, ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($commande_number,$now,$fournisseur, $statut_CF));
	$designation.=" Commande ajoutée";
	$this->SetMessage($designation);
	$this->Redirect($id, 'view_order_cf',$returnid,array("active_tab"=>"commandesfournisseurs","fournisseur"=>$fournisseur,"record_id"=>$commande_number, "date_created"=>$now));
}
else
{
	//il s'agit d'une mise à jour !	//on regarde aussi si le statut est égal à "Reçue"
	
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cf SET statut_CF = ? WHERE commande_number = ?";
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
	
	/*ci dessous, si la commande fournisseur est reçue ou envoyée on va chercher à mettre 
	le statut reçue également aux commandes clients avec le même fournisseur
	*/
	$service = new commandes_ops;
	if($statut_CF =="Envoyée")
	{
		$adh_ops = new contact;
		//le statut de la commande est donc "Envoyée" ou "Reçue" 
		//on créé une variable commande_number pour chaque client
		//on va modifier le statut en cascade
		
		$query6 = "SELECT  id_items FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
		$dbresult6 = $db->Execute($query6, array($record_id));
		if($dbresult6 && $dbresult6->RecordCount()>0)
		{
			while($row= $dbresult6->FetchRow())
			{
								
				$id_items = $row['id_items'];
				//$commande_number = $row['commande_number'];
				//on met le champ à 1 pour ne plus pouvoir  re-commander les articles par erreur	
				$query = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande = '1' WHERE id = ?";
				$dbresult = $db->Execute($query, array($id_items));			
					
			} 
			//tout se passe bien on peut envoyer des emails
			$query = "SELECT DISTINCT client FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ? ";
			$dbresult = $db->Execute($query, array($record_id));
			if($dbresult && $dbresult->recordCount()>0)
			{
				while($row = $dbresult->FetchRow())
				{
					$client = $row['client'];
					$email = $adh_ops->email_address($client);
						
					if(FALSE !== $email)
					{
						$send = $service->send_mail_alerts($email);
						
					}
				
					
					
					
				}
			}
			
			//on envoie au module de paiement ?
						
		}
		else
		{
			//la commande ne contient pas d'articles on refuse l'envoi !!
			echo "pb avec dbresult6";
		}
		$this->Redirect($id, 'defaultadmin', $returnid);
		
	}
	elseif($statut_CF == "Reçue")
	{
		
			//on sélectionne 
			$query = "SELECT  id_items, client FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
			$dbresult = $db->Execute($query, array($record_id));
			if($dbresult && $dbresult->RecordCount()>0)
			{
				while($row= $dbresult6->FetchRow())
				{
					$client = $row['client'];
					//on va chercher les articles de la commande pour les mettre en paiement et incrémenter le stock
					$details = $service->details_commande($client);
					$a_qui = $details['client'];
					$libelle_commande = $details['libelle_commande'];
					$fournisseur = $details['fournisseur'];

					if($libelle_commande =='')
					{
						$libelle_commande = 'Commande '.$fournisseur;
					}

					$prix_total = $details['prix_total'];
					$paiements_ops = new paiementsbis();
					$module = 'Commandes';
					$add_paiement = $paiements_ops->add_paiement($a_qui,$commande_number,$module,$libelle_commande,$prix_total);
					
					//Pour incrémenter le stock on prend le détail des articles de la commande
					
					
					$query2 = "SELECT libelle_commande,ep_manche_taille,couleur FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ? AND commande = 1";
					$dbresult2 = $db->Execute($query2, array($commande_number));
					
					if($dbresult2 && $dbresult2->RecordCount()>0)
					{
						while($row2 = $dbresult2->FetchRow())
						{
							$row;
							// un produit similaire est en stock, on incrémente la quantité
							$en_stock = $service->en_stock($libelle_commande,$ep_manche_taille,$couleur);
							if($en_stock == "True")
							{
								$increment = $service->incremente_stock($libelle_commande,$quantite,$ep_manche_taille,$couleur);
							}
							else //produit pas existant en stock, on le crée...
							{
								//on incrémente différemment
								$query5 = "INSERT INTO ".cms_db_prefix()."module_commandes_stock (id, id_items, fk_id, libelle_commande,categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
								$dbresult5 = $db->Execute($query5, array($id_items,$fk_id, $libelle_commande, $categorie_produit, $fournisseur, $quantite, $ep_manche_taille, $couleur, $prix_total));

							}
							$designation.=" Stock modifié";
						}
					}
					
					
					
					
				}	
					$designation.= "Statut mis à Reçue";
			
			}
			
			
			//tout se passe bien on peut envoyer des emails
			$query = "SELECT DISTINCT commande_number FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ? ";
			$dbresult = $db->Execute($query, array($record_id));
			if($dbresult && $dbresult->recordCount()>0)
			{
				while($row = $dbresult->FetchRow())
				{
					$commande_number = $row['commande_number'];
					//a qui appartient cette commande ?
					$client = $service->customer($commande_number);
					$adh_ops = new contact;
					if(FALSE !== $client)
					{
						$email = $adh_ops->email_address($client);
						
						if(FALSE !== $email)
						{
							$send = $service->send_mail_alerts($email);
						}
					}
					
					
					
				}
			}

		
	}
	else
	{
		//juste une redirection à faire...
		$this->RedirectToAdminTab('commandesfournisseurs');
	}
	
	/*
	
	$details = $service->details_commande($commande_number);
	$a_qui = $details['client'];
	$libelle_commande = $details['libelle_commande'];
	$fournisseur = $details['fournisseur'];
	
	if($libelle_commande =='')
	{
		$libelle_commande = 'Commande '.$fournisseur;
	}
	
	$prix_total = $details['prix_total'];
	$paiements_ops = new paiementsbis();
	$module = 'Commandes';
	$add_paiement = $paiements_ops->add_paiement($a_qui,$commande_number,$module,$libelle_commande,$prix_total);
	$this->SetMessage($designation);
	
	*/
}
#
# EOF
#
?>