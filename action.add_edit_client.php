<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Use Commandes'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('compets');
    		return;
  	}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
//s'agit-il d'une modif ou d'une créa ?
$record_id = '';
$index = 0;
if(isset($params['edit']) && $params['edit']=='1')
{
	$edit =1;
}
else
{
	$edit = 0;//pour savoir si on édite ou on créé, 0 par défaut c'est une créa
}


if(isset($params['record_id']) && $params['record_id'] !="")
	{
		$record_id = $params['record_id'];
		$edit = 1;//on est bien en trai d'éditer un enregistrement
		//ON VA CHERCHER l'enregistrement en question
		$query = "SELECT date_created, date_maj,nom, prenom, club, email, portable, tel, licence, account_validation, email_sent FROM ".cms_db_prefix()."module_commandes_clients WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			
			$date_created = $row['date_created'];
			$date_maj = $row['date_maj'];
			$nom = $row['nom'];
			$prenom = $row['prenom'];
			$club = $row['club'];
			$email = $row['email'];
			$tel = $row['tel'];
			$portable = $row['portable'];
			$licence = $row['licence'];
			$account_validation = $row['account_validation'];
			$email_sent = $row['email_sent'];
					
		}
	}
			
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_client', $returnid ) );
	if($edit==1)
	{
		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$record_id));
	}
	/*
	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id,'idepreuve',$type_compet,$selectedindex = $index, $selectedvalue=$name));
	*/
	$smarty->assign('nom',
			$this->CreateInputText($id,'nom',(isset($nom)?$nom:""),50,200));
	$smarty->assign('prenom',
			$this->CreateInputText($id,'prenom',(isset($prenom)?$prenom:""),50,200));
	$smarty->assign('licence',
			$this->CreateInputText($id,'licence',(isset($licence)?$licence:""),50,200));
	$smarty->assign('club',
			$this->CreateInputText($id,'club',(isset($club)?$club:""),50,200));
			
	$smarty->assign('email',
			$this->CreateInputText($id,'email',(isset($email)?$email:""),50,200));
			
	$smarty->assign('tel',
			$this->CreateInputText($id,'tel',(isset($tel)?$tel:""),50,200));			
			
	$smarty->assign('portable',
			$this->CreateInputText($id,'portable',(isset($portable)?$portable:""),30,150));
	
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
	
	



echo $this->ProcessTemplate('add_edit_client.tpl');

#
# EOF
#
?>
