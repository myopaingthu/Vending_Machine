<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>User Edit</h5>
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
            <form action="/admin/users/<?= $user['id'] ?>/update" method="POST" class="row" id="userForm">
                <div class="form-group col-12 col-md-6">
                    <label for="name">User Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name" value="<?= $user['username'] ?>">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control form-control-sm" name="email" value="<?= $user['email'] ?>">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-control form-control-sm">
                            <option value="">---</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= ($user['role'] == 'user') ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <div class="col-12 mt-3 text-right">
                    <button type="button" class="btn btn-sm btn-cancel back">Back</button>
                    <button type="submit" class="ml-2 btn btn-sm btn-primary">
                        Update
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