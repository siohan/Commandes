<?php
if( !isset($gCms) ) exit;
################################################################################
##        Cette page gère les différentes listes                              ##
################################################################################
$liste_fournisseurs = array("WACK SPORT"=>"WACK SPORT", "BUTTERFLY"=>"BUTTERFLY", "AUTRES"=>"AUTRES");
$liste_statuts_commandes_fournisseurs = array("Non envoyée"=>"Non envoyée", "Envoyée"=>"Envoyée", "Reçue"=>"Reçue");
$items_statut_commande = array("En cours de traitement"=>"En cours de traitement", "Envoyée"=>"Envoyée", "Refusée"=>"Refusée", "Reçue"=>"Reçue");
$items_paiement = array("Non payée"=>"Non payée", "Payée"=>"Payée", "Payée et déstockée"=>"Payée et déstockée","Refusée"=>"Refusée", "Reçue"=>"Reçue");
$items_mode_paiement = array("Aucun"=>"Aucun","Chèque"=>"Chèque", "Espèces"=>"Espèces","Dotations"=>"Dotations","Dotations Club"=>"Dotations Club", "Autres"=>"Autres");
$liste_categories = array("BOIS"=>"BOIS","REVETEMENTS"=>"REVETEMENTS","BALLES"=>"BALLES","TEXTILES"=>"TEXTILES","AUTRES"=>"AUTRES", "ACCESSOIRES"=>"ACCESSOIRES");
?>