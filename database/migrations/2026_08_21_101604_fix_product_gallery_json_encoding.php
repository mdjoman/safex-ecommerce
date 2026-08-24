<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    public function up()
    {
        $products = Product::all();

        foreach ($products as $product) {
            if (!empty($product->gallery) && is_string($product->gallery)) {
                // Clean and decode the gallery
                $gallery = $product->gallery;

                // Try to fix double-encoded JSON
                if (str_starts_with($gallery, '"') && str_ends_with($gallery, '"')) {
                    $gallery = substr($gallery, 1, -1);
                    $gallery = stripslashes($gallery);
                }

                $decoded = json_decode($gallery, true);

                if (is_array($decoded)) {
                    // Re-encode properly
                    $product->gallery = json_encode($decoded);
                    $product->save();
                }
            }
        }
    }

    public function down()
    {
        // Not needed for this migration
    }
};
