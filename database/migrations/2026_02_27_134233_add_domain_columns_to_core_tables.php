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
        if (Schema::hasTable('households')) {
            Schema::table('households', function (Blueprint $table) {
                if (!Schema::hasColumn('households', 'household_number')) {
                    $table->string('household_number')->unique()->after('id');
                }
                if (!Schema::hasColumn('households', 'head_resident_id')) {
                    $table->unsignedBigInteger('head_resident_id')->nullable()->after('household_number');
                }
                if (!Schema::hasColumn('households', 'zone')) {
                    $table->string('zone')->nullable()->after('head_resident_id');
                }
                if (!Schema::hasColumn('households', 'address')) {
                    $table->string('address')->nullable()->after('zone');
                }
                if (!Schema::hasColumn('households', 'remarks')) {
                    $table->text('remarks')->nullable()->after('address');
                }
                if (!Schema::hasColumn('households', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('residents')) {
            Schema::table('residents', function (Blueprint $table) {
                if (!Schema::hasColumn('residents', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('residents', 'household_id')) {
                    $table->unsignedBigInteger('household_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('residents', 'first_name')) {
                    $table->string('first_name')->nullable()->after('household_id');
                }
                if (!Schema::hasColumn('residents', 'middle_name')) {
                    $table->string('middle_name')->nullable()->after('first_name');
                }
                if (!Schema::hasColumn('residents', 'last_name')) {
                    $table->string('last_name')->nullable()->after('middle_name');
                }
                if (!Schema::hasColumn('residents', 'suffix')) {
                    $table->string('suffix', 20)->nullable()->after('last_name');
                }
                if (!Schema::hasColumn('residents', 'birthdate')) {
                    $table->date('birthdate')->nullable()->after('suffix');
                }
                if (!Schema::hasColumn('residents', 'birthplace')) {
                    $table->string('birthplace')->nullable()->after('birthdate');
                }
                if (!Schema::hasColumn('residents', 'gender')) {
                    $table->string('gender', 20)->nullable()->after('birthplace');
                }
                if (!Schema::hasColumn('residents', 'civil_status')) {
                    $table->string('civil_status', 50)->nullable()->after('gender');
                }
                if (!Schema::hasColumn('residents', 'nationality')) {
                    $table->string('nationality')->nullable()->after('civil_status');
                }
                if (!Schema::hasColumn('residents', 'religion')) {
                    $table->string('religion')->nullable()->after('nationality');
                }
                if (!Schema::hasColumn('residents', 'occupation')) {
                    $table->string('occupation')->nullable()->after('religion');
                }
                if (!Schema::hasColumn('residents', 'zone')) {
                    $table->string('zone')->nullable()->after('occupation');
                }
                if (!Schema::hasColumn('residents', 'address')) {
                    $table->string('address')->nullable()->after('zone');
                }
                if (!Schema::hasColumn('residents', 'contact_number')) {
                    $table->string('contact_number')->nullable()->after('address');
                }
                if (!Schema::hasColumn('residents', 'email')) {
                    $table->string('email')->nullable()->after('contact_number');
                }
                if (!Schema::hasColumn('residents', 'voter_status')) {
                    $table->boolean('voter_status')->default(false)->after('email');
                }
                if (!Schema::hasColumn('residents', 'is_indigenous')) {
                    $table->boolean('is_indigenous')->default(false)->after('voter_status');
                }
                if (!Schema::hasColumn('residents', 'is_pwd')) {
                    $table->boolean('is_pwd')->default(false)->after('is_indigenous');
                }
                if (!Schema::hasColumn('residents', 'is_solo_parent')) {
                    $table->boolean('is_solo_parent')->default(false)->after('is_pwd');
                }
                if (!Schema::hasColumn('residents', 'is_4ps')) {
                    $table->boolean('is_4ps')->default(false)->after('is_solo_parent');
                }
                if (!Schema::hasColumn('residents', 'photo')) {
                    $table->string('photo')->nullable()->after('is_4ps');
                }
                if (!Schema::hasColumn('residents', 'remarks')) {
                    $table->text('remarks')->nullable()->after('photo');
                }
                if (!Schema::hasColumn('residents', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('certificate_requests')) {
            Schema::table('certificate_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('certificate_requests', 'resident_id')) {
                    $table->unsignedBigInteger('resident_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('certificate_requests', 'requested_by')) {
                    $table->unsignedBigInteger('requested_by')->nullable()->after('resident_id');
                }
                if (!Schema::hasColumn('certificate_requests', 'processed_by')) {
                    $table->unsignedBigInteger('processed_by')->nullable()->after('requested_by');
                }
                if (!Schema::hasColumn('certificate_requests', 'signatory_id')) {
                    $table->unsignedBigInteger('signatory_id')->nullable()->after('processed_by');
                }
                if (!Schema::hasColumn('certificate_requests', 'certificate_type')) {
                    $table->string('certificate_type')->nullable()->after('signatory_id');
                }
                if (!Schema::hasColumn('certificate_requests', 'purpose')) {
                    $table->string('purpose')->nullable()->after('certificate_type');
                }
                if (!Schema::hasColumn('certificate_requests', 'status')) {
                    $table->string('status')->default('pending')->after('purpose');
                }
                if (!Schema::hasColumn('certificate_requests', 'or_number')) {
                    $table->string('or_number')->nullable()->after('status');
                }
                if (!Schema::hasColumn('certificate_requests', 'fee')) {
                    $table->decimal('fee', 10, 2)->nullable()->after('or_number');
                }
                if (!Schema::hasColumn('certificate_requests', 'requirements_checklist')) {
                    $table->json('requirements_checklist')->nullable()->after('fee');
                }
                if (!Schema::hasColumn('certificate_requests', 'remarks')) {
                    $table->text('remarks')->nullable()->after('requirements_checklist');
                }
                if (!Schema::hasColumn('certificate_requests', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('remarks');
                }
                if (!Schema::hasColumn('certificate_requests', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('approved_at');
                }
                if (!Schema::hasColumn('certificate_requests', 'released_at')) {
                    $table->timestamp('released_at')->nullable()->after('rejected_at');
                }
                if (!Schema::hasColumn('certificate_requests', 'printed_at')) {
                    $table->timestamp('printed_at')->nullable()->after('released_at');
                }
                if (!Schema::hasColumn('certificate_requests', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('blotter_records')) {
            Schema::table('blotter_records', function (Blueprint $table) {
                if (!Schema::hasColumn('blotter_records', 'blotter_number')) {
                    $table->string('blotter_number')->nullable()->after('id');
                }
                if (!Schema::hasColumn('blotter_records', 'complainant_id')) {
                    $table->unsignedBigInteger('complainant_id')->nullable()->after('blotter_number');
                }
                if (!Schema::hasColumn('blotter_records', 'respondent_id')) {
                    $table->unsignedBigInteger('respondent_id')->nullable()->after('complainant_id');
                }
                if (!Schema::hasColumn('blotter_records', 'complainant_name')) {
                    $table->string('complainant_name')->nullable()->after('respondent_id');
                }
                if (!Schema::hasColumn('blotter_records', 'respondent_name')) {
                    $table->string('respondent_name')->nullable()->after('complainant_name');
                }
                if (!Schema::hasColumn('blotter_records', 'incident_type')) {
                    $table->string('incident_type')->nullable()->after('respondent_name');
                }
                if (!Schema::hasColumn('blotter_records', 'incident_date')) {
                    $table->date('incident_date')->nullable()->after('incident_type');
                }
                if (!Schema::hasColumn('blotter_records', 'incident_location')) {
                    $table->string('incident_location')->nullable()->after('incident_date');
                }
                if (!Schema::hasColumn('blotter_records', 'narrative')) {
                    $table->text('narrative')->nullable()->after('incident_location');
                }
                if (!Schema::hasColumn('blotter_records', 'status')) {
                    $table->string('status')->default('open')->after('narrative');
                }
                if (!Schema::hasColumn('blotter_records', 'resolution')) {
                    $table->text('resolution')->nullable()->after('status');
                }
                if (!Schema::hasColumn('blotter_records', 'resolved_at')) {
                    $table->timestamp('resolved_at')->nullable()->after('resolution');
                }
                if (!Schema::hasColumn('blotter_records', 'encoded_by')) {
                    $table->unsignedBigInteger('encoded_by')->nullable()->after('resolved_at');
                }
                if (!Schema::hasColumn('blotter_records', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable()->after('encoded_by');
                }
                if (!Schema::hasColumn('blotter_records', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('blotter_hearings')) {
            Schema::table('blotter_hearings', function (Blueprint $table) {
                if (!Schema::hasColumn('blotter_hearings', 'blotter_record_id')) {
                    $table->unsignedBigInteger('blotter_record_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('blotter_hearings', 'conducted_by')) {
                    $table->unsignedBigInteger('conducted_by')->nullable()->after('blotter_record_id');
                }
                if (!Schema::hasColumn('blotter_hearings', 'hearing_date')) {
                    $table->timestamp('hearing_date')->nullable()->after('conducted_by');
                }
                if (!Schema::hasColumn('blotter_hearings', 'notes')) {
                    $table->text('notes')->nullable()->after('hearing_date');
                }
                if (!Schema::hasColumn('blotter_hearings', 'outcome')) {
                    $table->string('outcome')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('blotter_hearings', 'next_hearing_date')) {
                    $table->timestamp('next_hearing_date')->nullable()->after('outcome');
                }
            });
        }

        if (Schema::hasTable('blotter_attachments')) {
            Schema::table('blotter_attachments', function (Blueprint $table) {
                if (!Schema::hasColumn('blotter_attachments', 'blotter_record_id')) {
                    $table->unsignedBigInteger('blotter_record_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('blotter_attachments', 'uploaded_by')) {
                    $table->unsignedBigInteger('uploaded_by')->nullable()->after('blotter_record_id');
                }
                if (!Schema::hasColumn('blotter_attachments', 'file_name')) {
                    $table->string('file_name')->nullable()->after('uploaded_by');
                }
                if (!Schema::hasColumn('blotter_attachments', 'file_path')) {
                    $table->string('file_path')->nullable()->after('file_name');
                }
                if (!Schema::hasColumn('blotter_attachments', 'file_type')) {
                    $table->string('file_type')->nullable()->after('file_path');
                }
                if (!Schema::hasColumn('blotter_attachments', 'file_size')) {
                    $table->unsignedBigInteger('file_size')->nullable()->after('file_type');
                }
                if (!Schema::hasColumn('blotter_attachments', 'description')) {
                    $table->string('description')->nullable()->after('file_size');
                }
            });
        }

        if (Schema::hasTable('legislations')) {
            Schema::table('legislations', function (Blueprint $table) {
                if (!Schema::hasColumn('legislations', 'title')) {
                    $table->string('title')->nullable()->after('id');
                }
                if (!Schema::hasColumn('legislations', 'type')) {
                    $table->string('type')->nullable()->after('title');
                }
                if (!Schema::hasColumn('legislations', 'number')) {
                    $table->string('number')->nullable()->after('type');
                }
                if (!Schema::hasColumn('legislations', 'series')) {
                    $table->string('series')->nullable()->after('number');
                }
                if (!Schema::hasColumn('legislations', 'description')) {
                    $table->text('description')->nullable()->after('series');
                }
                if (!Schema::hasColumn('legislations', 'content')) {
                    $table->longText('content')->nullable()->after('description');
                }
                if (!Schema::hasColumn('legislations', 'tags')) {
                    $table->json('tags')->nullable()->after('content');
                }
                if (!Schema::hasColumn('legislations', 'status')) {
                    $table->string('status')->default('draft')->after('tags');
                }
                if (!Schema::hasColumn('legislations', 'date_enacted')) {
                    $table->date('date_enacted')->nullable()->after('status');
                }
                if (!Schema::hasColumn('legislations', 'date_effective')) {
                    $table->date('date_effective')->nullable()->after('date_enacted');
                }
                if (!Schema::hasColumn('legislations', 'file_path')) {
                    $table->string('file_path')->nullable()->after('date_effective');
                }
                if (!Schema::hasColumn('legislations', 'uploaded_by')) {
                    $table->unsignedBigInteger('uploaded_by')->nullable()->after('file_path');
                }
                if (!Schema::hasColumn('legislations', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('activity_logs', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('activity_logs', 'action')) {
                    $table->string('action')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('activity_logs', 'subject_type')) {
                    $table->string('subject_type')->nullable()->after('action');
                }
                if (!Schema::hasColumn('activity_logs', 'subject_id')) {
                    $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
                }
                if (!Schema::hasColumn('activity_logs', 'description')) {
                    $table->text('description')->nullable()->after('subject_id');
                }
                if (!Schema::hasColumn('activity_logs', 'old_values')) {
                    $table->json('old_values')->nullable()->after('description');
                }
                if (!Schema::hasColumn('activity_logs', 'new_values')) {
                    $table->json('new_values')->nullable()->after('old_values');
                }
                if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('new_values');
                }
                if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                    $table->text('user_agent')->nullable()->after('ip_address');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep this migration non-destructive for existing environments.
    }
};
