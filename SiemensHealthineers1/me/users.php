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
            <div id="users">
            </div>
        </div>
    </div>

</div>




<?php
require_once 'scripts.php';
?>

<script>
    $(function() {
        getUserList('1');
    });

    function gotoPage(pageNum) {
        getUserList(pageNum);

        return false;
    }

    function getUserList(pageNum) {

        $.ajax({
            url: '../control/users.php',
            data: {
                action: 'getallusers',
                pagenum: pageNum
            },
            type: 'post',
            success: function(response) {
                $('#users').html(response);
            }
        });
    }

    function delUser(userid) {

        if (confirm('Are you syre?')) {

            $.ajax({
                url: '../control/users.php',
                data: {
                    action: 'deluser',
                    userId: userid
                },
                type: 'post',
                success: function(response) {
                    if (response == 'done') {
                        alert('User deleted');
                        getUserList('1');
                    }
                }
            });
        }
    }
</script>
<?php
require_once 'footer.php';
?>