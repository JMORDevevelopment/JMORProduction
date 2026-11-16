<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SOURCE_URL = 'https://jmor.com/blog/mahwah-nj-it-services-computer-support-for-home-business';

    /**
     * These quotes were already published by JMOR with first-name-only
     * attribution. The public page never exposes the linked customer account
     * or any contact details.
     *
     * @var array<int, array{display_name: string, service_used: string, message: string}>
     */
    private const TESTIMONIALS = [
        [
            'display_name' => 'John D., Mahwah resident',
            'service_used' => 'Managed IT Support',
            'message' => 'The JMOR Connection, Inc has been our go-to IT partner for years. Their team is knowledgeable, friendly, and always responsive to our needs.',
        ],
        [
            'display_name' => 'Sarah K., business owner',
            'service_used' => 'Managed IT Support',
            'message' => 'We were experiencing frequent downtime and technical issues, but since switching to The JMOR Connection, Inc, our systems have been running smoothly and efficiently. Their team is top-notch!',
        ],
        [
            'display_name' => 'Mark Z., Mahwah resident',
            'service_used' => 'Computer Support',
            'message' => 'I was hesitant to switch to a new IT provider, but The JMOR Connection, Inc has exceeded my expectations. Their team is professional, courteous, and always willing to go the extra mile.',
        ],
    ];

    public function up(): void
    {
        foreach (self::TESTIMONIALS as $testimonial) {
            $exists = DB::table('testimony_form')
                ->where('display_name', $testimonial['display_name'])
                ->where('message', $testimonial['message'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('testimony_form')->insert([
                'customer_id' => null,
                'display_name' => $testimonial['display_name'],
                'service_used' => $testimonial['service_used'],
                'message' => $testimonial['message'],
                'status' => 1,
                'consent_to_publish' => true,
                'verified_at' => '2026-11-13 00:00:00',
                'source_url' => self::SOURCE_URL,
                'published' => '2025-02-20 00:00:00',
            ]);
        }
    }

    public function down(): void
    {
        DB::table('testimony_form')
            ->where('source_url', self::SOURCE_URL)
            ->whereIn('display_name', array_column(self::TESTIMONIALS, 'display_name'))
            ->delete();
    }
};
