<?php
#-------------------------------------------------------------------------
# Module: Commandes
# Version: 0.1
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
   
 }


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>