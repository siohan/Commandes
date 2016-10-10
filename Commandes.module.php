<?php

#-------------------------------------------------------------------------
# Module : Commandes - 
# Version : 0.1, Sc
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
  function GetVersion() { return '0.1'; }  
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
        return array('CGExtensions'=>'1.53.6','CGSimpleSmarty'=>'1.9','JQueryTools'=>'1.3.8');
    }

  public function GetHeaderHTML()
    {
        $out = parent::GetHeaderHTML();
        $obj = cms_utils::get_module('JQueryTools','1.2');
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

  
  function SetParameters()
  { 
  	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();
	
	//form parameters
	$this->SetParameterType('submit',CLEAN_STRING);
	$this->SetParameterType('tourlist',CLEAN_INT);
	

}

function InitializeAdmin()
{
  	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
	$this->CreateParameter('tour', 1, $this->Lang('help_tour'));
	
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

  function InstallPostMessage() { return $this->Lang('postinstall'); }
  function UninstallPostMessage() { return $this->Lang('postuninstall'); }
  function UninstallPreMessage() { return $this->Lang('really_uninstall'); }
  
  
  function _SetStatus($oid, $status) {
    //...
  }




} //end class
?>
