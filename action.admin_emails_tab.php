<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
if(isset($params['submit']))
{
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('admin_email', $params['adminemail']);

	$this->SetPreference('new_command_subject', $params['newcommandsubject']);
	$this->SetTemplate('newcommandemail_Sample',$params['newcommand_mail_template']);
	$this->SetPreference('new_status_subject', $params['newstatussubject']);
	$this->SetTemplate('newstatusemail_Sample',$params['newstatus_mail_template']);
	//on redirige !
	$this->RedirectToAdminTab('notifications');
}
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_emails_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());

$smarty->assign('input_adminemail', $this->CreateInputText($id, 'adminemail',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('input_newcommandsubject',$this->CreateInputText($id, 'newcommandsubject',$this->GetPreference('new_command_subject'), 50, 150));
$smarty->assign('input_emailnewcommandbody', $this->CreateSyntaxArea($id, $this->GetTemplate('newcommandemail_Sample'), 'newcommand_mail_template', '', '', '', '', 80, 7));

$smarty->assign('input_newstatussubject',$this->CreateInputText($id, 'newstatussubject',$this->GetPreference('new_status_subject'), 50, 150));
$smarty->assign('input_emailnewstatusbody', $this->CreateSyntaxArea($id, $this->GetTemplate('newstatusemail_Sample'), 'newstatus_mail_template', '', '', '', '', 80, 7));


$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('notifications.tpl');
#
# EOF
#
?>