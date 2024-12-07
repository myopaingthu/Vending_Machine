<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container p-3">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h5>User List</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="/admin/users/create" class="btn btn-primary">Create New</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr class="bg-primary">
                        <th>
                            <a href="?sort=username&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Name <?= $sortField === 'username' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=email&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Email <?= $sortField === 'email' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=role&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Role <?= $sortField === 'role' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="javascript:void(0)" class="text-white">
                                Action
                            </a>
                        </th>
                    </tr>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= strtoupper(htmlspecialchars($user['role'])) ?></td>
                                <td>
                                    <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                                    <?php if ($_SESSION['user_id'] != $user['id']) { ?>
                                        <form action="/admin/users/<?= $user['id'] ?>/delete" method="POST" id="del-user-<?= $user['id'] ?>" class="d-inline">
                                            <a class="btn btn-sm btn-danger destroy_btn"
                                                data-origin="del-user-<?= $user['id'] ?>" data-text="Are you sure you want to delete  <?= $user['username'] ?>?" data-original-title="">
                                                Delete
                                            </a>

                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&sort=<?= $sortField ?>&order=<?= $sortOrder ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&sort=<?= $sortField ?>&order=<?= $sortOrder ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&sort=<?= $sortField ?>&order=<?= $sortOrder ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".destroy_btn", function() {
        Swal.fire({
            //title: "Are you sure?",
            text: $(this).attr("data-text"),
            icon: "warning",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
        }).then((response) => {
            if (response.isConfirmed) {
                var form_id = $(this).attr("data-origin");
                $("#" + form_id).submit();
            }
        });
    });
</script>
<?php
include __DIR__ . '/../layouts/footer.php';
?>