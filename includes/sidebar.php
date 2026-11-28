<?php
global $TABLES;
$script = basename($_SERVER['SCRIPT_NAME']);
$curT = $_GET['t'] ?? '';

// menu in priority order, grouped
$groups = [
    ''                    => ['dashboard'],
    'People'              => ['student', 'staff'],
    'Fees & Payments'     => ['payments_page', 'payment', 'monthly_fee', 'membership'],
    'Training & Contests' => ['training', 'contest', 'contest_participation'],
    'Gym'                 => ['equipment', 'shop', 'sales_history'],
    'Reports'             => ['reports'],
];
// returns [label, link, active] or null when the user may not see it
$menuItem = function ($k) use ($base, $TABLES, $script, $curT) {
    if ($k === 'dashboard')     return ['Dashboard', $base . 'pages/dashboard.php', $script === 'dashboard.php'];
    if ($k === 'payments_page') return can_read('monthly_fee')
        ? ['Student Payments', $base . 'pages/payments.php', in_array($script, ['payments.php', 'student_ledger.php'])] : null;
    if ($k === 'reports')       return ['Reports', $base . 'pages/reports.php', $script === 'reports.php'];
    if (!isset($TABLES[$k]) || !can_read($k)) return null;
    if ($k === 'contest_participation')
        return [$TABLES[$k]['label'], $base . 'pages/participants.php', $script === 'participants.php'];
    return [$TABLES[$k]['label'], $base . 'pages/records.php?t=' . $k,
            in_array($script, ['records.php', 'add.php', 'edit.php', 'delete.php']) && $curT === $k];
};
?>
<aside class="sidebar">
    <div class="brand">Gym Management</div>
    <nav>
        <?php foreach ($groups as $title => $keys):
            $items = array_filter(array_map($menuItem, $keys));
            if (!$items) continue; ?>
            <?php if ($title !== ''): ?><div class="nav-group"><?= h($title) ?></div><?php endif; ?>
            <?php foreach ($items as $it): ?>
                <a href="<?= $it[1] ?>" class="<?= $it[2] ? 'active' : '' ?>"><?= h($it[0]) ?></a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>
    <a class="logout" href="<?= $base ?>auth/logout.php">Logout</a>
</aside>
