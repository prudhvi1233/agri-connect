<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('crop_name')->after('listing_id');
            $table->decimal('price_per_unit', 10, 2)->after('agreed_price');
            $table->decimal('total_amount', 12, 2)->after('price_per_unit');
            $table->string('delivery_location')->nullable()->after('delivery_date');
            $table->string('payment_terms')->default('50% advance, 50% on delivery')->after('delivery_location');
            $table->decimal('advance_percentage', 5, 2)->default(50)->after('payment_terms');
            $table->decimal('advance_amount', 10, 2)->nullable()->after('advance_percentage');
            $table->decimal('final_payment', 10, 2)->nullable()->after('advance_amount');
            $table->date('contract_start_date')->nullable()->after('delivery_location');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->text('additional_terms')->nullable()->after('contract_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'crop_name',
                'price_per_unit',
                'total_amount',
                'delivery_location',
                'payment_terms',
                'advance_percentage',
                'advance_amount',
                'final_payment',
                'contract_start_date',
                'contract_end_date',
                'additional_terms',
            ]);
        });
    }
};
