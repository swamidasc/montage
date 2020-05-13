<?php
namespace App\Model\Table;

use App\Model\Entity\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clients Model
 *
 * @property \Cake\ORM\Association\HasMany $Projects
 */
class ClientsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('clients');
        $this->displayField('companyname');
        $this->primaryKey('id');

        $this->hasMany('Projects', [
            'foreignKey' => 'client_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('companyname', 'create')
            ->notEmpty('companyname');

        $validator
            ->requirePresence('contactname', 'create')
            ->notEmpty('contactname');

        $validator
            ->requirePresence('contactphone', 'create')
            ->notEmpty('contactphone');

        $validator
            ->requirePresence('contactemail', 'create')
            ->notEmpty('contactemail');

        return $validator;
    }
}
