<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'name' => 'SMS',
                'description' => 'Short Message Service',
            ], [
                'name' => 'Email',
                'description' => 'Electronic Mail',
            ], [
                'name' => 'Social Media',
                'description' => 'Platforms like Facebook, Twitter, etc.',
            ], [
                'name' => 'Direct',
                'description' => 'Direct traffic without a referrer',
            ], [
                'name' => 'Referral',
                'description' => 'Traffic from other websites',
            ], [
                'name' => 'Paid Search',
                'description' => 'Traffic from paid search campaigns',
            ]
        ];

        foreach ($channels as $channel) {
            Channel::create($channel);
        }
    }
}
