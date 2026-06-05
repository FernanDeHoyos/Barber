<?php
use App\Models\User;
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$users = User::all();
foreach($users as $u) {
    echo $u->email . ' - Role: ' . $u->role . PHP_EOL;
}
?>
