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
} else  {
    $name = (strlen($item->name) > 12) ? substr($item->name, 0, 9) . '...' : $item->name;
}
?>
<?= $this->Html->link('<div class="row col-lg-12"><div class="col-lg-2">
    <div class="item-icon">' . 
        $this->Html->image("MakvilleStorage.icons/$icon") . 
    '</div>
    <p class="item-name">' . $name . '</p>
</div><div class="col-lg-9"><h4>' . $item->label . '</h4><p>' . $item->description . '</p></div></div>', $item->s3_path, ['escape' => false, 'class' => 'storage-item', 'target' => '_blank', 'title' => $item->name, 'class' => 'col-lg-12']);
?>
<hr />