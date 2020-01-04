$(function () {
    $('.items').hover(function () {
        $(this).find('.deleter, .attacher').show();
    }, function () {
        $(this).find('.deleter, .attacher').hide();
    });
    $('.attacher').click(function (e) {
        var $a = $(this).parents('div.items').find('a.storage-item');
        var $name = $a.attr('title');
        var $path = $a.attr('href');
        parent.attachClicked($name, $path);
        e.stopImmediatePropagation();
        e.preventDefault();
        return false;
    });
    $('#new-folder').click(function () {
        var $name = prompt('New folder name', 'Untitled Folder');
        var $parent = $(this).attr('data-item');
        if ($name !== null) {
            //create  the folder and then refresh the window
            $.post($settings.storageRootUrl + 'buckets/add.json', {name: $name, parent: $parent}, function ($response, $status) {
                if ($status === 'success') {
                    //reload the page
                    window.location.reload();
                }
            });
        }
    });
    $('#delete-folder').click(function () {
        if (confirm('Do you want to delete the current folder and its contents?')) {
            var $folder = $(this).attr('data-item');
            var $parent = $(this).attr('data-parent');
            $.post($settings.storageRootUrl + 'buckets/delete/' + $folder + '.json', {}, function ($response, $status) {
                if ($status === 'success') {
                    //show the parent folder
                    window.location = $settings.storageRootUrl + 'buckets/show/' + $parent;
                }
            });
        }
    });
    $('#rename-folder').click(function () {
        var $folder = $(this).attr('data-item');
        var $oldName = $(this).attr('data-name');
        var $name = prompt('New folder name', $oldName);
        if ($name !== null) {
            //create  the folder and then refresh the window
            $.post($settings.storageRootUrl + 'buckets/edit/' + $folder + '.json', {name: $name}, function ($response, $status) {
                if ($status === 'success') {
                    //reload the page
                    window.location.reload();
                }
            });
        }
    });
});
