<?php
$menuItems = [
    ['label' => 'Products', 'url' => '/admin/products', 'roles' => ['admin']],
    ['label' => 'Users', 'url' => '/admin/users', 'roles' => ['admin']],
    ['label' => 'Transactions', 'url' => '/admin/transactions', 'roles' => ['admin']],
    ['label' => 'Products', 'url' => '/products', 'roles' => ['user']],
    ['label' => 'Transactions', 'url' => '/transactions', 'roles' => ['user']],
];

$userRole = $_SESSION['role'] ?? null;

$currentUrl = $_SERVER['REQUEST_URI'] ?? null;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text font-weight-light">Vending Machine</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if (in_array($userRole, $menuItem['roles'])): ?>
                        <li class="nav-item">
                            <a href="<?= $menuItem['url'] ?>" class="nav-link <?= ($currentUrl === $menuItem['url']) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p><?= $menuItem['label'] ?></p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li class="nav-item">
                    <form method="POST" action="/logout" class="nav-link">
                        <a hrefs="#" onclick="event.preventDefault(); this.closest('form').submit();" style="cursor: pointer;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>