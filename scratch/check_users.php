<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::with('role')->get();
foreach ($users as $u) {
    echo $u->name . ' | ' . $u->email . ' | ' . ($u->role ? $u->role->name : 'no role') . PHP_EOL;
}
