<?php ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $this->fetch('title'); ?></title>

        <!-- Custom styles for this template -->

        <?= $this->Html->css('plugins/bootstrap/bootstrap'); ?>
        <?= $this->Html->css('plugins/bootstrap/bootstrap-grid'); ?>
        <?= $this->Html->css('plugins/bootstrap/bootstrap-reboot'); ?>

    </head>

    <body style="padding: 50px">
        <div class="">
            <div class="col-lg-12">
                <div class="container-fluid">
                    <!-- Container Begin -->
                    <?= $this->fetch('content'); ?>
                    <!-- End of Container  -->
                </div>
            </div>

        </div>
        <?= $this->Html->script('settings.js'); ?>
        <?= $this->Html->script('plugins/jquery/jquery-3.4.1.min'); ?>
        <?= $this->Html->script('plugins/bootstrap/bootstrap.bundle'); ?>
        <?= $this->Html->script('plugins/font-awesome/all'); ?>
        <?= $this->fetch('scriptBottom'); ?>
    </body>
</html>
