<?php
require_once '../functions.php';
require_once 'logincheck.php';
?>

<?php
require_once 'header.php';
require_once 'nav.php';
?>
<div class="container">
    <div class="row p-2">
        <div class="col-12">
            <button type="button" class="btn btn-primary  btn-sm" data-toggle="modal" data-target="#addadmin-modal">Add Admin</button>
        </div>
    </div>
    <div class="row p-2">
        <div class="col-12">
            <div id="message"></div>
            <div id="admins"></div>
        </div>
    </div>

</div>

<div class="modal fade" id="addadmin-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="add-message"></div>
                <form id="addadmin" method="post" action="#">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="userpwd" name="userpwd" placeholder="Enter Password" autocomplete="on" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="userrole" id="userrole" required>
                            <option value="0">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="moderator">Question Moderator</option>
                            <option value="helpdesk">Helpdesk Manager</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" id="addAdmin">Add Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editadmin-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit-message"></div>
                <form id="editadmin" method="post" action="#">
                    <div class="form-group">
                        <input type="text" class="form-control" id="updname" name="updname" placeholder="Enter Username" readonly>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="updrole" id="updrole" required>
                            <option value="0">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="moderator">Question Moderator</option>
                            <option value="helpdesk">Helpdesk Manager</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <input type="hidden" id="adminid" name="adminid" readonly>
                    <button type="submit" class="btn btn-primary btn-sm" id="updAdmin">Update Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
require_once 'scripts.php';
?>
<script>
    getAdmins();
    $(function() {

        $("button#addAdmin").click(function() {
            if ($('#userrole').val() != '0') {
                $.ajax({
                    type: "POST",
                    url: "../controllers/admin.php",
                    data: {
                        action: 'addadmin',
                        data: $('form#addadmin').serialize()
                    },
                    success: function(message) {
                        var response = JSON.parse(message);
                        //console.log(response['status']);
                        var status = response['status'];
                        var msg = response['message'];
                        if (status == 'error') {
                            //$('#add-message').html(msg).removeClass().addClass('alert alert-danger').fadeIn();
                            showMessage('error', msg);
                        } else {
                            //$('#add-message').html(msg).removeClass().addClass('alert alert-success').fadeIn();
                            showMessage('success', msg);
                            $('form#addadmin')[0].reset();
                            setTimeout(function() {
                                $('#addadmin-modal').modal('hide');
                            }, 1000);
                            getAdmins();

                        }
                        //$("#addadmin-modal").modal('hide');
                    },
                    error: function() {

                    }
                });
            } else {
                alert('Select User Role');
            }


            return false;
        });

        $(".getadmin").click(function() {
            var admin = $(this).data('id');
            var adminInfo = getAdminInfo(admin);
        });


        $("button#updAdmin").click(function() {
            if ($('#updrole').val() != '0') {
                $.ajax({
                    type: "POST",
                    url: "../controllers/admin.php",
                    data: {
                        action: 'updadmin',
                        data: $('form#editadmin').serialize()
                    },
                    success: function(message) {
                        //console.log(message);
                        var response = JSON.parse(message);
                        var status = response['status'];
                        var msg = response['message'];
                        if (status == 'error') {
                            $('#edit-message').html(msg).removeClass().addClass('alert alert-danger').fadeIn();
                        } else {
                            $('#edit-message').html(msg).removeClass().addClass('alert alert-success').fadeIn();
                            $('form#editadmin')[0].reset();

                            setTimeout(function() {
                                $('#editadmin-modal').modal('hide');
                            }, 1000);

                            getAdmins();

                        }
                    },
                    error: function() {

                    }
                });
            } else {
                alert('Select Admin Role');
            }
            return false;
        });

        $('#addadmin-modal').on('hidden.bs.modal', function(e) {
            $('#add-message').html('').removeClass().fadeOut(500);
            $('form#addadmin')[0].reset();
        });
        $('#addadmin-modal').on('show.bs.modal', function(e) {
            $('#add-message').html('').removeClass().fadeOut(500);
            $('form#addadmin')[0].reset();
        });

        $(".deladmin").click(function() {
            var admin = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.ajax({
                    type: "POST",
                    url: "../controllers/admin.php",
                    data: {
                        action: 'deladmin',
                        adminid: admin
                    },
                    success: function(message) {
                        console.log(message);
                        var response = JSON.parse(message);
                        var status = response['status'];
                        var msg = response['message'];
                        if (status == 'error') {
                            $('#message').html(msg).removeClass().addClass('alert alert-danger').fadeIn();
                        } else {
                            getAdmins();
                            //$('#message').html(msg).removeClass().addClass('alert alert-success').fadeIn().delay(3000).fadeOut();
                            showMessage('success', msg);

                        }
                    },
                    error: function() {

                    }
                });
            }
        });
    });

    function getAdmins() {
        $.ajax({
            type: "POST",
            url: "../controllers/admin.php",
            data: {
                action: 'getadmins'
            },
            success: function(response) {
                $('#admins').html(response);
            }
        });
    }

    function getAdminInfo(admin) {
        $.ajax({
            type: "POST",
            url: "../controllers/admin.php",
            data: {
                action: 'getadmin',
                admin: admin
            },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response);
                $('#updname').val(response[0]['username']);
                $('#updrole').val(response[0]['role']);
                $('#adminid').val(admin);
                $('#editadmin-modal').modal('show');
                //$('#admins').html(response);
            }
        });
    }
</script>
<?php
require_once 'footer.php';
?>