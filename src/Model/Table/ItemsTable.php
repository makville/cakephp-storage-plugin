<?php

namespace MakvilleStorage\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

/**
 * Items Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Buckets
 *
 * @method \MakvilleStorage\Model\Entity\Item get($primaryKey, $options = [])
 * @method \MakvilleStorage\Model\Entity\Item newEntity($data = null, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Item[] newEntities(array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Item|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MakvilleStorage\Model\Entity\Item patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Item[] patchEntities($entities, array $data, array $options = [])
 * @method \MakvilleStorage\Model\Entity\Item findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ItemsTable extends Table {

    const ITEM_STATUS_LOCAL = 'local';
    const ITEM_STATUS_S3 = 's3';

    /**
     *
     * @var S3Client
     */
    public $s3 = null;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('items');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Buckets', [
            'foreignKey' => 'bucket_id',
            'className' => 'MakvilleStorage.Buckets'
        ]);

        $this->s3 = S3Client::factory([
                    'region' => Configure::read('aws_region'),
                    'version' => Configure::read('aws_version'),
                    'credentials' => [
                        'key' => Configure::read('aws_key'),
                        'secret' => Configure::read('aws_secret'),
                    ]
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
                ->allowEmpty('bucket_id');

        $validator
                ->integer('size')
                ->allowEmpty('size');

        $validator
                ->allowEmpty('s3_path');

        $validator
                ->allowEmpty('local_path');

        $validator
                ->allowEmpty('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        //$rules->add($rules->existsIn(['bucket_id'], 'Buckets'));

        return $rules;
    }

    public function saveItem(\MakvilleStorage\Model\Entity\Item $item) {
        if ($this->save($item)) {
            //upload to s3
            $prefix = 'https://s3-us-west-2.amazonaws.com/' . Configure::read('aws_bucket');
            $parts = explode('.', $item->name);
            $ext = array_pop($parts);
            $itemName = md5(uniqid() . $item->id . date('Y-m-d H:i:s')) . ".$ext";
            $s3Url = "$prefix/items/$itemName";
            try {
                $this->s3->putObject([
                    'Bucket' => Configure::read('aws_bucket'),
                    'Key' => "items/$itemName",
                    'Body' => fopen($item->local_path, 'rb'),
                    'SourceFile' => $item->local_path,
                    'ContentType' => $item->type,
                    'ACL' => 'public-read'
                ]);
            } catch (S3Exception $e) {
                return false;
            } finally {
                //update the item with the s3 - url
                $item->s3_path = $s3Url;
                $item->status = self::ITEM_STATUS_S3;
                $this->save($item);
                //delete the local file
                unlink($item->local_path);
            }
            return true;
        }
        return false;
    }

    public function deleteItem($item) {
        if ($this->delete($item)) {
            //delete from s3
            $bucketName = substr($item->s3_path, -36);
            try {
                $this->s3->putObject([
                    'Bucket' => Configure::read('aws_bucket'),
                    'Key' => "items/$bucketName",
                    'ACL' => 'public-read'
                ]);
            } catch (S3Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function deleteItems($items) {
        $objects = [];
        foreach ($items as $item) {
            $objects[] = ['Key' => substr($item->s3_url, -36)];
            $ids[] = $item->id;
        }
        if ($this->deleteAll(['id IN' => $ids])) {
            try {
                $this->s3->deleteObjects([
                    'Bucket' => Configure::read('aws_bucket'),
                    'Objects' => $objects
                ]);
            } catch (S3Exception $e) {
                return false;
            } finally {
                
            }
            return true;
        }
        return false;
    }
}
