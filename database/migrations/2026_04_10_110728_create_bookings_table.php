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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();

            $table->foreignId("user_id")->nullable()->constrained()->onDelete('set null');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->foreignId('camper_id')->constrained()->onDelete('cascade');

            $table->date("start_date");
            $table->date("end_date");

            $table->boolean('terms_accepted')->default(false);
            $table->boolean('privacy_accepted')->default(false);
            $table->timestamp('terms_and_privacy_accepted_at')->nullable();
            $table->ipAddress('terms_and_privacy_accepted_ip')->nullable();
            $table->string('contract_version');

            $table->string('driver_license_path')->nullable();
            $table->string('id_card_path')->nullable();
            $table->string('documents_status')->default('pending')->index();

            $table->decimal("total_price", 10, 2);

            $table->decimal('down_payment', 10, 2);
            $table->boolean('down_paid')->default(false);
            $table->timestamp('down_paid_at')->nullable();

            $table->decimal('balance_payment', 10, 2);
            $table->boolean('balance_paid')->default(false);
            $table->timestamp('balance_paid_at')->nullable();

            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('unpaid')->index();

            $table->timestamp('cancellation_requested_at')->nullable();
            $table->timestamp('cancellation_confirmed_at')->nullable();

            $table->string('stripe_payment_id')->nullable();

            $table->decimal('refund_amount', 10, 2)->nullable();

            $table->string('penalty_receipt_path')->nullable();

            $table->index(['start_date', 'end_date']);
            $table->index(['status', 'payment_status', 'created_at'], 'bookings_cleanup_index');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
