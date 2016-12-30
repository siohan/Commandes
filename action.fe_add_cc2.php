 <?php
       if( !isset($gCms) ) exit;
       if( !$this->CheckPermission(Holidays::MANAGE_PERM) ) return;
       $commandes = new CommandesCC();
       if( isset($params['cancel']) ) {
           $this->RedirectToAdminTab();
       }
       elseif( isset($params['submit']) ) 
	{
	   $commandes->client = $params['client']
           $commandes->name = trim($params['name']);
           $commandes->published = cms_to_bool($params['published']);
           $commandes->date_created = strtotime($params['date_created']);
	   $commandes->date_modified = strtotime($params['date_modified']);
           $commandes->description = $params['description'];
           $commandes->save();
           $this->SetMessage($this->Lang('Commande enregistree'));
           $this->RedirectToAdminTab();
	}
	
	
       $tpl = $smarty->CreateTemplate($this->GetTemplateResource('fe_add_cc.tpl'),null,null,$smarty);
       //$tpl->assign('commandes',$commandes);
       $tpl->display();
?>