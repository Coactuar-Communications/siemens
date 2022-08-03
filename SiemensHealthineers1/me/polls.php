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
            <a href="poll.php?ac=add" class="btn btn-primary">Add Poll</a>
        </div>
    </div>
    <div class="row p-2">
        <div class="col-12">
            <div id="message"></div>
            <div id="polls" class="mt-2"></div>
        </div>
    </div>

</div>
<?php
require_once 'scripts.php';
?>
<script>
    $(function() {
        getPollsList('1');
    });

    function gotoPage(pageNum) {
        getPollsList(pageNum);

        return false;
    }

    function getPollsList(pageNum) {

        $.ajax({
            url: '../control/poll.php',
            data: {
                action: 'getallpolls',
                pagenum: pageNum
            },
            type: 'post',
            success: function(response) {
                $('#polls').html(response);
            }
        });
    }

    function updatePoll(pollid, sessid, value) {
        $.ajax({
            url: '../control/poll.php',
            data: {
                action: 'updatepoll',
                pollId: pollid,
                sessId: sessid,
                value: value
            },
            type: 'post',
            success: function(output) {
                var response = JSON.parse(output);
                var status = response['status'];
                var msg = response['message'];
                if (status == 'success') {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        text: 'Poll status has been updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    getPollsList('1');
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        text: 'Poll status could not be updated!',
                        showConfirmButton: false,
                        timer: 2000
                    })

                }

            }
        });
    }

    function delPoll(poll_id) {
        if (confirm('Are you sure?')) {
            $.ajax({
                type: 'POST',
                url: '../control/poll.php',
                data: {
                    action: 'delpoll',
                    pollId: poll_id
                },
                success: function(output) {

                    var response = JSON.parse(output);
                    var status = response['status'];
                    var msg = response['message'];
                    if (status == 'success') {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            text: 'Poll has been deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        getPollsList('1');
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            text: 'Poll could not be deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        })

                    }



                }
            });
        }

    }
</script>

<?php
require_once 'footer.php';
?>