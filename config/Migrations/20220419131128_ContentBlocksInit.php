<?php
use Migrations\AbstractMigration;

class ContentBlocksInit extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {
        $this->table('content_blocks_areas')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('owner_model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('owner_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('content_blocks_blocks')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('content_blocks_area_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sort', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
    }

    public function down()
    {
        $this->table('content_blocks_areas')->drop()->save();
        $this->table('content_blocks_blocks')->drop()->save();
    }
}
