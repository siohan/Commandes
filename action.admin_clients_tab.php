<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

//require_once(dirname(__FILE__).'/include/prefs.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');

$result= array ();
$query = "SELECT licence, CONCAT_WS(' ',nom, prenom) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents WHERE actif = 1";


	$query .=" ORDER BY nom ASC";
	//echo $query;
	$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->client_id= $row['licence'];
				
				//on va chercher le nombre de commandes toutes confondues de chq client
				$query2 = "SELECT count(*) AS nb_commandes  FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ?";
				$dbresult2 = $db->Execute($query2, array($row['licence']));
				$row2 = $dbresult2->FetchRow();
				$nb_commandes = $row2['nb_commandes'];
				
				
				
				$onerow->joueur = $row['joueur'];
				$onerow->licence =$row['licence'];
				$onerow->nb_commandes = $nb_commandes;
						
				
				if($nb_commandes >0)
				{
					$onerow->view= $this->createLink($id, 'view_client_orders', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['licence'])) ;
				}				
				$onerow->editlink= $this->CreateLink($id, 'add_edit_client', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['licence']));
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['licence'],"bdd"=>"clients"));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}
/*
		else
		{
			echo $this->ErrorMsg();
		}
*/

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('client.tpl');


#
# EOF
#
?>