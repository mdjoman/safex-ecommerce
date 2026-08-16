<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Basic Info Fields
            if (!Schema::hasColumn('banners', 'badge')) {
                $table->string('badge')->nullable()->after('title');
            }

            if (!Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable()->after('badge');
            }

            // Button Fields
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->string('button_text')->default('Shop Now')->after('description');
            }

            if (!Schema::hasColumn('banners', 'button_url')) {
                $table->string('button_url')->nullable()->after('button_text');
            }

            // Display Order
            if (!Schema::hasColumn('banners', 'order')) {
                $table->integer('order')->default(0)->after('button_url');
            }

            // Stats Fields (3 items)
            if (!Schema::hasColumn('banners', 'stat1_label')) {
                $table->string('stat1_label')->default('Year Warranty')->after('order');
                $table->string('stat1_value')->default('2')->after('stat1_label');
            }

            if (!Schema::hasColumn('banners', 'stat2_label')) {
                $table->string('stat2_label')->default('Year Free Service')->after('stat1_value');
                $table->string('stat2_value')->default('4')->after('stat2_label');
            }

            if (!Schema::hasColumn('banners', 'stat3_label')) {
                $table->string('stat3_label')->default('Support')->after('stat2_value');
                $table->string('stat3_value')->default('24/7')->after('stat3_label');
            }

            // Style/Color Fields - Nullable with Default Values
            if (!Schema::hasColumn('banners', 'text_color')) {
                $table->string('text_color')->default('#FFFFFF')->after('stat3_value');
            }

            if (!Schema::hasColumn('banners', 'bg_color')) {
                $table->string('bg_color')->nullable()->after('text_color');
            }

            if (!Schema::hasColumn('banners', 'button_color')) {
                $table->string('button_color')->default('#0637A1')->after('bg_color');
            }

            if (!Schema::hasColumn('banners', 'button_hover_color')) {
                $table->string('button_hover_color')->default('#03246E')->after('button_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'badge',
                'description',
                'button_text',
                'button_url',
                'order',
                'stat1_label',
                'stat1_value',
                'stat2_label',
                'stat2_value',
                'stat3_label',
                'stat3_value',
                'text_color',
                'bg_color',
                'button_color',
                'button_hover_color',
            ]);
        });
    }
};
