<?php
echo $this->Html->css('MakvilleStorage.style');
?>
<div class="row">

    <div class="large-12 columns">
        <div class="box">
            <div class="box-header bg-transparent">
                <?php if ($id > 0) : ?>
                <h3 class="box-title"><?=$this->Html->link('<i class="fa fa-arrow-left"></i>', ['plugin' => 'MakvilleStorage', 'controller' => 'buckets', 'action' => 'show', $bucket->parent, $attach], ['escape' => false, 'title' => 'Previous folder']); ?>
                    <span style="font-size: 14px; font-weight: bold;">
                    <?php 
                    foreach($ancestory as $id => $ancestor) {
                        echo $this->Html->link($ancestor, ['plugin' => 'MakvilleStorage', 'controller' => 'buckets', 'action' => 'show', $id, $attach]) . '&nbsp;/&nbsp;';    
                    }
                    ?>
                    <?= $bucket->name; ?>
                    </span>
                </h3>
                <?php else: ?>
                <h3 class="box-title"><span style="font-size: 14px; font-weight: bold;">Home</span></h3>
                <?php endif; ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <div class="row" style="padding-top: 0 !important;">
                    <div class="box" style="margin-top: 0; padding-top: 0;">
                        <div class="box-header bg-transparent">
                            <div class="pull-left box-tools" style="font-size: 20px; color: #000;">
                                <span class="box-btn" data-widget="collapse"><i class="icon-menu" style="color: black;"></i></span>
                            </div>
                        </div>
                        <?php if (is_null($attach)): ?>
                        <div class="box-body">
                            <ul class="menu-list">
                                <li id="new-folder" data-item="<?=$bucket->id;?>">New folder</li>
                                <li id="delete-folder" data-item="<?=$bucket->id;?>" data-parent="<?=$bucket->parent;?>">Delete folder</li>
                                <li id="rename-folder" data-item="<?=$bucket->id;?>" data-name="<?=$bucket->name;?>">Rename folder</li>
                                <li><?=$this->Html->link('New file', ['plugin' => 'MakvilleStorage', 'controller' => 'items', 'action' => 'add', $id, $attach]); ?></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                <?php 
                    foreach ($homeContent['buckets'] as $bucket ) {
                        echo $this->Element('MakvilleStorage.bucket', ['bucket' => $bucket, 'attach' => $attach]);
                    }
                    foreach ($homeContent['items'] as $item ) {
                        echo $this->Element('MakvilleStorage.item', ['item' => $item, 'attach' => $attach]);
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('MakvilleStorage.behaviors/bucket', ['block' => 'scriptBottom']);?>
