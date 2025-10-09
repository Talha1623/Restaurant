<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting foreign key fix...\n\n";

try {
    // Step 1: Drop old foreign key
    echo "Step 1: Dropping old foreign key constraint...\n";
    DB::statement('ALTER TABLE `menus` DROP FOREIGN KEY `menus_category_id_foreign`');
    echo "✅ Old foreign key dropped successfully!\n\n";
    
    // Step 2: Add new foreign key pointing to menu_categories
    echo "Step 2: Adding new foreign key constraint...\n";
    DB::statement('ALTER TABLE `menus` ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE SET NULL');
    echo "✅ New foreign key added successfully!\n\n";
    
    echo "🎉 Foreign key constraint fixed!\n";
    echo "\nNow category changes in settings will automatically reflect in menus!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nIf you see this error, please run these SQL queries manually in phpMyAdmin:\n";
    echo "1. ALTER TABLE `menus` DROP FOREIGN KEY `menus_category_id_foreign`;\n";
    echo "2. ALTER TABLE `menus` ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE SET NULL;\n";
}

