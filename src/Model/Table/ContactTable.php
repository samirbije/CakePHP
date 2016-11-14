<?php
namespace App\Model\Table;

use App\Model\Entity\Member;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contact Model
 *
 * @method \App\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contact findOrCreate($search, callable $callback = null)
 */
class ContactTable extends Table
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

        $this->table('contact');
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
         ->notEmpty('name','Please enter name')
         ->notEmpty('last_name','Please enter last name')
         ->notEmpty('organization','Please enter organization')
         ->notEmpty('text','Please enter text')
         ->notEmpty('reason','Please enter reason')
         ->notEmpty('email', __('Email is required field'))
        ->add('email', 'validFormat', [
                    'rule' => 'email',
                    'message' => 'E-mail must be valid'
        ])
		->notEmpty('specify','Please enter specify');
		
		
        return $validator;       
    }
    /**
     * Default custom validation rules.
     *
     * @param select value,input value.
     * @return boolean 
     */
public function seasonEmptyCheck($value,$context){
        if ($value =='' && $context['data']['reason']=='Other') {
            return false;

        } else {
            return true;

        }
    }
}
