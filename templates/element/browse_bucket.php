<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (is_null($attach)) {
    $name = (strlen($bucket->name) > 21) ? substr($bucket->name, 0, 18) . '...' : $bucket->name;
} else {
    $name = (strlen($bucket->name) > 12) ? substr($bucket->name, 0, 9) . '...' : $bucket->name;    
}
?>
<?= $this->Html->link('<div class="col-lg-12">
    <div class="bucket-icon">' . 
        $this->Html->image('MakvilleStorage.icons/bucket.jpg', ['plugin' => 'MakvilleStorage']) . 
    '</div>
    <p class="bucket-name">' . $name . '</p>
</div>', ['plugin' => 'MakvilleStorage', 'controller' => 'buckets', 'action' => isset($action) ? $action : 'manage', $bucket->id, $attach], ['escape' => false, 'title' => $bucket->name, 'class' => 'col-lg-12']);
?>
<hr />