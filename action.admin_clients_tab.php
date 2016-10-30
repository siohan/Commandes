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
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_client', $returnid,$contents='Ajouter un client'));
$smarty->assign('import_from_ping',
		$this->CreateLink($id, 'import_from_ping', $returnid,$contents='Importer les joueurs du module Ping'));

$result= array ();
$query = "SELECT  id AS client_id, date_created, date_maj, nom, prenom, club, email, tel, portable FROM ".cms_db_prefix()."module_commandes_clients as cl";//", ".cms_db_prefix()."module_commandes_cc ";


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

				//les champs disponibles : 
				
				
				//on va chercher le nb d'articles de chq commande client
				
				
				$client_id = $row['client_id'];
			//	echo "le client_id est : ".$client_id;
				
				$onerow->client_id= $row['client_id'];
				
				//on va chercher le nombre de commandes toutes confondues de chq client
				$query2 = "SELECT count(*) AS nb_commandes  FROM ".cms_db_prefix()."module_commandes_cc WHERE client = ?";
				$dbresult2 = $db->Execute($query2, array($client_id));
				$row2 = $dbresult2->FetchRow();
				$nb_commandes = $row2['nb_commandes'];
				
				
				$onerow->date_created = $row['date_created'];
				$onerow->date_maj = $row['date_maj'];
				$onerow->nom = $row['nom'];
				$onerow->prenom = $row['prenom'];
				$onerow->nb_commandes = $nb_commandes;
				$onerow->club = $row['club'];				
				$onerow->email = $row['email'];
				$onerow->tel = $row['tel'];
				$onerow->portable = $row['portable'];
				if($nb_commandes >0)
				{
					$onerow->view= $this->createLink($id, 'view_client_orders', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'commandesclients',"record_id"=>$row['client_id'])) ;
				}
				
				$onerow->editlink= $this->CreateLink($id, 'add_edit_client', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['client_id']));
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['client_id'],"bdd"=>"clients"));
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('client.tpl');


#
# EOF
#
?>