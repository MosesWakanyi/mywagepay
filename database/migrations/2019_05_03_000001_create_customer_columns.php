<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table((new User)->getTable(), function (Blueprint $table) {
            $table->string('mywagepay_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new User)->getTable(), function (Blueprint $table) {
            $table->dropColumn([
                'mywagepay_id',
            ]);
        });
    }
};
