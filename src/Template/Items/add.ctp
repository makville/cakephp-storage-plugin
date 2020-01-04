<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row" style="margin-top:-20px">

    <div class="large-12 columns">
        <div class="box">
            <div class="box-header bg-transparent">
                <h3 class="box-title">
                    <span style="font-size: 14px; font-weight: bold;">Save to: <?= isset($bucket) ? $bucket->name : 'Home'; ?></span>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <?= $this->Form->create($item, ['type' => 'file']) ?>
                <?php
                    echo $this->Form->input('bucket_id', ['value' => $bucketId, 'empty' => true, 'type' => 'hidden']);
                    echo $this->Form->input('item', ['type' => 'file', 'label' => '']);
                ?>
                <?= $this->Form->button(__('Save')) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('MakvilleStorage.behaviors/bucket', ['block' => 'scriptBottom']);?>
