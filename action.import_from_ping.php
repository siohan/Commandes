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

$result= array();
$query = "SELECT  nom, prenom, licence FROM ".cms_db_prefix()."module_ping_joueurs WHERE actif = '1' ";


	//$query .=" ORDER BY id DESC";
	//echo $query;
	$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$compt = 0; //compteur d'insertion

	
		if($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				//les champs
				$nom = $row['nom'];
				$prenom = $row['prenom'];
				$licence = $row['licence'];
				//
				
				$query2 = "SELECT * FROM ".cms_db_prefix()."module_commandes_clients WHERE nom LIKE ? AND prenom LIKE ? AND licence = ?";
				$dbresult2 = $db->Execute($query2, array($nom, $prenom, $licence));
				
				if($dbresult2 && $dbresult2->RecordCount()== 0)
				{
					//on fait une requete d'insertion
					$compt++;
					$query3 = "INSERT INTO ".cms_db_prefix()."module_commandes_clients (id, nom, prenom,licence) VALUES ('', ?, ?, ?)";
					$dbresult3 = $db->Execute($query3, array($nom, $prenom, $licence));
				}
				else
				{
					//le client existe déjà ds la base
				}
      			}
			
  		}
		elseif(!$dbresult)
		{
			$message = $db->ErrorMsg();
			echo $message;
		}
		else
		{
			echo "pas de résultats !";
		}
$designation = $compt." joueur(s) inséré(s)";
$this->SetMessage($designation);
$this->RedirectToAdminTab('clients');
	

#
# EOF
#
?>