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
	$this->SetPreference('email_activation_subject', $params['emailactivationsubject']);
	$this->SetTemplate('newactivationemail_Sample', $params['activation_mail_template']);
	$this->SetPreference('new_command_subject', $params['newcommandsubject']);
	$this->SetTemplate('newcommandemail_Sample',$params['newcommand_mail_template']);
	//on redirige !
	$this->RedirectToAdminTab('notifications');
}
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_emails_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());
$smarty->assign('input_emailactivationsubject', $this->CreateInputText($id, 'emailactivationsubject',$this->GetPreference('email_activation_subject'), 50, 150));
$smarty->assign('input_adminemail', $this->CreateInputText($id, 'adminemail',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('input_emailactivationbody', $this->CreateSyntaxArea($id, $this->GetTemplate('newactivationemail_Sample'), 'activation_mail_template', '', '', '', '', 80, 7));

$smarty->assign('input_newcommandsubject',$this->CreateInputText($id, 'newcommandsubject',$this->GetPreference('new_command_subject'), 50, 150));
$smarty->assign('input_emailnewcommandbody', $this->CreateSyntaxArea($id, $this->GetTemplate('newcommandemail_Sample'), 'newcommand_mail_template', '', '', '', '', 80, 7));


$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('notifications.tpl');
#
# EOF
#
?>