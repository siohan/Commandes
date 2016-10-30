<?php
#-------------------------------------------------------------------------
# Module: Commandes
# Version: 0.1, Claude SIOHAN 
# Method: Install
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------


if (!isset($gCms)) exit;


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */

$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	date_created D,
	date_maj D,
	nom C(255),
	prenom C(255),
	club C(255),
	email C(200),
	tel C(15),
	portable C(15)";
			
// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_clients",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	categorie C(50),
	fournisseur C(50),
	reference I(7),
	libelle C(255),
	marque C(200),
	prix_unitaire N(6.2),
	reduction I(3),
	statut_item C(55)";

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_items",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	date_created D,
	date_modified D,
	client C(50),
	libelle_commande C(255),
	fournisseur C(50),
	prix_total N(6.2),
	statut_commande C(55),
	paiement C(50),
	mode_paiement C(50),
	remarques C(255)";

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_cc",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);				
#
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	fk_id I(11),
	date_created D,
	date_modified D,
	reference I(8),
	libelle_commande C(255),
	categorie_produit C(100),
	fournisseur C(50),
	quantite I(3),
	prix_total N(6.2),
	statut_item C(55),
	commande I(1),
	ep_manche_taille C(50),
	couleur C(80)";

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_cc_items",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id_CF I(20) AUTO KEY,
	date_created D,
	fournisseur C(50),
	statut_CF C(55)";
	

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_cf",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(20) AUTO KEY,
	id_CF I(20) ,
	id_items I(20),
	prix_total N(6.2)";
	

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_cf_items",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
#
#
#
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	id_items I(11),
	fk_id I(11),
	libelle_commande C(255),
	categorie_produit C(255),
	fournisseur C(100),
	quantite I(4),
	ep_manche_taille C(50),
	couleur C(80),
	prix_total N(6.2)";

// create it. 
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_commandes_stock",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
#
#
#Indexes
//on créé un index sur la table div_tours
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'cf',
	    cms_db_prefix().'module_commandes_cf_items', 'id_items',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
#
# Les préférences 
$this->SetPreference('installation', '0');

//permissions
$this->CreatePermission('Use Commandes','Utiliser le module Commandes');

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>