<?php
require_once '../functions.php';
require_once 'logincheck.php';

$pollId = $_GET['id'];
$poll = new Poll();
$poll->__set('poll_id', $pollId);
$resPoll = $poll->getPoll();
if (empty($resPoll)) {
    header('location: polls.php');
}
$poll_question = $resPoll[0]['poll_question'];
?>
<?php
require_once 'header.php';
require_once 'nav.php';
?>

<div class="container-fluid">
    <div class="row p-2">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <?= $poll_question ?>
                </div>
                <div class="card-body">
                    <div id="pollresults"></div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php
require_once 'scripts.php';
?>
<script>
    $(function() {
        getPollResults('<?= $pollId ?>');
    });

    function getPollResults(poll_id) {

        $.ajax({
                url: '../control/poll.php',
                data: {
                    action: 'getpollresults',
                    pollId: poll_id
                },
                type: 'post',
                success: function(response) {
                    $('#pollresults').html(response);
                }
            })
            .always(function(data) {
                setTimeout(function() {
                    getPollResults('<?= $pollId ?>');
                }, 30000);
            });
    }
</script>

<?php
require_once 'footer.php';
?>