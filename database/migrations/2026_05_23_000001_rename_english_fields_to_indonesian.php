<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // SQLite only supports one renameColumn per Schema::table() call, so
        // each rename gets its own call — works the same way on MySQL too.
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('avatar', 'foto_profil');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('remember_token', 'token_ingat_saya');
        });

        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('disk', 'penyimpanan');
        });
        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('tipe_mime', 'jenis_mime');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('url_file_diunggah', 'tautan_file_diunggah');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('foto_profil', 'avatar');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('token_ingat_saya', 'remember_token');
        });

        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('penyimpanan', 'disk');
        });
        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('jenis_mime', 'tipe_mime');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('tautan_file_diunggah', 'url_file_diunggah');
        });
    }
};
