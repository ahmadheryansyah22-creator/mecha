<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('mechanic_fee', 10, 2)->nullable()->after('final_price');
            $table->enum('mechanic_status', ['waiting', 'accepted', 'rejected'])->default('waiting')->after('mechanic_fee');
            $table->text('mechanic_notes')->nullable()->after('mechanic_status');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['mechanic_fee', 'mechanic_status', 'mechanic_notes']);
        });
    }
};
