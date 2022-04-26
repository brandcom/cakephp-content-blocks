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

4. Modify your `TextContentBlocksTable.php` so that your `TextContentBlocksTable` extends `ContentBlocks\Model\Entity\BlocksTable`, and modify the class as follows:

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

If you click on the button, a new Block will be added to the Block Area. You can enter content and save.

### 3. Create a template for your block

ContentBlock templates are elements. In your `Template/Element` folder, create the folder `content_blocks/` and the file `text.ctp`. 

The template file name is always the lower_case_underscore version of your model name, omitting the content_block. 

So the template for `MyCoolHeroHeaderContentBlock` will be in `/Template/Element/content_blocks/my_cool_hero_header.ctp`. 

Your `TextContentBlock` Entity will be available as the `$block` variable. 

Now you can create your template: 

```
<?php
/**
 * @var \App\Model\Entity\TextContentBlock $block
 */
?>
<section class="my-12">
    <h2>
        <?= $block->title ?>
    </h2>
    <div class="content">
        <?= $block->content ?>
    </div>
</section>
```

> **Note:** You can change the rendering logic in your `TextContentBlock` Entity by overriding the `render()` method. 

### 4. Display the blocks

To render your block area for an entity, add this cell to your template:

```
<?= $this->cell("ContentBlocks.BlocksArea", ['entity' => $article]) ?>
```

### 5. Modifying the Admin interface 

If you want to change how fields are displayed, you can override the `getFields()` method in your `TextContentBlock` Entity.

The method should return an array of all editable fields with the field names as keys and an array as value which is passed to `FormHelper::control` as `$options`: 

```
public function getFields(): array
{
    return array_merge(
        parent::getFields(),
        [
            'title' => [
                'label' => __("Block Title"),
            ],
            'style' => [
                'label' => __("Choose a style for this block."),
                'options' => [
                    'default' => __("Default Style"),
                    'funky' => __("Other cool Style"),
                ],
            ],
        ]
    );
}
```

Special field options: 
* `beforeControl`: Will be rendered before the respective control.
* `afterControl`: Will be rendered after the respective control. 

To change the hidden fields, override `Block::getHiddenFields()`:

```
public function getHiddenFields(): array
{
    return array_merge(
        parent::getHiddenFields(),
        [
            'some_field',
            'another_hidden_field',
        ]
    );
}
```

E.g., `content_blocks_block_id` is hidden by default, and you may want to make it editable. 


### 6. Containing associated Models 

Define a `beforeFind()` method in your `TextContenBlocksTable` 

```
public function beforeFind(Event $event, Query $query): Query
{
    return $query->contain([
        'Images',
    ]);
}
```

### 7. Edit related models

The plugin supports an admin interface even for related models. 

#### HasMany relations

Say you have a  `SliderContentBlock` with several slides. This means, you will have e.g. a `SliderBlockSlide` entity and 
a `SliderBlockSlidesTable`. 

1. Let your Slide entity use the `ContentBlocks\Model\Entity\Traits\BelongsToBlockTrait`
2. Contain the Slide as shown in **6. Containing associated Models** 
3. In your `SliderContentBlock`, override the `Block`'s method `getManagedModels()`. This should return an array of all related models which shall be editable in the admin form.

```
public function getManagedModels(): array
{
    return ["SliderBlockSlide"];
}
```

4. To customize the appearance in the admin form, you can override the methods from the `BelongsToBlockTrait`, e.g. to define a nicer title or control what fields are available for editing (similarly as in **5. Modifying the Admin interface**). 

### 8. Pass custom data to the block template

By default, the containing model of the block area is passed to the template as `$owner`, 
so, e.g., if you have an `Article` entity which has a `TextContentBlock`, your article entity will be accessible 
in the `text.ctp` as `$owner`. 

You can override this - and add more view variables - by overriding the `getViewVariables()` method in your `*ContentBlocksTable`.

If you want to rename `$owner` and add more data, you can do that like so:  

```
public function getViewVariables($entity): array
{
    $vars = parent::getViewVariables($entity);

    return [
        'article' => $owner,
        'random' => "This is just a string",
        'someOtherVariable' => $this->getSomeOtherVariable($entity),
    ];
}
```

`$entity`, the instance of your `*ContentBlock`, will be passed to the method.

## Contribution

You can contribute to this project via pull requests or issues. 