<?php
include __DIR__ . '/../layouts/master.php';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Product Create</h5>
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
            <form action="/admin/products" method="POST" class="row" id="productForm">
                <div class="form-group col-12 col-md-6">
                    <label for="name">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="price">Price (USD) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="price">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="quantity">Available Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="quantity">
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
        $("#productForm").validate({
            rules: {
                name: {
                    required: true,
                },
                price: {
                    required: true,
                    min: 0.01
                },
                quantity: {
                    required: true,
                    min: 0
                }
            },
            messages: {
                name: {
                    required: "Product name is required",
                    minlength: "Product name must be at least 3 characters"
                },
                price: {
                    required: "Price is required",
                    min: "Price must be at least $0.01"
                },
                quantity: {
                    required: "Quantity is required",
                    min: "Quantity must be 0 or more"
                }
            },
            errorClass: "text-danger"
        });
    });
</script>
<?php
include __DIR__ . '/../layouts/footer.php';
?>