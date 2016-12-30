<?php
       class CommandesCC
       {
           private $_data = array('id'=>null,'date_created'=>null,'date_modified'=>null,
                                  'client'=>null,'libelle_commande'=>null, 'fournisseur'=>null,'prix_total'=>null, 'statut_commande'=>null, 'paiement'=>null, 'mode_paiement'=>null, 'remarques'=>null);
           public function __get($key)
           {
               switch( $key ) 
		{
               	       case 'id':
	               case 'date_created':
	               case 'date_modified':
	               case 'client':
	               case 'libelle_commande':
		       case 'fournisseur':
		       case 'prix_total' :
		       case 'statut_commande' :
		       case 'paiement' :
		       case 'mode_paiement':
		       case 'remarques':
		
	                   return $this->_data[$key];
                }
}
           public function __set($key,$val)
           {
               switch( $key ) 
		{
               		case 'libelle_commande':
               		case 'fournisseur':
			case 'statut_commande':
			case 'paiement':
			case 'mode_paiement':
			case 'remarques':
			
                   		$this->_data[$key] = trim($val);
                   	break;
               		case 'date_created':
			case 'date_modified':
			case 'client':
        			$this->_data[$key] = (int) $val;
			break; 
		}
	   }
	   public function save()
	   {
    		if( !$this->is_valid() ) return FALSE;
    		if( $this->id > 0 ) 
		{
        		$this->update();
    		} 
		else 
		{
        		$this->insert();
    		}
	   }
	   public function is_valid()
	   {
    		if( !$this->name ) return false;
    		if( !$this->the_date ) return false;
    		return TRUE;
	   }
	   
	  protected function insert()
	  {
    		$db = \cms_utils::get_db();
    		$sql = 'INSERT INTO '.CMS_DB_PREFIX.'module_commandes_cc (date_created, date_modified,client, libelle_commande, fournisseur, prix_total, statut_commande, paiement, mode_paiement, remarques) VALUES (?,?,?,?,?,?,?,?,?,?)';
    		$dbr = $db->Execute($sql,array($this->date_created,$this->date_modified,$this->client, $this->libelle_commande, $this->fournisseur, $this->prix_total, $this->statut_commande, $this->paiement, $this->mode_paiement, $this->remarques));
    				if( !$dbr ) return FALSE;
    				$this->_data['id'] = $db->Insert_ID();
    		return TRUE;
	}
	
	protected function update()
	{
    		$db = \cms_utils::get_db();
    		$sql = 'UPDATE '.CMS_DB_PREFIX.'module_commandes_cc SET name = ?, description = ?, published = ?, the_date = ? WHERE id = ?';
    			$dbr = $db->Execute($sql,array($this->name,$this->description,$this->published,$this->the_date,$this->id));
    			if( !$dbr ) return FALSE;
    		return TRUE;
	}
	public function delete()
	{
    		if( !$this->id ) return FALSE;
    		$db = \cms_utils::get_db();
    		$sql = 'DELETE FROM '.CMS_DB_PREFIX.'module_commandes_cc WHERE id = ?';
    		$dbr = $db->Execute($sql,array($this->id));
    		if( !$dbr ) return FALSE;
    		$this->_data['id'] = null;
    		return TRUE;
	}
	/** internal */
	public function fill_from_array($row)
	{
    		foreach( $row as $key => $val ) 
		{
        		if( array_key_exists($key,$this->_data) ) 
			{
            			$this->_data[$key] = $val;
        		}
		} 
	}
	
	public static function &load_by_id($id)
	{

$this->the_date,$this->id));
$id = (int) $id;
               $db = \cms_utils::get_db();
               $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'mod_holidays WHERE id = ?';
               $row = $db->GetRow($sql,array($id));
               if( is_array($row) ) {
                   $obj = new self();
                   $obj->fill_from_array($row);
                   return $obj;
} }
} ?>