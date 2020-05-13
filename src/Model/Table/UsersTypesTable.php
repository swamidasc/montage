<?php
namespace App\Model\Table;

use App\Model\Entity\UsersType;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersTypes Model
 *
 */
class UsersTypesTable extends Table
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

        $this->table('users_types');
        $this->displayField('typename');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->hasMany('Users', [
            'foreignKey' => 'user_type_id',
            'bindingKey' => 'id'
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
            ->requirePresence('typename', 'create')
            ->notEmpty('typename');

        return $validator;
    }
}
