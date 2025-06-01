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
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('consultand_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->enum('payment_status', ['pendiente', 'pagada', 'cancelada'])->default('pendiente');
            $table->string(('cancelation_reason'))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
