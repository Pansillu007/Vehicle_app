<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 20)->default('user')->after('email');
            });
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicles', 'image_path')) {
                $table->string('image_path')->nullable()->after('vin_number');
            }
            if (! Schema::hasColumn('vehicles', 'next_service_due_date')) {
                $table->date('next_service_due_date')->nullable()->after('image_path');
            }
            if (! Schema::hasColumn('vehicles', 'next_service_due_mileage')) {
                $table->unsignedInteger('next_service_due_mileage')->nullable()->after('next_service_due_date');
            }
            if (! Schema::hasColumn('vehicles', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('service_records', function (Blueprint $table) {
            if (! Schema::hasColumn('service_records', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('action');
                $table->string('description');
                $table->nullableMorphs('subject');
                $table->json('properties')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activity_logs');

        Schema::table('service_records', function (Blueprint $table) {
            if (Schema::hasColumn('service_records', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            $columns = ['image_path', 'next_service_due_date', 'next_service_due_mileage'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('vehicles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
