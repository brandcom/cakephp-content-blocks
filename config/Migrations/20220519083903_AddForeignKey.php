<?php
use Migrations\AbstractMigration;

class AddForeignKey extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->table('content_blocks_blocks')
            ->changeColumn('content_blocks_area_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('content_blocks_blocks')
            ->addIndex(
                [
                    'content_blocks_area_id',
                ],
                [
                    'name' => 'content_blocks_area_id',
                ]
            )
            ->update();

        $this->table('content_blocks_blocks')
            ->addForeignKey(
                'content_blocks_area_id',
                'content_blocks_areas',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }
}

