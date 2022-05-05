<?php

use Migrations\AbstractMigration;

class ContentBlocks2 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->table('content_blocks_blocks')
            ->addColumn('is_published', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('html_anchor', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->update();
    }

    public function down()
    {
        $this->table('content_blocks_blocks')
            ->removeColumn('is_published')
            ->removeColumn('html_anchor')
            ->update();
    }
}