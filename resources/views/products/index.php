<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container p-3">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h5>Product List</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="/admin/products/create" class="btn btn-primary">Create New</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr class="bg-primary">
                        <th>
                            <a href="?sort=name&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Name <?= $sortField === 'name' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=price&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Price <?= $sortField === 'price' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=quantity_available&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Quantity <?= $sortField === 'quantity_available' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="javascript:void(0)" class="text-white">
                                Action
                            </a>
                        </th>
                    </tr>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                                <td><?= htmlspecialchars($product['quantity_available']) ?></td>
                                <td>
                                    <a href="/admin/products/<?= $product['id'] ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="/admin/products/<?= $product['id'] ?>/delete" method="POST" id="del-product-<?= $product['id'] ?>" class="d-inline">
                                        <a class="btn btn-sm btn-danger destroy_btn"
                                            data-origin="del-product-<?= $product['id'] ?>" data-text="Are you sure you want to delete  <?= $product['name'] ?>?" data-original-title="">
                                            Delete
                                        </a>

                                    </form>
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