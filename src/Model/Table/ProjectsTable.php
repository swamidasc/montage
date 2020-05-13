<?php
namespace App\Model\Table;

use App\Model\Entity\Project;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Network\Http\Client;
use Cake\Validation\Validator;

/**
 * Projects Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clients
 * @property \Cake\ORM\Association\BelongsTo $Groups
 * @property \Cake\ORM\Association\BelongsTo $ProjectsTypes
 * @property \Cake\ORM\Association\BelongsToMany $Users
 * @property \Cake\ORM\Association\hasMany $ProjectsLabels
 */
class ProjectsTable extends Table
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

        $this->table('projects');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProjectsTypes', [
            'foreignKey' => 'project_type_id'
        ]);
        $this->belongsToMany('Users', [
            'through' => 'UsersProjects'
        ]);
        
       $this->hasMany('Labels', [
            'foreignKey' => 'project_id',
            'bindingKey' => 'id',
            'dependent' => true         
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('client_id', 'create')
            ->notEmpty('client_id');


        $validator
            ->requirePresence('group_id', 'create')
            ->notEmpty('group_id');


        $validator
            ->requirePresence('project_type_id', 'create')
            ->notEmpty('project_type_id');


        $validator
            ->requirePresence('table_name', 'create')
            ->notEmpty('table_name');

        $validator
            ->add('return_limit', 'valid', ['rule' => 'numeric'])
            ->requirePresence('return_limit', 'create')
            ->notEmpty('return_limit');

        $validator
            ->add('startdate', 'valid', ['rule' => 'datetime'])
            ->requirePresence('startdate', 'create')
            ->notEmpty('startdate');

        $validator
            ->add('enddate', 'valid', ['rule' => 'datetime'])
            ->requirePresence('enddate', 'create')
            ->notEmpty('enddate');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['client_id'], 'Clients'));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['project_type_id'], 'ProjectsTypes'));
        $rules->add($rules->isUnique(['table_name']));
        $rules->add($rules->isUnique(['name']));
        return $rules;
    }
}
