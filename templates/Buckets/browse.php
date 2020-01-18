<?php
echo $this->Html->css('MakvilleStorage.style');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php if ($id > 0) : ?>
                        <h3 class="box-title"><?= $this->Html->link('<i class="fa fa-arrow-left"></i>', ['plugin' => 'MakvilleStorage', 'controller' => 'buckets', 'action' => 'browse', $bucket->parent, $attach], ['escape' => false, 'title' => 'Previous folder']); ?>
                            <span style="font-size: 14px; font-weight: bold;">
                                <?php
                                foreach ($ancestory as $id => $ancestor) {
                                    echo $this->Html->link($ancestor, ['plugin' => 'MakvilleStorage', 'controller' => 'buckets', 'action' => 'browse', $id, $attach]) . '&nbsp;/&nbsp;';
                                }
                                ?>
                                <?= $bucket->name; ?>
                            </span>
                        </h3>
                    <?php else: ?>
                        <h3 class="box-title"><span style="font-size: 14px; font-weight: bold;">Home</span></h3>
                    <?php endif; ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php
                    foreach ($homeContent['buckets'] as $bucket) {
                        echo $this->Element('MakvilleStorage.browse_bucket', ['bucket' => $bucket, 'attach' => $attach, 'action' => 'browse']);
                    }
                    foreach ($homeContent['items'] as $item) {
                        echo $this->Element('MakvilleStorage.browse_item', ['item' => $item, 'attach' => $attach]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('MakvilleStorage.behaviors/bucket', ['block' => 'scriptBottom']); ?>
