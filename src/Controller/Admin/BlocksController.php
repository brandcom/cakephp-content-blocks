<?php
namespace ContentBlocks\Controller\Admin;

use Cake\ORM\Table;
use Cake\Utility\Inflector;
use ContentBlocks\Controller\AppController;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\Model\Table\BlocksTable;

/**
 * ContentBlocksBlocks Controller
 *
 * @property BlocksTable $Blocks
 * @method \ContentBlocks\Model\Entity\Block[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlocksController extends AppController
{
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        /**
         * @var Block $block
         */
        $data = $this->getRequest()->getData();
        $block = $this->Blocks->newEntity();
        $block->content_blocks_area_id = $data['area_id'];
        $block->type = $data['type'];

        $block = $this->Blocks->saveOrFail($block);
        $this->loadModel($block->type);


        $contentBlock = $this->{$block->type}->newEntity();
        $contentBlock->content_blocks_block_id = $block->id;

        $this->{$block->type}->save($contentBlock);

        return $this->redirect([
            'action' => 'edit',
            $block->id,
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Content Blocks Block id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contentBlock = $this->Blocks->getContentBlock($id);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $contentBlock = $this->{$contentBlock->getSource()}->patchEntity($contentBlock,
                $this->request->getData());
            if ($this->{$contentBlock->getSource()}->save($contentBlock)) {

                $this->Flash->success(__d("ContentBlocks", 'The content blocks block has been saved.'));

                return $this->redirect(['action' => 'edit', $contentBlock->block->id]);
            }
            $this->Flash->error(__d("ContentBlocks",
                'The content blocks block could not be saved. Please, try again.'));
        }

        $adminViewVariables = $this->{$contentBlock->getSource()}
            ->getAdminViewVariables($contentBlock);

        $this->set(compact('contentBlock'));
        $this->set($adminViewVariables);
    }

    /**
     * Delete method
     *
     * @param string|null $id Content Blocks Block id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id, $type)
    {
        $this->request->allowMethod(['post', 'delete']);

        $this->loadModel($type);
        /**
         * @var Table $blockTable
         */
        $blockTable = $this->{$type};

        $block = $blockTable->get($id, [
            'contain' => [
                'Blocks',
            ]
        ]);

        if ($this->Blocks->delete($block->block) && $blockTable->delete($block)) {
            $this->Flash->success(__('The content blocks block has been deleted.'));
        } else {
            $this->Flash->error(__('The content blocks block could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->getRequest()->getData('redirect'));
    }

    public function addRelated($id, $type, $relatedModel)
    {
        $block_id_field = Inflector::underscore(rtrim($type, "s") . 'Id');
        $this->loadModel($relatedModel);
        /**
         * @var Table $relatedTable
         */
        $relatedTable = $this->{$relatedModel};

        $entity = $relatedTable->newEntity();
        $entity->{$block_id_field} = $id;

        if ($relatedTable->save($entity)) {
            return $this->redirect(['action' => 'editRelated', $id, $type, $relatedModel, $entity->id]);
        }

        return $this->redirect($this->referer());
    }

    public function editRelated($id, $type, $relatedModel, $relatedId)
    {
        $this->loadModel($type);
        $this->loadModel($relatedModel);

        $blockTable = $this->{$type};
        $relatedTable = $this->{$relatedModel};

        $block = $blockTable->get($id, [
            'contain' => ['Blocks'],
        ]);
        $relatedEntity = $relatedTable->get($relatedId);

        if ($this->request->is(["post", "put", "patch"])) {
            $relatedTable->patchEntity($relatedEntity, $this->getRequest()->getData());
            if ($relatedTable->save($relatedEntity)) {
                $this->Flash->success(__d("ContentBlocks", "{0} was saved successfully.", [$relatedModel]));
            } else {
                $this->Flash->error(__d("ContentBlocks", "{0} could not be saved.", [$relatedModel]));
            }
        }

        $this->set(compact("relatedEntity", "block", "id", "type"));
    }

    public function deleteRelated($relatedId, $relatedModel)
    {
        $this->request->allowMethod(['post', 'delete']);

        $this->loadModel($relatedModel);
        $relatedTable = $this->{$relatedModel};
        $relatedEntity = $relatedTable->get($relatedId);

        if ($relatedTable->delete($relatedEntity)) {
            $this->Flash->success(__d("ContentBlocks", "The {0} has been deleted.", [
                $relatedEntity->getTitle(),
            ]));
            return $this->redirect($this->getRequest()->getData("redirect"));
        }

        $this->Flash->error(__d("ContentBlocks", "Could not delete {0}", [
            $relatedEntity->getTitle(),
        ]));
    }
}
