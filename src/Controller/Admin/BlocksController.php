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
        $block->type = $data['type'] . 'ContentBlocks';

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
        /**
         * @var Block $block
         * @var Block $contentBlock
         */
        $block = $this->Blocks->get($id);

        $this->loadModel($block->type);

        $contentBlock = $this->{$block->type}
            ->find()
            ->where([
                'content_blocks_block_id' => $block->id,
            ])
            ->first();

        $contained = [
            'Blocks.Areas',
        ];

        if (!empty($contentBlock->getManagedModels())) {
            $associated = array_map(
                function ($model) {
                    return \Cake\Utility\Inflector::pluralize($model);
                },
                $contentBlock->getManagedModels()
            );

            $contained = array_merge($contained, $associated);
        }

        $contentBlock = $this->{$block->type}
            ->find()
            ->where([
                'content_blocks_block_id' => $block->id,
            ])
            ->contain($contained)
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $contentBlock = $this->{$block->type}->patchEntity($contentBlock,
                $this->request->getData());
            if ($this->{$block->type}->save($contentBlock)) {

                $this->Flash->success(__d("ContentBlocks", 'The content blocks block has been saved.'));

                return $this->redirect(['action' => 'edit', $block->id]);
            }
            $this->Flash->error(__d("ContentBlocks",
                'The content blocks block could not be saved. Please, try again.'));
        }

        $this->set(compact('contentBlock'));
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
}
