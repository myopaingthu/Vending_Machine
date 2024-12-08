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
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php unset($_SESSION['errors']);
            endif; ?>

            <div class="row" id="productCards">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-3 product-card" data-name="<?= strtolower($product['name']) ?>"
                        data-price="<?= $product['price'] ?>">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text">Price: $<?= number_format($product['price'], 2) ?></p>
                                <p class="card-text">Available Quantity: <?= $product['quantity_available'] ?></p>
                                <form class="purchaseForm" method="POST" action="products/<?= $product['id'] ?>/purchase">
                                    <div class="form-group">
                                        <label for="quantity_<?= $product['id'] ?>">Quantity:</label>
                                        <input type="number" class="form-control purchaseQuantity" id="quantity_<?= $product['id'] ?>"
                                            name="quantity" min="1" max="<?= $product['quantity_available'] ?>" required>
                                    </div>
                                    <button type="button" class="btn btn-success btn-block btn-purchase">Purcase</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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

    $(document).on("click", ".btn-purchase", function(e) {
        e.preventDefault();

        const $form = $(this).closest('form');
        const quantity = $form.find(".purchaseQuantity").val();
        const price = parseFloat($form.closest(".product-card").data("price"));
        const total = (quantity * price).toFixed(2);

        Swal.fire({
            title: "Confirm Purchase",
            html: `<p>Are you sure you want to purchase <strong>${quantity}</strong> item(s) for a total of <strong>$${total}</strong>?</p>`,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Yes, Purchase",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $form.off("submit");
                $form.submit();
            }
        });
    });
</script>
<?php
include __DIR__ . '/../layouts/footer.php';
?>