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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->unique()->after('contract_id');
            $table->string('payment_method')->default('bank_transfer')->after('amount');
            $table->string('payment_type')->default('advance')->after('payment_method'); // advance or final
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payment_method', 'payment_type']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
