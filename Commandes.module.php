<?php

#-------------------------------------------------------------------------
# Module : Commandes - 
# Version : 0.3.1, Sc
# Auteur : Claude SIOHAN
#-------------------------------------------------------------------------
/**
 *
 * @author Claude SIOHAN
 * @since 0.1
 * @version $Revision: 3827 $
 * @modifiedby $LastChangedBy: Claude 
 * @lastmodified $Date: 2007-03-12 11:56:16 +0200 (Mon, 28 Juil 2015) $
 * @license GPL
 **/


class Commandes extends CMSModule
{
  
  
  function GetName() { return 'Commandes'; }   
  function GetFriendlyName() { return $this->Lang('friendlyname'); }   
  function GetVersion() { return '0.3.2'; }  
  function GetHelp() { return $this->Lang('help'); }   
  function GetAuthor() { return 'Claude SIOHAN'; } 
  function GetAuthorEmail() { return 'claude.siohan@gmail.com'; }
  function GetChangeLog() { return $this->Lang('changelog'); }
    
  function IsPluginModule() { return true; }
  function HasAdmin() { return true; }   
  function GetAdminSection() { return 'content'; }
  function GetAdminDescription() { return $this->Lang('moddescription'); }

  function VisibleToAdminUser()
  {
    	return 	$this->CheckPermission('Use Commandes');
	
  }
 
  
  public function GetDependencies() {
        return array('Adherents'=>'0.2.2','Paiements'=>'0.1.2','CGExtensions'=>'1.56.1','CGSimpleSmarty'=>'2.1.6','JQueryTools'=>'1.4.0.3');
    }

  public function GetHeaderHTML()
    {
        $out = parent::GetHeaderHTML();
        $obj = cms_utils::get_module('JQueryTools','1.4.0.3');
        if( is_object($obj) ) {
            $tmpl = <<<EOT
{JQueryTools action='require' lib='tablesorter,jquerytools,cgform'}
{JQueryTools action='placemarker'}
EOT;
            $out .= $this->ProcessTemplateFromData($tmpl);
        }
        return $out;
    }


  function MinimumCMSVersion()
  {
    return "2.0";
  }

  public function InitializeFrontend()
  {
    $this->RestrictUnknownParams();
    	//form parameters
	$this->SetParameterType('submit',CLEAN_STRING);
	$this->SetParameterType('display',CLEAN_STRING);
	$this->SetParameterType('record_id', CLEAN_INT);
	$this->SetParameterType('fournisseur', CLEAN_STRING);
	$this->SetParameterType('nom', CLEAN_INT);
	$this->SetParameterType('client', CLEAN_INT);
	$this->SetParameterType('id_client', CLEAN_INT);
	$this->SetParameterType('date_created', CLEAN_STRING);
	$this->SetParameterType('returnid', CLEAN_INT);
	$this->SetParameterType('page', CLEAN_INT);
	$this->SetParameterType('detailpage',CLEAN_STRING);
	$this->SetParameterType('commande_number', CLEAN_STRING);
	$this->SetParameterType('produits', CLEAN_STRING);
	$this->SetParameterType('ep_manche_taille', CLEAN_STRING);
	$this->SetParameterType('couleur', CLEAN_STRING);
	$this->SetParameterType('quantite', CLEAN_INT);
	$this->SetParameterType('nom_joueur', CLEAN_STRING);
	$this->SetParameterType('prenom', CLEAN_STRING);
	$this->SetParameterType('email', CLEAN_STRING);
	$this->SetParameterType('motdepasse', CLEAN_STRING);
	$this->SetParameterType('licence', CLEAN_INT);
	
  }

  function SetParameters()
  { 
  	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();
	
	//form parameters
	$this->SetParameterType('submit',CLEAN_STRING);
	$this->SetParameterType('record_id', CLEAN_INT);
	$this->SetParameterType('fournisseur', CLEAN_STRING);
	$this->SetParameterType('nom', CLEAN_INT);
	$this->SetParameterType('client', CLEAN_INT);
	$this->SetParameterType('id_client', CLEAN_INT);
	$this->SetParameterType('date_created', CLEAN_STRING);
	$this->SetParameterType('returnid', CLEAN_INT);
	$this->SetParameterType('page', CLEAN_INT);
	$this->SetParameterType('detailpage',CLEAN_STRING);
	$this->SetParameterType('display',CLEAN_STRING);
	$this->SetParameterType('email', CLEAN_STRING);
	$this->SetParameterType('commande_number', CLEAN_STRING);
	$this->SetParameterType('nom_joueur', CLEAN_STRING);
	$this->SetParameterType('prenom', CLEAN_STRING);
	$this->SetParameterType('motdepasse', CLEAN_STRING);
	$this->SetParameterType('licence', CLEAN_INT);
	

}

function InitializeAdmin()
{
  	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
	$this->CreateParameter('detailpage',null,$this->Lang('param_detailpage'));
	//$this->CreateParameterType('display',CLEAN_STRING);
	
	
}

public function HasCapability($capability, $params = array())
{
   if( $capability == 'tasks' ) return TRUE;
   return FALSE;
}

public function get_tasks()
{
   /*
$obj = array();
	$obj[0] = new PingRecupFfttTask();
   	$obj[1] = new PingRecupSpidTask();  
	$obj[2] = new PingRecupRencontresTask();
return $obj; 
*/
}

  function GetEventDescription ( $eventname )
  {
    return $this->Lang('event_info_'.$eventname );
  }
     
  function GetEventHelp ( $eventname )
  {
    return $this->Lang('event_help_'.$eventname );
  }
  function GetDefaultTemplate($template)
  {
      $fn = sprintf('orig_%s_template.tpl',$template);
      $fn = cms_join_path(__DIR__,'templates',$fn);
      $data = @file_get_contents($fn);
      return $data;
  }
  function InstallPostMessage() { return $this->Lang('postinstall'); }
  function UninstallPostMessage() { return $this->Lang('postuninstall'); }
  function UninstallPreMessage() { return $this->Lang('really_uninstall'); }
  
  static public function page_type_lang_callback($str)
  {
      $mod = cms_utils::get_module('Commandes');
      if( is_object($mod) ) return $mod->Lang('tpltype_'.$str);
  }

  static public function reset_page_type_defaults(CmsLayoutTemplateType $type)
  {
      if( $type->get_originator() != 'Commandes' ) throw new CmsLogicException('Cannot reset contents for this template type');

      $fn = null;
      switch( $type->get_name() ) {
      case 'form1':
          $fn = 'orig_form1_template.tpl';
          break;
      /*
      case 'searchresult':
          $fn = 'orig_searchresult_template.tpl';
          break;
      case 'event':
          $fn = 'orig_event_template.tpl';
          break;
      case 'eventlist':
          $fn = 'orig_list_template.tpl';
          break;
      case 'myevents':
          $fn = 'orig_myevents_template.tpl';
          break;
      case 'editevent':
          $fn = 'orig_editevent_template.tpl';
          break;
      case 'deleteevent':
          $fn = 'orig_deleteevent_template.tpl';
          break;
      case 'fullcalendar':
          $fn = 'orig_fullcalendar_template.tpl';
          break;
      */
      default:
          throw new \LogicException($type->get_name().' is not a known type for the '.$type->get_originator().' originator');
      }

      $fn = cms_join_path(__DIR__,'templates',$fn);
      if( file_exists($fn) ) return @file_get_contents($fn);
  }
  
  function random($car) {
$string = "";
$chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
srand((double)microtime()*1000000);
for($i=0; $i<$car; $i++) {
$string .= $chaine[rand()%strlen($chaine)];
}
return $string;
}
function random_string($car) {
$string = "";
$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
srand((double)microtime()*1000000);
for($i=0; $i<$car; $i++) {
$string .= $chaine[rand()%strlen($chaine)];
}
return $string;
}

  function _SetStatus($oid, $status) {
    //...
  }




} //end class
?>
