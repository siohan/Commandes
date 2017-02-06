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

if ( !defined('CMS_VERSION')) exit;
//if (!isset($gCms)) exit;


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */
$uid = get_userid();
$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	date_created D,
	date_maj D,
	licence I(11),
	nom C(255),
	prenom C(255),
	club C(255),
	email C(200),
	tel C(15),
	portable C(15),
	account_validation I(1) DEFAULT 0,
	email_sent I(1) DEFAULT 0";
			
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
	commande_number C(15),
	date_created D,
	date_modified D,
	client C(50),
	libelle_commande C(255),
	fournisseur C(50),
	prix_total N(6.2),
	statut_commande C(55),
	paiement C(50),
	mode_paiement C(50),
	user_validation I(1) DEFAULT 0,
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
	couleur C(80),
	commande_number C(15)";

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
$insert_sql = "INSERT INTO ".cms_db_prefix()."module_commandes_items (`id`,`categorie`, `fournisseur`, `reference`, `libelle`, `marque`, `prix_unitaire`, `reduction`, `statut_item`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 1095, 'RASANT GRIP', 'INDO', '47.95', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 05', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 05 FX', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 64', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SRIVER EL', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SRIVER FX', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 25', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SRIVER', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 25 FX', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 64 FX', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 80', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TENERGY 80 FX', '', '56.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SRIVER G3', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SRIVER G3 FX', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'BRYCE', '', '41.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'BRYCE FX', '', '41.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'BRYCE SPEED', '', '52.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'BRYCE SPEED FX', '', '52.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'BRYCE HIGHSPEED', '', '49.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FLEXTRA', '', '21.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TACKIFIRE DRIVE', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TACKINESS DRIVE 21', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TACKINESS CHOP', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('AUTRES', 'AUTRES', 0, 'COLLAGE REVETEMENT', '', '1.00', 0, '1'));
$db->execute($insert_sql, array('BALLES', 'BUTTERFLY', 0, 'THREE STAR BALL G40 + (3 BALLES)', '', '5.90', 0, '1'));
$db->execute($insert_sql, array('BALLES', 'BUTTERFLY', 0, 'THREE STAR BALL G40 + (12 BALLES)', '', '22.90', 0, '1'));
$db->execute($insert_sql, array('BALLES', 'BUTTERFLY', 0, 'THREE STAR G40 + (72 BALLES)', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BALLES', 'BUTTERFLY', 0, 'MASTER QUALITY G40 + (72 BALLES)', '', '49.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'ROUNDELL', '', '34.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'ROUNDELL HARD', '', '34.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'ROUNDELL SOFT', '', '34.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TACKINESS DRIVE', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'TACKINESS CHOP 2', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FLARESTORM 2', '', '47.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'RAYSTORM', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SPEEDY P.O', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'SUPER ANTI', '', '26.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'CHALLENGER ATTACK', '', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'ORTHODOX DX', '', '15.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FEINT AG', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FEINT SOFT', '', '31.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FEINT OX', '', '31.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FEINT LONG 3', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'BUTTERFLY', 0, 'FEINT LONG 2', '', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'APOLONIA ZLC', '', '189.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'FREITAS ALC', '', '139.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GARAYDIA ZLC', '', '189.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GARAYDIA ALC', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GARAYDIA T5000', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'INNERFORCE LAYER ZLC', '', '189.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'INNERFORCE LAYER ALC', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'TIMO BOLL ZLC', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'TIMO BOLL ZLF', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'TIMO BOLL ALC', '', '139.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW VR', '', '99.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW VK', '', '89.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW SR', '', '99.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW SK', '', '89.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZHANG JIKE SUPER ZLC', '', '349.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZHANG JIKE ZLC', '', '239.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZHANG JIKE ALC', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZHANG JIKE T5000', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'MIZUTANI JUN SUPER ZLC', '', '349.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'MIZUTANI JUN ZLC', '', '239.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'FUKUHARA AI PRO ZLF', '', '179.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'LIU SHIWEN', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'VISCARIA', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'TIMO BOLL SPIRIT', '', '119.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'PRIMORAC CARBON', '', '119.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'KORBEL SK7', '', '69.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'A MAZUNOV', '', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'PETR KORBEL (5 PLIS)', '', '44.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'PETR KORBEL XXS', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZORAN PRIMORAC (5 PLIS)', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ZORAN PRIMORAC XXS', '', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BOLL FORTE', '', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BOLL OFFENSIVE', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BOLL ALLROUND (5 PLIS)', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BOLL ALLROUND XXS', '', '31.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BOLL CONTROL', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'BALSA CARBO X5', '', '51.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'ANDRZEJ GRUBBA', '', '39.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'MAZE MAGIC', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GIONIS CARBON OFFENSIVE', '', '64.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GIONIS CARBON ALLROUND', '', '54.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GIONIS CARBON DEFENSIVE', '', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'INNERFORCE LAYER ZLF', '', '159.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW SHIELD', '', '99.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'DEFENCE 4', '', '99.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'JOO SAEHYUK', '', '74.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'GARAYDIA REVOLVER-R', '', '129.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'BUTTERFLY', 0, 'HADRAW REVOLVER-R', '', '99.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100811, 'BALSA 4,5', 'TSP', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100814, 'BALSA 5,5', 'TSP', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100812, 'BALSA 6,5', 'TSP', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100808, 'BLACK BALSA 7.0', 'TSP', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100809, 'BLACK BALSA 5.0', 'TSP', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100177, '4 L', 'TIBHAR', '30.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100185, '4 L BALSA', 'TIBHAR', '32.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100178, '4 L LIGHT CONTACT', 'TIBHAR', '30.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100176, '4 S', 'TIBHAR', '30.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100167, '4 L SGS', 'TIBHAR', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100168, 'BALSA SGS', 'TIBHAR', '36.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100166, '4 S SGS', 'TIBHAR', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100111, 'PATRICK CHILA', 'TIBHAR', '32.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100120, 'NIMBUS ALL', 'TIBHAR', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100108, 'DRINKHALL ALLROUND CLASSIC', 'TIBHAR', '52.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100109, 'DRINKHALL OFFENSIVE CLASSIC', 'TIBHAR', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100110, 'DRINKHALL POWER SPIN CARBON', 'TIBHAR', '134.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100165, 'SAMSONOV ALPHA SGS', 'TIBHAR', '36.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100197, 'SAMSONOV ALPHA', 'TIBHAR', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100179, 'TECHNO POWER CONTACT', 'TIBHAR', '32.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100180, 'SAMSONOV PREMIUM CONTACT', 'TIBHAR', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100146, 'SAMSONOV PURE WOOD', 'TIBHAR', '49.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100159, 'RAPID CARBON LIGHT', 'TIBHAR', '57.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100101, 'BALSA ALLROUND 50', 'TIBHAR', '45.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100102, 'BALSA FIBRE OFF 60', 'TIBHAR', '45.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100103, 'BALSA FIBRETEC 70', 'TIBHAR', '45.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100936, 'PRO', 'BANCO', '33.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100934, 'BALSA COMBI', 'BANCO', '36.00', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100941, 'EPOXY ALL', 'BANCO', '29.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100940, 'EPOXY OFF', 'BANCO', '34.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100913, 'CARBO POWER', 'BANCO', '39.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100912, 'BALSA SPEED', 'BANCO', '29.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100907, 'BANCO STAR', 'BANCO', '17.00', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100901, 'BLACK LIGHT', 'BANCO', '30.50', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100924, 'MAGIC LIGHT', 'BANCO', '32.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100793, 'OVTCHAROV TRUE CARBON', 'DONIC', '99.00', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100769, 'OVTCHAROV CARBOSPEED', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100771, 'BURN OFF', 'DONIC', '42.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100772, 'BURN OFF-', 'DONIC', '42.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100773, 'BURN ALL +', 'DONIC', '42.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100703, 'APPELGREEN ALLPLAY SENSO V1', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100702, 'APPELGREEN SENSO V2', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100799, 'OVTCHAROV SENSO CB', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100798, 'OVTCHAROV SENSO V2', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100738, 'WALDNER SENSO V1', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100739, 'WALDNER SENSO V2', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100701, 'APPELGREEN ALLPLAY', 'DONIC', '29.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100734, 'WALDNER ALLPLAY', 'DONIC', '29.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100761, 'EPOX POWER ALLROUND', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100785, 'APPELGREEN EXCLUSIVE AR', 'DONIC', '25.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100786, 'WALDNER EXCLUSIVE AR+', 'DONIC', '25.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100787, 'PERSSON EXCLUSIVE OFF', 'DONIC', '25.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100795, 'OVTCHAROV DOTEC ALL', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100796, 'OVTCHAROV DOTEC ALL+', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100797, 'OVTCHAROV DOTEC OFF', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100720, 'WANG XI DOTEC CONTROL +', 'DONIC', '59.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100749, 'APPELGREEN DOTEC CONTROL', 'DONIC', '64.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100750, 'WALDNER DOTEC AR', 'DONIC', '64.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100758, 'PERSSON DOTEC OFF', 'DONIC', '69.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 100752, 'WALDNER DOTEC OFF', 'DONIC', '79.90', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101052, 'NIGHTMARE ALL+', 'ANDRO', '34.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101053, 'NIGHTMARE OFF', 'ANDRO', '34.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101076, 'KANTER OFF+', 'ANDRO', '46.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101020, 'SUPER CORE CARBON LIGHT ALL+', 'ANDRO', '64.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101021, 'SUPER CORE CARBON LIGHT OFF', 'ANDRO', '64.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101059, 'TREIBER Z OFF', 'ANDRO', '109.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101085, 'CORE 7 OFF', 'ANDRO', '49.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101086, 'CORE 7 OFF+', 'ANDRO', '49.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101066, 'CS V OFF-', 'ANDRO', '34.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101067, 'CS V OFF', 'ANDRO', '34.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101028, 'SUPER CORE CELL ALL', 'ANDRO', '44.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101029, 'SUPER CORE CELL ALL+', 'ANDRO', '44.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101030, 'SUPER CORE CELL OFF-', 'ANDRO', '44.95', 0, '1'));
$db->execute($insert_sql, array('BOIS', 'WACK SPORT', 101031, 'SUPER CORE CELL OFF', 'ANDRO', '44.95', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 941, 'POWERFEELING 43', 'BANCO', '43.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 940, 'POWERFEELING 38', 'BANCO', '43.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 922, 'OURAGAN 42', 'BANCO', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 921, 'OURAGAN 37', 'BANCO', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 945, 'MEGASPIN 45', 'BANCO', '41.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 946, 'MEGASPIN 40', 'BANCO', '41.90', 0, '1'));
$db->execute($insert_sql, array('TEXTILES', 'BUTTERFLY', 0, 'SURVETEMENT TOYO', 'TOYO', '40.00', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 932, 'PUISSANCE 42', 'BANCO', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 931, 'PUISSANCE 37', 'BANCO', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 936, 'PUISSANCE 32', 'BANCO', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 915, 'FUTURA ENERGIE', 'BANCO', '30.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 928, 'EXCELLENCE 37', 'BANCO', '29.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 916, 'VARIATION ENERGIE', 'BANCO', '31.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 909, 'ALLSTAR', 'BANCO', '18.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 903, 'FEELING', 'BANCO', '30.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 731, 'BLUEFIRE M1 TURBO', 'DONIC', '48.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 796, 'BLUEFIRE M1', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 797, 'BLUEFIRE M2', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 798, 'BLUEFIRE M3', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 729, 'BLUEFIRE BIGSLAM', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 728, 'BLUEFIRE JP 02', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 727, 'BLUEFIRE JP 03', 'DONIC', '45.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 723, 'ACUDA BLUE P3', 'DONIC', '48.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 781, 'ACUDA S2', 'DONIC', '44.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 782, 'ACUDA S3', 'DONIC', '44.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 776, 'COPPA X2 (PLATIN SOFT)', 'DONIC', '42.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 777, 'COPPA X3', 'DONIC', '42.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 768, 'VARIO BIGSLAM', 'DONIC', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 700, 'DESTO F4', 'DONIC', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 770, 'DESTO F3 BIGSLAM', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 706, 'DESTO F3', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 704, 'DESTO F2', 'DONIC', '35.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 702, 'DESTO F1 PLUS', 'DONIC', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 771, 'BARACUDA', 'DONIC', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 785, 'BARACUDA BIGSLAM', 'DONIC', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 725, 'LIGA PLUS', 'DONIC', '22.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 769, 'AKKADI TAICHI', 'DONIC', '24.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 714, 'VARIO', 'DONIC', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 713, 'VARIO SOFT', 'DONIC', '32.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 172, 'SPEEDY SPIN', 'TIBHAR', '35.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 170, 'SPEEDY SPIN FX PREMIUM', 'TIBHAR', '35.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 155, 'RAPID', 'TIBHAR', '28.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 153, 'RAPID SOFT', 'TIBHAR', '28.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 105, 'NIMBUS DELTA 5', 'TIBHAR', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 106, 'NIMBUS DELTA S', 'TIBHAR', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 130, 'NIMBUS', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 129, 'NIMBUS SOFT', '', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 128, 'NIMBUS SOUND', 'TIBHAR', '36.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 115, 'AURUS', 'TIBHAR', '38.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 116, 'AURUS SOFT', 'TIBHAR', '38.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 117, 'AURUS SOUND', 'TIBHAR', '38.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 101, 'QUANTUM', 'TIBHAR', '48.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 102, 'QUANTUM S', 'TIBHAR', '48.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 125, 'SINUS', 'TIBHAR', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 126, 'SINUS ALPHA', 'TIBHAR', '39.90', 0, '1'));
$db->execute($insert_sql, array('REVETEMENTS', 'WACK SPORT', 124, 'SINUS SOUND', 'TIBHAR', '39.90', 0, '1'));

#
#
#Indexes
//on créé un index sur la table div_tours
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'cf',
	    cms_db_prefix().'module_commandes_cf_items', 'id_items',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
#
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'cc',
	    cms_db_prefix().'module_commandes_cc', 'commande_number',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
	#
#
	//
// templates
//

try {
    // commande form1 template type
    $commandes_template_type = new \CmsLayoutTemplateType();
    $commandes_template_type->set_originator($this->GetName());
    $commandes_template_type->set_name('form1');
    $commandes_template_type->set_dflt_flag(TRUE);
    $commandes_template_type->set_lang_callback('Commandes::page_type_lang_callback');
    $commandes_template_type->set_content_callback('Commandes::reset_page_type_defaults');
    $commandes_template_type->reset_content_to_factory();
    $commandes_template_type->save();

    // create a sample template of this type
    $fn = __DIR__.'/templates/orig_form1_template.tpl';
    if( is_file( $fn ) ) {
        $contents = @file_get_contents($fn);
        $tpl = new CmsLayoutTemplate();
        $tpl->set_name('Commandes form1');
        $tpl->set_owner($uid);
        $tpl->set_content($contents);
        $tpl->set_type($commandes_template_type);
        $tpl->set_type_dflt(TRUE);
        $tpl->save();
    }
}
catch( \Exception $e ) {
    // log it
    debug_to_log('ERROR: '.$e->GetMessage());
    debug_to_log($e->GetTraceAsString());
    audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}

try {
    // commande form1 template type
    $commandes_template_type = new \CmsLayoutTemplateType();
    $commandes_template_type->set_originator($this->GetName());
    $commandes_template_type->set_name('form1results');
    $commandes_template_type->set_dflt_flag(TRUE);
    $commandes_template_type->set_lang_callback('Commandes::page_type_lang_callback');
    $commandes_template_type->set_content_callback('Commandes::reset_page_type_defaults');
    $commandes_template_type->reset_content_to_factory();
    $commandes_template_type->save();

    // create a sample template of this type
    $fn = __DIR__.'/templates/orig_form1results_template.tpl';
    if( is_file( $fn ) ) {
        $contents = @file_get_contents($fn);
        $tpl = new CmsLayoutTemplate();
        $tpl->set_name('Commandes form1results');
        $tpl->set_owner($uid);
        $tpl->set_content($contents);
        $tpl->set_type($commandes_template_type);
        $tpl->set_type_dflt(TRUE);
        $tpl->save();
    }
}
catch( \Exception $e ) {
    // log it
    debug_to_log('ERROR: '.$e->GetMessage());
    debug_to_log($e->GetTraceAsString());
    audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}
//mails templates
# Mails templates
$fn = cms_join_path(dirname(__FILE__),'templates','orig_activationemailtemplate.tpl');
if( file_exists( $fn ) )
{
	$template = file_get_contents( $fn );
	$this->SetTemplate('newactivationemail_Sample',$template);
}
$fn = cms_join_path(dirname(__FILE__),'templates','orig_newcommandemailtemplate.tpl');
if( file_exists( $fn ) )
{
	$template = file_get_contents( $fn );
	$this->SetTemplate('newcommandemail_Sample',$template);
}
# Les préférences 
$this->SetPreference('installation', '0');
$this->SetPreference('admin_email', 'root@localhost.com');
$this->SetPreference('email_activation_subject', 'Votre compte T2T Commandes est activé');
$this->SetPreference('new_command_subject','[T2T] Nouvelle commande !');
//$this->SetPreference('mail_activation_body', )
//permissions
$this->CreatePermission('Use Commandes','Utiliser le module Commandes');

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>