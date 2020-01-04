<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
switch ($item->type) {
    case 'image/png':
    case 'image/jpeg':
    case 'image/gif':
        $icon = 'image.jpg';
        break;
    case 'application/pdf':
        $icon = 'pdf.jpg';
        break;
    default:
        $icon = 'generic.jpg';
        break;
}
if ( is_null($attach)) {
    $name = (strlen($item->name) > 21) ? substr($item->name, 0, 18) . '...' : $item->name;
    $extra = '<div class="deleter">' . $this->Form->postLink('<i class="fa fa-trash"></i>', ['plugin' => 'MakvilleStorage', 'controller' => 'items', 'action' => 'delete', $item->id], ['escape' => false, 'confirm' => 'Are you sure you want to delete this item?']) . '</div>';
} else  {
    $name = (strlen($item->name) > 12) ? substr($item->name, 0, 9) . '...' : $item->name;
    $extra = '<div class="attacher"><a href="#"><i class="fa fa-paperclip"></i></a></div>';
}
?>
<?= $this->Html->link('<div class="items large-2 medium-2 columns">
    <div class="item-icon">' . 
        $this->Html->image("MakvilleStorage.icons/$icon") . 
    '</div>
    <p class="item-name">' . $name . '</p>' . $extra . '
</div>', $item->s3_path, ['escape' => false, 'class' => 'storage-item', 'target' => '_blank', 'title' => $item->name]);
?>