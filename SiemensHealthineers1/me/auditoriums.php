<?php
require_once '../functions.php';
require_once 'logincheck.php';
?>
<?php
require_once 'header.php';
require_once 'nav.php';
?>

<div class="container-fluid">
    <div class="row p-2">
        <div class="col-12">
            <a href="audi.php?ac=add" class="btn btn-primary">Add Auditorium</a>
        </div>
    </div>
    <div class="row p-2">
        <div class="col-12">
            <div id="audis">
                <?php
                $audi = new Auditorium();
                $audiList = $audi->getAudis();

                if (!empty($audiList)) {
                ?>
                    <div id="message"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Auditorium</th>
                                <th scope="col">Audi ID</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($audiList as $a) { ?>
                                <tr>
                                    <th><?= $a['audi_name']; ?></th>
                                    <td><?= $a['audi_id']; ?></td>
                                    <td><a href="audi.php?ac=edit&id=<?= $a['audi_id']; ?>" class="btn btn-warning">Edit</a> <a onclick="delaudi('<?= $a['audi_id']; ?>')" class="btn btn-danger">Delete</a> <a onclick="updaudientry('<?= $a['audi_id']; ?>')" class="btn <?php if ($a['entry']) echo 'btn-danger';
                                                                                                                                                                                                                                                                            else echo 'btn-success'; ?>"><?php if ($a['entry']) echo 'Close Entry';
                                                                                                                                                                                                                                                                                                            else echo 'Allow Entry'; ?></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>

<?php
require_once 'scripts.php';
?>
<script>
    function delaudi(audi_id) {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '../controllers/audi.php',
                data: {
                    action: 'delaudi',
                    audi_id: audi_id
                },
                type: 'post',
                success: function(message) {
                    var response = JSON.parse(message);
                    var status = response['status'];
                    var msg = response['message'];
                    if (status == 'success') {
                        location.href = 'auditoriums.php';
                    } else {
                        $('#message').html(msg).removeClass().addClass('alert alert-danger').fadeIn();
                    }
                }
            });
        }
    }

    function updaudientry(audi_id) {
        $.ajax({
            url: '../controllers/audi.php',
            data: {
                action: 'audientry',
                audi_id: audi_id
            },
            type: 'post',
            success: function(message) {
                var response = JSON.parse(message);
                var status = response['status'];
                var msg = response['message'];
                if (status == 'success') {
                    location.href = 'auditoriums.php';
                } else {
                    $('#message').html(msg).removeClass().addClass('alert alert-danger').fadeIn();
                }
            }
        });
    }
</script>
<?php
require_once 'footer.php';
?>