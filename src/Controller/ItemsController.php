<?php

namespace MakvilleStorage\Controller;

use MakvilleStorage\Controller\AppController;

/**
 * Items Controller
 *
 * @property \MakvilleStorage\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Buckets']
        ];
        $items = $this->paginate($this->Items);

        $this->set(compact('items'));
        $this->set('_serialize', ['items']);
    }

    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $item = $this->Items->get($id, [
            'contain' => ['Buckets']
        ]);

        $this->set('item', $item);
        $this->set('_serialize', ['item']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($bucketId = null, $attach = null) {
        if (!is_null($attach)) {
            $this->viewBuilder()->layout('lite');
        }
        $item = $this->Items->newEntity();
        if ($this->request->is('post')) {
            //do the upload
            $bucketId = $this->request->data('bucket_id');
            if (is_uploaded_file($_FILES['item']['tmp_name'])) {
                //generate a temporary destination
                $filename = $_FILES['item']['name'];
                $destination = TMP . $filename;
                move_uploaded_file($_FILES['item']['tmp_name'], $destination);
            }
            $item->bucket_id = (is_numeric($bucketId) && $bucketId > 0 ) ? $bucketId : null;
            $item->name = $filename;
            $item->label = $this->request->data('label');
            $item->description = $this->request->data('description');
            $item->size = $_FILES['item']['size'];
            $item->type = $_FILES['item']['type'];
            $item->local_path = $destination;
            $item->status = \MakvilleStorage\Model\Table\ItemsTable::ITEM_STATUS_LOCAL;
            if ($this->Items->saveItem($item)) {
                $this->Flash->success(__('The file has been saved.'));
                return $this->redirect(['controller' => 'buckets', 'action' => 'show', $bucketId, $attach]);
            } else {
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
        $buckets = $this->Items->Buckets->find('list', ['limit' => 200]);
        $this->set(compact('item', 'buckets', 'bucketId', 'attach'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $item = $this->Items->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->data);
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
        $buckets = $this->Items->Buckets->find('list', ['limit' => 200]);
        $this->set(compact('item', 'buckets'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $item = $this->Items->get($id);
        if ($this->Items->deleteItem($item)) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'buckets', 'action' => 'show', $item->bucket_id]);
    }

}
