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

        </div>
    </div>
    <div class="row p-2">
        <div class="col-12">
            <div id="message"></div>
            <div id="questions" class="mt-2"></div>
        </div>
    </div>

</div>
<?php
require_once 'scripts.php';
?>
<script>
    $(function() {
        getQuesList('1');
    });

    function gotoPage(pageNum) {
        getQuesList(pageNum);

        return false;
    }

    function getQuesList(pageNum) {

        $.ajax({
            url: '../control/ques.php',
            data: {
                action: 'getallquestions',
                pagenum: pageNum
            },
            type: 'post',
            success: function(response) {
                $('#questions').html(response);
            }
        });
    }


    function delQues(ques_id) {
        if (confirm('Are you sure?')) {
            $.ajax({
                type: 'POST',
                url: '../control/ques.php',
                data: {
                    action: 'delques',
                    quesId: ques_id
                },
                success: function(output) {

                    var response = JSON.parse(output);
                    var status = response['status'];
                    var msg = response['message'];
                    if (status == 'success') {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            text: 'Question has been deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        getQuesList('1');
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            text: 'Question could not be deleted!',
                            showConfirmButton: false,
                            timer: 2000
                        })

                    }



                }
            });
        }

    }

    function getupdate() {
        var curr = $('#ques_count').text();

        $.ajax({
            type: 'POST',
            url: '../control/ques.php',
            data: {
                action: 'getquesupdate'
            },
            type: 'post',
            success: function(output) {
                var msg = output;

                if (Number(output) > Number(curr)) {
                    newmsg = Number(output) - Number(curr);
                    if (newmsg == "1") {
                        msg = newmsg + " new question available.";
                    } else {
                        msg = newmsg + " new questions available.";
                    }
                    msg += " <a href='questions.php' style='color:#0c0;'>Refresh</a>";
                } else {
                    msg = "";
                }

                $("#ques_update").html(msg);

            }
        });
    }

    setInterval(function() {
        getupdate();
    }, 60000);
</script>
<?php
require_once 'footer.php';
?>