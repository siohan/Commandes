<?php
#-------------------------------------------------------------------------
# Module: Commandes
# Version: 0.3.5
# Method: Upgrade
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------

/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
*/ 
if (!isset($gCms)) exit;

$db = $this->GetDb();			/* @var $db ADOConnection */
$dict = NewDataDictionary($db); 	/* @var $dict ADODB_DataDict */
/**
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Upgrade() method in the module body.
 */
$now = trim($db->DBTimeStamp(time()), "'");
$current_version = $oldversion;
switch($current_version)
{
  // we are now 1.0 and want to upgrade to latest
 case "0.1":
	{
		//rajout d'un champ ds la table commandes_cc
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_cc", "commande_number C(15), user_validation I(1) DEFAULT 0");
		$dict->ExecuteSQLArray( $sqlarray );
		
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_cc_items", "commande_number C(15)");
		$dict->ExecuteSQLArray( $sqlarray );
		
		//rajout de deux champs ds la table commandes_clients
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_clients", "account_validation I(1) DEFAULT 0, email_sent I(1) DEFAULT 0");
		$dict->ExecuteSQLArray( $sqlarray );
		
		//on ajoute un index sur cette nouvelle colonne
		$idxoptarray = array('UNIQUE');
		$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'cc',
			    cms_db_prefix().'module_commandes_cc', 'commande_number',$idxoptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
		//il faut remplir les champs existants et les dépendances
		$query = "SELECT id, commande_number FROM ".cms_db_prefix()."module_commandes_cc WHERE commande_number IS NULL ";
		$dbresult = $db->Execute($query);
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$id = $row['id'];
				$commande_number = $this->random(15);
				//on fait une requete d'abord pour mettre à jour la table commandes_cc
				$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET commande_number = ? WHERE id = ?";
				$dbresult2 = $db->Execute($query2, array($commande_number,$id));
				
				$query3 = "SELECT fk_id FROM ".cms_db_prefix()."module_commandes_cc_items WHERE fk_id = ?";
				$dbresult3 = $db->Execute($query3, array($id));
				if($dbresult3 && $dbresult3->RecordCount()>0)
				{
					while($row3 = $dbresult3->FetchRow())
					{
						$query4 = "UPDATE ".cms_db_prefix()."module_commandes_cc_items SET commande_number = ? WHERE fk_id = ?";
						$dbresult4 = $db->Execute($query4, array($commande_number, $id));
					}
				}
			}
		}
		
		//emails
		$this->SetPreference('admin_email', 'root@localhost.com');
		$this->SetPreference('email_activation_subject','Ton compte T2T Commandes est activé');
		//pour les nouvelles commandes
		$this->SetPreference('new_command_subject','[T2T] Nouvelle commande !');
		# Mails templates
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_newcommandemailtemplate.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('newcommandemail_Sample',$template);
		}
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_activationemailtemplate.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('newactivationemail_Sample',$template);
		}
	}
	case "0.2" :
	{
		$this->RemovePermission('manage_commandes');
		$this->CreatePermission('Use Commandes', 'Utiliser le module Commandes');
		
		$this->SetPreference('new_command_subject','[T2T] Nouvelle commande !');
		# Mails templates
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_newcommandemailtemplate.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('newcommandemail_Sample',$template);
		}
	}//end case 0.2
	case "0.2.1" :
	{
		#
		$idxoptarray = array('UNIQUE');
		$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'items',
				    cms_db_prefix().'module_commandes_items', 'libelle, fournisseur',$idxoptarray);
		$dict->ExecuteSQLArray($sqlarray);
		#
	}
	case "0.2.2":
	{
		// table schema description
		$flds = "
			id I(20) AUTO KEY,
			nom_fournisseur C(255) ,
			description C(255),
			actif I(1),
			ordre I(11)";


		// create it. 
		$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_fournisseurs",
						   $flds, 
						   $taboptarray);
		$dict->ExecuteSQLArray($sqlarray);
		#
			$insert_sql = "INSERT INTO ".cms_db_prefix()."module_commandes_fournisseurs (`id`,`nom_fournisseur`, `description`, `actif`, `ordre`) VALUES ('', ?, ?, ?, ?)";
			$db->execute($insert_sql, array('WACK SPORT', 'Le catalogue Wack', 1, 10));
			$db->execute($insert_sql, array('BUTTERFLY', 'Le catalogue Butterfly', 1, 20));
   		//comme on supprime la table client on modifie les autres tables

		$query = "SELECT cl.id, cl.licence FROM ".cms_db_prefix()."module_commandes_clients AS cl, ".cms_db_prefix()."module_commandes_cc AS cc WHERE cl.id = cc.id";
		$dbresult = $db->Execute($query);
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$id = $row['id'];
				$licence = $row['licence'];
				$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET client = ? WHERE client = ?";
				$dbresult2 = $db->Execute($query2, array($licence,$id));
				
			}
		}
		//on ajoute un champ ds la table commandes_fournisseur
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_fournisseurs", "commande_number C(15)");
		$dict->ExecuteSQLArray( $sqlarray );
		
		//on ajoute ce parametre dans les tables existantes
 	}
	case "0.3" :
	case "0.3.1" :
	{
		//On supprime la table commandes_clients qui n'est plus ds l'install
		$sqlarray = $dict->DropTableSQL( cms_db_prefix()."module_commandes_clients");
		$dict->ExecuteSQLArray( $sqlarray );
	}
	
        case "0.3.2" :
	case "0.3.3" :
	{
		$this->SetPreference('new_status_subject','[T2T] Changement de statut de ta commande');
		# Mails templates
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_newstatusemailtemplate.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('newstatusemail_Sample',$template);
		}
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_cf_items", "commande_number C(15), client I(11), received I(1) DEFAULT 0");
		$dict->ExecuteSQLArray( $sqlarray );
		
		//on ajoute un champ user_validation ds la table cc_items
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_commandes_cc_items", "user_validation I(1) DEFAULT 0");
		$dict->ExecuteSQLArray( $sqlarray );
	}
	case "0.3.4" :
	{
		$dict = NewDataDictionary( $db );
		$fields = "id_CF C(15)";
		$sqlarray = $dict->AlterColumnSQL(cms_db_prefix()."module_commandes_cf_items", $fields);
		$dict->ExecuteSQLArray( $sqlarray );
		//on ajoute un événement
	//	$this->CreateEvent('CommandesItemAdded');
	}
// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
}
?>