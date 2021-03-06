<?php
if ( !isset($gCms) ) exit; 
if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
	

//on instancie les onglets
$version = $this->GetVersion('Commandes');
/*
$installation = $this->GetPreference('installation');
//var_dump($installation);
//echo "la version est : ".$version;
if(!isset($installation) || $installation =='' || $installation == '0')
{
	echo "Procédez à l'installation du module";
}
else
{
*/
	echo $this->StartTabheaders();
	if (FALSE === empty($params['active_tab']))
	 {
	   	$tab = $params['active_tab'];
	 }
	elseif(FALSE === empty($params['__activetab']))
	{
		$tab = $params['__activetab'];
	}
	else 
	{
	 	$tab = 'encours ';
	}	
		echo $this->SetTabHeader('encours', 'En cours', ('encours' == $tab)?true:false);
		echo $this->SetTabHeader('commandesfournisseurs', 'Commandes fournisseurs', ('commandesfournisseurs' == $tab)?true:false);
		echo $this->SetTabHeader('commandesclients', 'Commandes adhérents', ('commandesclients' == $tab)?true:false);		
		echo $this->SetTabHeader('clients', 'Clients', ('clients' == $tab)?true:false);
		echo $this->SetTabHeader('fournisseurs', 'Catalogues', ('fournisseurs' == $tab)?true:false);
		echo $this->SetTabHeader('articles', 'Articles' , ('articles' == $tab)?true:false);
		echo $this->SetTabHeader('stock', 'Stock' , ('stock' == $tab)?true:false);
		echo $this->SetTabHeader('notifications','Notifications', ('notifications' == $tab)?true:false);

	echo $this->EndTabHeaders();

	echo $this->StartTabContent();

		/**/
		echo $this->StartTab('encours', $params);
	    	include(dirname(__FILE__).'/action.admin_encours_tab.php');
	   	echo $this->EndTab();
	
		echo $this->StartTab('commandesfournisseurs' , $params);//les équipes
	    	include(dirname(__FILE__).'/action.admin_cf_tab.php');
	   	echo $this->EndTab();

		echo $this->StartTab('commandesclients', $params);
	    	include(dirname(__FILE__).'/action.admin_cc_tab.php');
	   	echo $this->EndTab();

		echo $this->StartTab('clients' , $params);//les types de compétitions
	    	include(dirname(__FILE__).'/action.admin_clients_tab.php');
		echo $this->EndTab();
		
		echo $this->StartTab('fournisseurs' , $params);//les types de compétitions
	    	include(dirname(__FILE__).'/action.admin_fournisseurs_tab.php');
		echo $this->EndTab();

		echo $this->StartTab('articles', $params);
	    	include(dirname(__FILE__).'/action.admin_items_tab.php');
	   	echo $this->EndTab();

		echo $this->StartTab('stock', $params);
	    	include(dirname(__FILE__).'/action.admin_stock_tab.php');
	   	echo $this->EndTab();
		
		echo $this->StartTab('notifications', $params);
	    	include(dirname(__FILE__).'/action.admin_emails_tab.php');
	   	echo $this->EndTab();
	
	echo $this->EndTabContent();


?>