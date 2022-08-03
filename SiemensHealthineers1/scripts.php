<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/mag-popup.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(function() {
        setTimeout(function() {
            $('body').addClass('loaded');
        }, 500);

        updateEvent('<?= $userid ?>', '<?= $curr_room ?>');
        updateAttendance();
        /* setInterval(function() {
            checkfornewchat('<?= $userid ?>');
        }, 60000); */

        $('.view').magnificPopup({
            disableOn: 700,
            type: 'image',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false
        });

        $('.viewvideo').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            closeBtnInside: true,

            fixedContentPos: false
        });

        // $('.showpdf').magnificPopup({
        //     disableOn: 700,
        //     type: 'iframe',
        //     mainClass: 'mfp-fade',
        //     removalDelay: 160,
        //     preloader: false,

        //     fixedContentPos: false
        // });
        $('.showpdf').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false
        });

        $('.resdl').on('click', function() {
            var res_id = $(this).data('docid');
            $.ajax({
                url: 'control/event.php',
                data: {
                    action: 'updateFileDLCount',
                    resId: res_id,
                    userId: '<?= $userid ?>'
                },
                type: 'post',
                success: function() {
                    //console.log(data);
                }
            });

        });

    });

    function updateAttendance() {
        updateVisitors();
        updateOnline();
        updateOnPage();
    }

    function updateVisitors() {
        $.ajax({
                url: 'control/update.php',
                data: {
                    action: 'updatevisitors',
                },
                type: 'post',
                success: function(output) {
                    //console.log(output);
                    $('#visitorCount').html(output);
                }
            })
            .always(function(data) {
                setTimeout(function() {
                    updateVisitors();
                }, 600000);
            });
    }

    function updateOnline() {
        $.ajax({
                url: 'control/update.php',
                data: {
                    action: 'updateonline',
                },
                type: 'post',
                success: function(output) {
                    //console.log(output);
                    $('#onlineCount').html(output);
                }
            })
            .always(function(data) {
                setTimeout(function() {
                    updateOnline();
                }, 60000);
            });
    }

    function updateOnPage() {
        $.ajax({
                url: 'control/update.php',
                data: {
                    action: 'updateonpage',
                    page: '<?= $curr_room ?>'
                },
                type: 'post',
                success: function(output) {
                    //console.log(output);
                    $('#onpageCount').html(output);
                }
            })
            .always(function(data) {
                setTimeout(function() {
                    updateOnPage();
                }, 60000);
            });
    }

    function updateEvent(userid, loc) {

        $.ajax({
                url: 'control/update.php',
                data: {
                    action: 'updateevent',
                    userId: userid,
                    room: loc
                },
                type: 'post',
                success: function(output) {
                    //console.log(output);
                    if (output == '0') {
                        location.href = './';
                    }
                }
            })
            .always(function(data) {
                setTimeout(function() {
                    updateEvent('<?= $userid ?>', '<?= $curr_room ?>');
                }, 30000);
            });
    }
</script>