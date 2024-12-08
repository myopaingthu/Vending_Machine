<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container p-3">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h5>Transaction List</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr class="bg-primary">
                        <th>
                            <a href="?sort=product_name&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Product Name <?= $sortField === 'product_name' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=user_name&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                User Name <?= $sortField === 'user_name' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=quantity&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Quantity <?= $sortField === 'quantity' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=total_price&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>" class="text-white">
                                Total Price <?= $sortField === 'total_price' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                    </tr>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['product_name']) ?></td>
                                <td><?= htmlspecialchars($transaction['user_name']) ?></td>
                                <td><?= htmlspecialchars($transaction['quantity']) ?></td>
                                <td>$<?= number_format($transaction['total_price'], 2) ?></td>
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