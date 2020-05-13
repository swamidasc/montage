<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;	

/**
 * UsersHistory Entity.
 */
class UsersHistory extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     * Note that '*' is set to true, which allows all unspecified fields to be
     * mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    


    /**
     * Record method
     *
     * @return void.
     */    
    public function record($userid,$message,$ipaddress) {
	    $userhistoryTable = TableRegistry::get('UsersHistory');
		$userhistory = $userhistoryTable->newEntity();
		$userhistory->user_id = $userid;
		$userhistory->description = $message;
		$userhistory->ipaddress = $ipaddress;
		$userhistoryTable->save($userhistory);
    }      
    
    
    
}
