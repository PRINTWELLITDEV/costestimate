<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Site table
        Schema::create('site', function (Blueprint $table) {
            $table->string('site', 8)->primary();
            $table->string('site_desc', 50);
            $table->text('address')->nullable();
            $table->text('site_link')->nullable();
            $table->text('logo_pic_url')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->text('create_by')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->text('deleted_by')->nullable();

        });

        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->string('site', 8);
            $table->string('userid', 8);
            $table->string('name')->nullable();
            $table->string('password', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('department', 50)->nullable();
            $table->string('section', 50)->nullable();
            $table->string('position', 50)->nullable();
            $table->integer('level')->nullable();
            $table->integer('status')->nullable();
            $table->string('gender', 10)->nullable();
            $table->text('profile_pic_url')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('updated_by_sql', 128)->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->text('deleted_by')->nullable();

            $table->rememberToken();

            $table->primary(['site', 'userid']);
        });
        // Trigger to auto-set create_date on insert
        // DB::unprepared('DROP TRIGGER IF EXISTS trg_rsusers_update');
        // Trigger to auto-update updated_date and updated_by on rsusers table
        DB::unprepared('
            CREATE TRIGGER trg_users_update
            ON dbo.users
            AFTER UPDATE
            AS
            BEGIN
                SET NOCOUNT ON;
                UPDATE u
                SET
                    updated_date = GETDATE(),
                    updated_by_sql = SUSER_SNAME()
                FROM users u
                INNER JOIN inserted i
                    ON u.site = i.site
                AND u.userid = i.userid;
            END
        ');



        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('site', 8)->nullable();
            // $table->string('rsuserid', 8)->nullable();
            $table->string('user_id', 8)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');

            $table->primary('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('site');
    }
};
