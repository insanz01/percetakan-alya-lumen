<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename columns in users table
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('avatar', 'foto_profil');
            $table->renameColumn('remember_token', 'token_ingat_saya');
        });

        // Rename columns in uploaded_files table
        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('disk', 'penyimpanan');
            $table->renameColumn('tipe_mime', 'jenis_mime');
        });

        // Rename columns in order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('url_file_diunggah', 'tautan_file_diunggah');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('foto_profil', 'avatar');
            $table->renameColumn('token_ingat_saya', 'remember_token');
        });

        Schema::table('uploaded_files', function (Blueprint $table) {
            $table->renameColumn('penyimpanan', 'disk');
            $table->renameColumn('jenis_mime', 'tipe_mime');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('tautan_file_diunggah', 'url_file_diunggah');
        });
    }
};
