<?php

/*
if (!$this->CheckPermission('Ping Use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
*/
require_once(dirname(__FILE__).'/include/preferences.php');
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$smarty->assign('add',
		$this->CreateLink($id, 'add_edit_cf', $returnid,$contents='Ajouter une commande fournisseur', array("edit"=>"0")));
$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('active_tab'=>'equicommandesfournisseurs')));
$smarty->assign('fournisseur', 
		$this->CreateInputDropdown($id,'fournisseur', $items_statut_commande));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());
$result= array();
$query = "SELECT *  FROM ".cms_db_prefix()."module_commandes_cf WHERE date_created > '1970-01-01'";

	if( isset($params['submitfilter'] ))
	{
		if(isset($params['fournisseur']) && $params['fournisseur'] != '')
		{
			$query.=" AND fournisseur LIKE ?";
			$parms['fournisseur'] = $params['fournisseur'];
		}
		if(isset($params['statut_CF']) && $params['statut_CF'] != '')
		{
			$query.=" AND statut_CF LIKE ?";
			$parms['statut_CF'] = $params['statut_CF'];
		}
		$dbresult= $db->Execute($query,$parms);	
	}
	else
	{
		//$query .=" ORDER BY id DESC";
		//echo $query;
		$dbresult= $db->Execute($query);
	}
	
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';

	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				$id_cf = $row['id_CF'];
				//on va chercher le nb d'articles et le prix total de la CF
				$onerow->id_cf= $row['id_CF'];
				$onerow->date_created= $row['date_created'];
				$onerow->fournisseur= $row['fournisseur'];
				$onerow->statut_CF= $row['statut_CF'];
				
				//on va chercher le nb d'articles et pourquoi pas le prix total
				
				$query2 = "SELECT count(*) AS nb_items, SUM(prix_total) AS total_commande FROM ".cms_db_prefix()."module_commandes_cf_items WHERE id_CF = ?";
				$dbresult2 = $db->Execute($query2,array($id_cf));
				
				if($dbresult2 && $dbresult2->RecordCount()>0)
				{
					$row2 = $dbresult2->FetchRow();
					$nb_items = $row2['nb_items'];
					$total_commande = $row2['total_commande'];
				}
				else
				{
					$nb_items = 0;
					$total_commande = '0.00';
				}
				
				$onerow->nb_items = $nb_items;
				$onerow->total_commande = $total_commande;
				$onerow->view= $this->createLink($id, 'view_order_cf', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'CF','fournisseur'=>$row['fournisseur'],"record_id"=>$row['id_CF'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_cf', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id_CF']));
				$onerow->deletelink = $this->CreateLink($id, 'delete',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('record_id'=>$row['id_CF'], 'bdd'=>'cf','nb_items'=>$nb_items));
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		


echo $this->ProcessTemplate('cf.tpl');


#
# EOF
#
?>