<?php
#-------------------------------------------------------------------------
# Module: Commandes (c) 2016 par Claude SIOHAN
#        
#
#-------------------------------------------------------------------------
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE
$db = $this->GetDb();

// remove the database tables
$dict = NewDataDictionary( $db );

$sqlarray = $dict->DropTableSQL( cms_db_prefix()."module_commandes_clients" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL( cms_db_prefix()."module_commandes_items" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(  cms_db_prefix()."module_commandes_cc" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(  cms_db_prefix()."module_commandes_cc_items" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(  cms_db_prefix()."module_commandes_cf" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(  cms_db_prefix()."module_commandes_cf_items" );
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(  cms_db_prefix()."module_commandes_stock" );
$dict->ExecuteSQLArray($sqlarray);


// remove the permissions
$this->RemovePermission('Use Commandes');
/*
$this->RemovePermission('Ping Set Prefs');
$this->RemovePermission('Ping Manage user');
$this->RemovePermission('Ping Delete');

// remove the preference
$this->RemovePreference();


// Events
$this->RemoveEvent( 'OnUserAdded' );
$this->RemoveEvent( 'OnUserDeleted' );

*/

// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>