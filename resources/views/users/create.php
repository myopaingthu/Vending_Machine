<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>User Create</h5>
        </div>
        <div class="card-body p-3">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php unset($_SESSION['errors']); endif; ?>
            <form action="/admin/users" method="POST" class="row" id="userForm">
                <div class="form-group col-12 col-md-6">
                    <label for="name">User Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control form-control-sm" name="email">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-sm" name="password">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="password_confirm">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-sm" name="password_confirm">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-control form-control-sm">
                            <option value="">---</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                    </select>
                </div>
                <div class="col-12 mt-3 text-right">
                    <button type="button" class="btn btn-sm btn-cancel back">Back</button>
                    <button type="submit" class="ml-2 btn btn-sm btn-primary">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#userForm").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                },
                password_confirm: {
                    required: true,
                },
                role: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "User name is required",
                },
                email: {
                    required: "Email is required",
                },
                password: {
                    required: "Password is required",
                    min: "Password must be 6 or more"
                },
                password_confirm: {
                    required: "Password is required",
                    min: "Password must be 6 or more"
                },
                role: {
                    required: "Role is required",
                }
            },
            errorClass: "text-danger"
        });
    });
</script>
<?php
include __DIR__ . '/../layouts/footer.php';
?>