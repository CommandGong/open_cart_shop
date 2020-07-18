<?php
class ModelShoptifyShoptify extends Model {
        const   _SHOPTIFY_COLUMN_NAME = "shoptify_app";
         function showConstant() {
            echo  self::_SHOPTIFY_COLUMN_NAME . "\n";
        }
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}
        
        public function getShoptifyAccessToken(){
            $query = $this->db->query("SELECT DISTINCT extension_value FROM ". DB_PREFIX . "extra WHERE extension_name = '".self::_SHOPTIFY_COLUMN_NAME."' ");
            if($query->num_rows != 0){
                return ($query->row['extension_value']);
            } 
            return null;
        }
        
        public function setShoptifyAccessToken($extra_value){
            
            if($this->getShoptifyAccessToken()==null){
                //INSERT INTO `aceway_cart_extra` (`extension_id`, `extension_name`, `extension_value`) VALUES (NULL, 'shoptify_app', 'something');
                $query = $this->db->query("INSERT INTO ". DB_PREFIX . "extra ( `extension_name`, `extension_value`) VALUES  ('".self::_SHOPTIFY_COLUMN_NAME."', '".$extra_value." ' )");
            }
            else{
                //UPDATE `aceway_cart_extra` SET `extension_value` = 'something1d' WHERE `aceway_cart_extra`.`extension_id` = 3;
                $sql = "UPDATE ". DB_PREFIX . "extra SET `extension_value` =   '".$extra_value." ' WHERE extension_name = '".self::_SHOPTIFY_COLUMN_NAME."' ";
                $query = $this->db->query($sql) ;
            }
            
            return ( $query );
        }

	
}