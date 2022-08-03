<script>
  var chkPollRes;

  $(function() {

    <?php
    if ($sess_id != '0') { ?>
      updateSession('<?= $userid; ?>', '<?= $sess_id; ?>');
      chknewpoll('<?= $sess_id; ?>');
    <?php } else { ?>
      checkforlive('<?= $audi_id; ?>');
      getAllSessions();
      //$('#confAgenda').modal('show');
    <?php } ?>

    $(document).on('click', '#showaudiAgenda', function() {
      $('#confAgenda').modal('show');
    });
    $(document).on('click', '#day1', function() {
      $('#day2').removeClass('active');
      $('#day3').removeClass('active');
      $('#day1').addClass('active');
      $('#day1Agenda').css('display', 'block');
      $('#day2Agenda').css('display', 'none');
      $('#day3Agenda').css('display', 'none');
      getSessions('day1', '<?= $audi_id; ?>', '#day1Agenda');
    });

    $(document).on('click', '#day2', function() {
      $('#day1').removeClass('active');
      $('#day3').removeClass('active');
      $('#day2').addClass('active');
      $('#day2Agenda').css('display', 'block');
      $('#day1Agenda').css('display', 'none');
      $('#day3Agenda').css('display', 'none');
      getSessions('day2', '<?= $audi_id; ?>', '#day2Agenda');
    });
    $(document).on('click', '#day3', function() {
      $('#day1').removeClass('active');
      $('#day2').removeClass('active');
      $('#day3').addClass('active');
      $('#day3Agenda').css('display', 'block');
      $('#day1Agenda').css('display', 'none');
      $('#day2Agenda').css('display', 'none');
      getSessions('day3', '<?= $audi_id; ?>', '#day3Agenda');
    });

    $('#confAgenda').on('show.bs.modal', function(e) {
      //alert();
      getAllSessions();
    })
    $('#confAgenda').on('hidden.bs.modal', function(e) {});

    $(document).on('click', '#askques', function() {
      $('.poll').removeClass('show');
      $('.ques').toggleClass('show');
    });

    $(document).on('click', '#close_ques', function() {
      $('.ques').toggleClass('show');
    });

    $(document).on('click', '#takepoll', function() {
      $('.ques').removeClass('show');
      $('.poll').toggleClass('show');
    });

    $(document).on('click', '#close_poll', function() {
      $('.poll').toggleClass('show');
    });

    $(document).on('click', '.send_sesques', function() {
      var sess_id = $(this).data('ses');
      var user_id = $(this).data('user');
      var ques = $('#userques').val();

      if (ques != '') {

        $.ajax({
          url: 'control/ques.php',
          data: {
            action: 'submitques',
            sessId: sess_id,
            userId: user_id,
            ques: ques
          },
          type: 'post',
          success: function(message) {
            console.log(message);
            var response = JSON.parse(message);
            var status = response['status'];
            var msg = response['message'];
            if (status == 'success') {
              $('#userques').val('');
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                text: msg,
                showConfirmButton: false,
                timer: 2000
              })

            } else {
              Swal.fire({
                position: 'top-end',
                icon: 'error',
                text: msg,
                showConfirmButton: false,
                timer: 2000
              })
            }

          }
        });
      } else {
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          text: 'Please enter your question',
          showConfirmButton: false,
          timer: 2000
        })

      }

    });

    $(document).on('click', '.send_takepoll', function() {
      var pollOpt = $("input[name='pollopts']:checked").val();

      if (pollOpt) {
        var user = $(this).data('user');
        var poll = $(this).data('poll');
        $('.send_takepoll').addClass('disabled');

        $.ajax({
          url: 'control/poll.php',
          data: {
            action: 'submitPollResp',
            pollId: poll,
            userId: user,
            answer: pollOpt
          },
          type: 'post',
          success: function(response) {
            if (response !== '0') {
              $('#currpoll').css('display', 'none');
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                text: 'Thank you for taking the poll',
                showConfirmButton: false,
                timer: 2000
              });

              //chkpollres('<?= $audi_id ?>');
              showpollres(poll);

            } else {
              console.log(response);
            }

          }
        });

        $('.send_takepoll').removeClass('disabled');
        return false;
      } else {
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          text: 'Please select your response',
          showConfirmButton: false,
          timer: 2000
        })
      }

    });


  });

  function checkforlive(audi_id) {
    $.ajax({
      url: 'control/sess.php',
      data: {
        action: 'checkforlive',
        audiId: audi_id
      },
      type: 'post',
      success: function(response) {
        if (response != '0') {
          location.href = '?ses=' + response;
        }
      }
    }).always(function(data) {
      setTimeout(function() {
        checkforlive(audi_id);
      }, 20000);
    });

  }

  function getAllSessions() {
    getSessions('day1', '<?= $audi_id ?>', '#day1Agenda');
    getSessions('day2', '<?= $audi_id ?>', '#day2Agenda');
    getSessions('day3', '<?= $audi_id ?>', '#day3Agenda');
  }

  function getSessions(day, audi, elem) {
    $.ajax({
      url: 'control/sess.php',
      data: {
        action: 'getSessions',
        audiId: audi,
        day: day
      },
      type: 'post',
      success: function(response) {
        console.log(response);
        $(elem).html(response);

      }
    });

  }

  function updateSession(user_id, sess_id) {
    $.ajax({
        url: 'control/update.php',
        data: {
          action: 'updatesession',
          sessId: sess_id,
          userId: user_id
        },
        type: 'post',
        success: function(output) {
          console.log(output);
          if (output == '0') {
            location.href = 'login.php';
          }
        }
      })
      .always(function(data) {
        updSes = setTimeout(function() {
          updateSession(user_id, sess_id);
        }, 30000);
      });

  }

  function chknewpoll(sess_id) {
    //console.log('check for new poll');
    $.ajax({
        url: 'control/poll.php',
        data: {
          action: 'checknewpoll',
          sessId: sess_id,
          userId: '<?= $userid ?>'
        },
        type: 'post',
        success: function(output) {
          var cpoll = $('#currpollid').text();
          //console.log(cpoll);
          //console.log(output);
          if (cpoll == output) {
            //console.log('dont change');
          } else {
            //console.log('update poll');
            clearTimeout(chkPollRes);
            $('#currpollresults').css('display', 'none');
            updatePoll(output, sess_id);
          }
          $('#currpollid').text(output);
        }
      })
      .always(function(data) {
        chkPoll = setTimeout(function() {
          chknewpoll('<?php echo $sess_id; ?>');
        }, 30000);
      });


  }

  function updatePoll(poll_id, sess_id) {
    $.ajax({
      url: 'control/poll.php',
      data: {
        action: 'getpoll',
        pollId: poll_id,
        sessId: sess_id,
        userId: '<?= $userid ?>'
      },
      type: 'post',
      success: function(output) {
        if (output == '-1') {
          showpollres(poll_id);
        } else {
          $('#currpoll').html(output);
          $('#currpoll').css('display', 'block');
        }

      }
    });
  }

  function showpollres(poll_id) {
    $.ajax({
        url: 'control/poll.php',
        data: {
          action: 'showpollres',
          pollId: poll_id,
          userId: '<?= $userid ?>'
        },
        type: 'post',
        success: function(output) {
          //getAttendeesCount(web);
          //console.log('PR: ' + output);
          var cpoll = $('#currpollresults').text();

          if (cpoll == output) {
            //
          } else {
            //clearTimeout(chkPoll);
            $('#currpollresults').html(output);
            $('#currpollresults').css('display', 'block');
          }


        }
      })
      .always(function() {
        chkPollRes = setTimeout(function() {
          showpollres(poll_id);
        }, 15000);
      });



  }
</script>
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
  var iframe = document.querySelector('iframe');
  var player = new Vimeo.Player(iframe);
  $('#goFS').on('click', function() {
    player.requestFullscreen().then(function() {
      // the player entered fullscreen
    }).catch(function(error) {
      // an error occurred
      console.log(error);
    });

  });
</script>