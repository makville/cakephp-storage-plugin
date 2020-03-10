<?php

namespace MakvilleStorage\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Buckets Model
 *
 * @property \Cake\ORM\Association\HasMany $Items
 *
 * @method \MakvilleStorage\Model\Entity\Bucket get($primaryKey, $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket newEntity($data = null, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket[] newEntities(array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket[] patchEntities($entities, array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Bucket findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BucketsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('buckets');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Items', [
            'foreignKey' => 'bucket_id',
            'className' => 'MakvilleStorage.Items'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->allowEmpty('name');

        $validator
                ->integer('parent')
                ->allowEmpty('parent');

        $validator
                ->integer('size')
                ->allowEmpty('size');

        return $validator;
    }

    public function getBucketContent($id = null, $access = 'private') {
        if (!is_null($id) && is_numeric($id) && $id > 0) {
            $buckets = $this->find()->where(['parent' => $id]);
            $items = $this->Items->find()->where(['bucket_id' => $id]);
        } else {
            $buckets = $this->find()->where(['parent IS NULL']);
            $items = $this->Items->find()->where(['bucket_id IS NULL']);
        }
        if ($access == 'public') {
            $items->where(['access' => 'public']);
        }
        return ['buckets' => $buckets, 'items' => $items];
    }
    
    public function getBucketDescendants ($id = null, &$contents = []) {
        $rootContents = $this->getBucketContent($id);
        foreach ($rootContents['items'] as $item) {
            $contents['items'][] = $item;
        }
        foreach ($rootContents['buckets'] as $bucket) {
            $contents['buckets'][] = $bucket;
            $this->getBucketDescendants($bucket->id, $contents);
        }
        return $contents;
    }
    
    public function getBucketAncestors($id, &$ancestors = []) {
        if (empty($ancestors)) {
            $ancestors[null] = 'Home';
        }
        $bucket = $this->get($id);
        if (!is_null($bucket->parent) && is_numeric($bucket->parent) && $bucket->parent > 0 ) {
            $parent = $this->get($bucket->parent);
            if (!is_null($parent->parent) && is_numeric($parent->parent) && $parent->parent > 0) {
                $this->getBucketAncestors($parent->id, $ancestors);
            }
            $ancestors[$parent->id] = $parent->name;
        }
        return $ancestors;
    }

    public function deleteBucket($id) {
        $bucket = $this->get($id);
        return $this->delete($bucket);
    }
}
