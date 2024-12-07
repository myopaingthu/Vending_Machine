<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vending Machine</title>
    <script src="/js/jquery.min.js"></script>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <script src="/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/css/adminlte.css">
    <script src="/js/adminlte.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--<link rel="stylesheet" href="/css/custom.css">-->
    <script src="/js/common.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>-->
</head>

<body class="login-page" cz-shortcut-listen="true" style="min-height: 494.802px;">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach ($_SESSION['errors'] as $err) {
                            echo "<div>$err</div>";
                        }
                        ?>
                    </div>
                <?php unset($_SESSION['errors']);
                endif; ?>
                <form action="/login" method="post">
                    <div class="input-group mb-3">
                        <input name="email" type="text" class="form-control " placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" class="form-control" name="password" placeholder="New Password">
                        <div class="login-password input-group-append">
                            <div class="input-group-text">
                                <a style="cursor: pointer;" id="password_toggle">
                                    <i class="fas fa-eye-slash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="submit" class="submit-btn btn btn-primary">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>