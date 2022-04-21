# ContentBlocks plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require brandcom/cakephp-content-blocks
```

Load the plugin: 

```
bin/cake plugin load ContentBlocks
```

... and run the migrations: 

```
bin/cake migrations migrate --plugin ContentBlocks
```

## Getting Started

### 1. Create Blocks

The plugin does not come with any blocks, so you will have to create them on your own. 

Let's create a simple TextContentBlock with a `title` and a `content` field. 

> **Note:** Every ContentBlock must end with `*ContentBlock`

1. In your database, create a table `text_content_blocks` with the fields `title` (Varchar 255) and `content` (Text). **Note:** You will also need an `id` field and a field `content_blocks_block_id` as `int(11), unsigned`.
2. Then, run the `bin/cake bake model text_content_blocks` command. You won't need any templates or controllers.
3. Edit the baked `TextContentBlock.php` file and make the class extend `ContentBlocks\Model\Entity\Block` instead of `Entity`.

4. Modify your `TextContentBlocksTable.php`:

Set the relation: 
```
$this->belongsTo('Blocks', [
    'foreignKey' => 'content_blocks_block_id',
    'className' => 'ContentBlocks.Blocks',
]);
```

Fix the `buildRules()` method, set `table` to `Blocks`: 
```
$rules->add($rules->existsIn(['content_blocks_block_id'], 'Blocks'));
```


You will find more on customizing your Block below, but let's now add it to one of your pages. 

### 2. Admin interface

To add your TextContentBlock to an Entity, e.g., `Article`, add the `BlocksAdmin` cell to your edit template: 

```
<?= $this->cell("ContentBlocks.BlocksAdmin", ['entity' => $article]) ?>
```

This will render a table representing the BlockArea for the respective entity. 

At the bottom of the table, there will be a button for each of your ContentBlocks. You should already find a button with the title `Text`. 
