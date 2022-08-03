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
            <a href="sess.php?ac=add" class="btn btn-primary">Add Session</a>
        </div>
    </div>
    <div class="row p-2">
        <div class="col-12">
            <div id="message"></div>
            <div id="sessions" class="mt-2"></div>
        </div>
    </div>

</div>

<?php
require_once 'scripts.php';
?>

<script>
    $(function() {
        getSessionsList('1');
    });

    function gotoPage(pageNum) {
        getSessionsList(pageNum);

        return false;
    }

    function getSessionsList(pageNum) {

        $.ajax({
            url: '../control/sess.php',
            data: {
                action: 'getallsessions',
                pagenum: pageNum
            },
            type: 'post',
            success: function(response) {
                $('#sessions').html(response);
            }
        });
    }

    function updateLiveStatus(sess_id, val) {
        $.ajax({
            url: '../control/sess.php',
            data: {
                action: 'updatelivestatus',
                sessId: sess_id,
                value: val
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
                        text: 'Live status has been updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    getSessionsList('1');
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        text: 'Live status could not be updated!',
                        showConfirmButton: false,
                        timer: 2000
                    })

                }


            }
        });
    }

    function updSession(sess_id) {
        var sesID = '#session' + sess_id;
        var value = $(sesID).val();

        $.ajax({
            type: 'POST',
            url: '../control/sess.php',
            data: {
                action: 'updsessstatus',
                sessId: sess_id,
                status: value
            },
            success: function(output) {
                var response = JSON.parse(output);
                var status = response['status'];
                var msg = response['message'];
                if (status == 'success') {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        text: 'Session status has been updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        text: 'Session status could not be updated!',
                        showConfirmButton: false,
                        timer: 2000
                    })

                }



            }
        });

    }

    function delSes(sess_id) {
        if (confirm('Are you sure?')) {
            $.ajax({
                type: 'POST',
                url: '../control/sess.php',
                data: {
                    action: 'delsess',
                    sessId: sess_id
                },
                success: function(output) {

                    var response = JSON.parse(output);
                    var status = response['status'];
                    var msg = response['message'];
                    if (status == 'success') {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            text: 'Session has been deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        getSessionsList('1');
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            text: 'Session could not be deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        })

                    }



                }
            });
        }

    }

    function updEntry(sess_id) {
        var respid = '#entry' + sess_id;
        var curval = 0;
        if ($(respid).prop("checked") == true) {
            curval = 1;
        }
        $.ajax({
            url: '../control/sess.php',
            data: {
                action: 'updateentrystatus',
                sessId: sess_id,
                value: curval
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
                        text: 'Session entry status has been updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        text: 'Session entry status could not be updated!',
                        showConfirmButton: false,
                        timer: 2000
                    })

                }
            }
        });
    }

    function updShow(sess_id) {
        var respid = '#show' + sess_id;
        var curval = 0;
        if ($(respid).prop("checked") == true) {
            curval = 1;
        }
        $.ajax({
            url: '../control/sess.php',
            data: {
                action: 'updateshowstatus',
                sessId: sess_id,
                value: curval
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
                        text: 'Session show status has been updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        text: 'Session show status could not be updated!',
                        showConfirmButton: false,
                        timer: 2000
                    })

                }
            }
        });
    }
</script>
<?php
require_once 'footer.php';
?>