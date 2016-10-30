<?php
if (!isset($gCms)) exit;
//debug_display($params,'Parameters');
$feu = cms_utils::get_module('FrontEndUsers');
$userid = $feu->LoggedInId();
$properties = $feu->GetUserProperties($userid);
$email = $feu->LoggedInEmail();
//var_dump($email);
if($email == '')
{
	echo "pas de résultats, on fait quoi ?";
	exit;
}
else
{
	//on a l'email
	//on peut récupérer les infos du user
	echo "on continue";

}


/**/
//echo $this->ProcessTemplate('default1.tpl');
#
# EOF
#

?>