<?php
class Zend_View_Helper_EntityDataArray {

    /**
     * @param RM_Trait_Content[] $entities
     * @return array
     */
	public function EntityDataArray( array $entities ) {
		$data = array();
		foreach ($entities as $entity) {
			$data[ $entity->getId() ] = $entity->getContent()->getName();
		}
		return $data;
	}
	
}