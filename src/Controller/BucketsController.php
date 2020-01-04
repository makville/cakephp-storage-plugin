<?php

namespace MakvilleStorage\Controller;

use MakvilleStorage\Controller\AppController;

/**
 * Buckets Controller
 *
 * @property \MakvilleStorage\Model\Table\BucketsTable $Buckets
 */
class BucketsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $buckets = $this->paginate($this->Buckets);

        $this->set(compact('buckets'));
        $this->set('_serialize', ['buckets']);
    }

    /**
     * View method
     *
     * @param string|null $id Bucket id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $bucket = $this->Buckets->get($id, [
            'contain' => ['Items']
        ]);

        $this->set('bucket', $bucket);
        $this->set('_serialize', ['bucket']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $bucket = $this->Buckets->newEntity();
        if ($this->request->is('post')) {
            $bucket = $this->Buckets->patchEntity($bucket, $this->request->data);
            if ($this->Buckets->save($bucket)) {
                $this->Flash->success(__('The bucket has been saved.'));
                if (!$this->request->is('ajax')) {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error(__('The bucket could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('bucket'));
        $this->set('_serialize', ['bucket']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bucket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $bucket = $this->Buckets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bucket = $this->Buckets->patchEntity($bucket, $this->request->data);
            if ($this->Buckets->save($bucket)) {
                $this->Flash->success(__('The bucket has been saved.'));
                if (!$this->request->is('ajax')) {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error(__('The bucket could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('bucket'));
        $this->set('_serialize', ['bucket']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bucket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $bucket = $this->Buckets->get($id);
        if ($this->Buckets->delete($bucket)) {
            $this->Flash->success(__('The bucket has been deleted.'));
        } else {
            $this->Flash->error(__('The bucket could not be deleted. Please, try again.'));
        }
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function show($id = null, $attach = null) {
        if ( !is_null($attach)) {
            $this->viewBuilder()->layout('lite');
        }
        //get all root buckets and root $items
        if (!is_null($id) && is_numeric($id) && $id > 0) {
            $ancestory = $this->Buckets->getBucketAncestors($id);
            $bucket = $this->Buckets->get($id);
        } else {
            $ancestory = [];
            $bucket = $this->Buckets->newEntity(['name' => 'Home']);
        }
        $homeContent = $this->Buckets->getBucketContent($id);
        $this->set(compact('homeContent', 'bucket', 'ancestory', 'attach', 'id'));
    }

    public function test() {
        var_dump($this->Buckets->getBucketAncestors(3));
        exit();
    }

}
