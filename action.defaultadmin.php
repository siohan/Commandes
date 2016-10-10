<?php

   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Use Commandes'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
	

//on instancie les onglets
$version = $this->GetVersion('Ping');
//echo "la version est : ".$version;
echo $this->StartTabheaders();
if (FALSE === empty($params['active_tab']))
  {
    $tab = $params['active_tab'];
  } else {
  $tab = 'commandesclients';
 }	
	echo $this->SetTabHeader('commandesfournisseurs', 'Commandes fournisseurs', ('commandesfournisseurs' == $tab)?true:false);
	echo $this->SetTabHeader('commandesclients', 'Commandes adhérents', ('commandesclients' == $tab)?true:false);
	echo $this->SetTabHeader('clients', 'Clients', ('clients' == $tab)?true:false);
	echo $this->SetTabHeader('articles', 'Articles' , ('articles' == $tab)?true:false);
	echo $this->SetTabHeader('stock', 'Stock' , ('stock' == $tab)?true:false);

echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	/**/
	echo $this->StartTab('commandesfournisseurs' , $params);//les équipes
    	include(dirname(__FILE__).'/action.admin_cf_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('commandesclients', $params);
    	include(dirname(__FILE__).'/action.admin_cc_tab.php');
   	echo $this->EndTab();


	echo $this->StartTab('clients' , $params);//les types de compétitions
    	include(dirname(__FILE__).'/action.admin_clients_tab.php');
	echo $this->EndTab();
	
	echo $this->StartTab('articles', $params);
    	include(dirname(__FILE__).'/action.admin_items_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('stock', $params);
    	include(dirname(__FILE__).'/action.admin_stock_tab.php');
   	echo $this->EndTab();


echo $this->EndTabContent();
//on a refermé les onglets

//echo $this->ProcessTemplate('admin_panel.tpl');
?>