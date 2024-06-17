<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Vendors Table
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_account_number')->nullable();
            $table->string('company_code')->nullable();
            $table->string('account_group')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('name')->nullable();
            $table->string('search_term_1')->nullable();
            $table->string('search_term_2')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('po_box')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_key')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('bank_region')->nullable();
            $table->string('recon_account')->nullable();
            $table->string('sort_key')->nullable();
            $table->string('cash_management_group')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->boolean('payment_block')->default(false);
            $table->string('withholding_tax')->nullable();
            $table->timestamps();
        });

        // VendorChanges Table
        Schema::create('vendor_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('change_type');
            $table->string('previous_sap_vendor_number')->nullable();
            $table->text('remarks');
            $table->string('status')->default('Pending');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // CustomerMaster Table
        Schema::create('customer_master', function (Blueprint $table) {
            $table->id();
            $table->string('customer_account_number')->nullable();
            $table->string('company_code')->nullable();
            $table->string('account_group')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('name')->nullable();
            $table->string('search_term_1')->nullable();
            $table->string('search_term_2')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('po_box')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_key')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('bank_region')->nullable();
            $table->string('recon_account')->nullable();
            $table->string('sort_key')->nullable();
            $table->string('cash_management_group')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->boolean('payment_block')->default(false);
            $table->string('withholding_tax')->nullable();
            $table->timestamps();
        });

        // CustomerChanges Table
        Schema::create('customer_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_master');
            $table->string('change_type');
            $table->string('previous_sap_customer_number')->nullable();
            $table->text('remarks');
            $table->string('status')->default('Pending');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // ApprovalSteps Table
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_name');
            $table->integer('step_order');
            $table->timestamps();
        });

        // ApprovalLogs Table
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->string('change_type');
            $table->integer('change_id');
            $table->foreignId('step_id')->constrained('approval_steps');
            $table->foreignId('user_id')->constrained('users');
            $table->string('action');
            $table->timestamp('action_at');
            $table->text('comments');
            $table->timestamps();
        });

        // Reminders Table
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('change_type');
            $table->integer('change_id');
            $table->timestamp('sent_at')->nullable();
            $table->string('recipient_email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('approval_logs');
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('customer_changes');
        Schema::dropIfExists('customer_master');
        Schema::dropIfExists('vendor_changes');
        Schema::dropIfExists('vendors');
    }
}
