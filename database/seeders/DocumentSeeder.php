<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $uploader = User::where('role', 'admin')->first() ?? User::first();

        Document::create([
            'title'     => 'CSIT Society Constitution',
            'file_path' => 'documents/sample-constitution.pdf',
            'category'  => 'constitution',
            'user_id'   => $uploader->id,
        ]);

        Document::create([
            'title'     => 'Executive Meeting Minutes — March 2026',
            'file_path' => 'documents/sample-minutes.pdf',
            'category'  => 'minutes',
            'user_id'   => $uploader->id,
        ]);

        Document::create([
            'title'     => 'Annual Report 2025/26',
            'file_path' => 'documents/sample-report.pdf',
            'category'  => 'reports',
            'user_id'   => $uploader->id,
        ]);
    }
}
