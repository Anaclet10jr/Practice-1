<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@rwandarealty.rw'],
            [
                'name'        => 'Site Admin',
                'password'    => Hash::make('password'),
                'role'        => User::ROLE_ADMIN,
                'is_approved' => true,
            ]
        );

        // ── Sample Agents ──────────────────────────────────────────
        $agent1 = User::firstOrCreate(
            ['email' => 'agent1@example.com'],
            [
                'name'         => 'Jean-Pierre Habimana',
                'password'     => Hash::make('password'),
                'role'         => User::ROLE_AGENT,
                'agency_name'  => 'Kigali Properties Ltd',
                'phone'        => '+250 788 000 001',
                'is_approved'  => true,
            ]
        );

        $agent2 = User::firstOrCreate(
            ['email' => 'agent2@example.com'],
            [
                'name'         => 'Marie Uwase',
                'password'     => Hash::make('password'),
                'role'         => User::ROLE_AGENT,
                'agency_name'  => 'Rwandan Homes',
                'phone'        => '+250 788 000 002',
                'is_approved'  => true,
            ]
        );

        // ── Sample Client ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name'        => 'Alice Mukamana',
                'password'    => Hash::make('password'),
                'role'        => User::ROLE_CLIENT,
                'is_approved' => true,
            ]
        );

        // ── Sample Properties ──────────────────────────────────────
        $sampleProperties = [
            [
                'agent_id'    => $agent1->id,
                'title'       => 'Luxurious 4-Bedroom Villa in Kiyovu, Kigali',
                'type'        => 'sale',
                'price'       => 185_000_000,
                'bedrooms'    => 4,
                'bathrooms'   => 3,
                'area_sqm'    => 320,
                'district'    => 'Nyarugenge',
                'sector'      => 'Kiyovu',
                'address'     => 'KN 15 Ave, Kiyovu',
                'features'    => ['Parking', 'Garden', 'Security Guard', 'Generator'],
                'is_featured' => true,
            ],
            [
                'agent_id'    => $agent1->id,
                'title'       => 'Modern 2BR Apartment in Kimihurura',
                'type'        => 'rent',
                'price'       => 550_000,
                'price_period'=> 'monthly',
                'bedrooms'    => 2,
                'bathrooms'   => 1,
                'area_sqm'    => 95,
                'district'    => 'Gasabo',
                'sector'      => 'Kimihurura',
                'address'     => 'KG 544 St, Kimihurura',
                'features'    => ['Parking', 'Water Tank', 'CCTV'],
                'is_featured' => true,
            ],
            [
                'agent_id'    => $agent2->id,
                'title'       => 'Stunning 3BR House in Musanze near Volcanoes',
                'type'        => 'sale',
                'price'       => 62_000_000,
                'bedrooms'    => 3,
                'bathrooms'   => 2,
                'area_sqm'    => 210,
                'district'    => 'Musanze',
                'sector'      => 'Muhoza',
                'address'     => 'Muhoza, Musanze',
                'features'    => ['Garden', 'Parking', 'Solar Panel'],
                'is_featured' => false,
            ],
            [
                'agent_id'    => $agent2->id,
                'title'       => 'Commercial Office Space in Kigali CBD',
                'type'        => 'rent',
                'price'       => 1_200_000,
                'price_period'=> 'monthly',
                'bedrooms'    => 0,
                'bathrooms'   => 2,
                'area_sqm'    => 180,
                'district'    => 'Nyarugenge',
                'sector'      => 'Nyarugenge',
                'address'     => 'KN 4 Ave, CBD',
                'features'    => ['Elevator', 'CCTV', 'Air Conditioning', 'Parking'],
                'is_featured' => true,
            ],
        ];

        foreach ($sampleProperties as $data) {
            Property::firstOrCreate(
                ['title' => $data['title']],
                [
                    ...$data,
                    'slug'        => Str::slug($data['title']) . '-' . Str::random(6),
                    'description' => "This is a sample property description for {$data['title']}. "
                                   . "Located in {$data['district']}, it offers excellent access to amenities "
                                   . "and transport links. Perfect for families and professionals alike.",
                    'status'      => Property::STATUS_APPROVED,
                    'images'      => [],
                    'latitude'    => -1.9441 + (rand(-100, 100) / 1000),
                    'longitude'   => 30.0619 + (rand(-100, 100) / 1000),
                ]
            );
        }

        $this->command->info('✅ Seeded: 1 admin, 2 agents, 1 client, 4 properties');
        $this->command->line('');
        $this->command->line('Login credentials:');
        $this->command->line('  Admin:  admin@rwandarealty.rw / password');
        $this->command->line('  Agent:  agent1@example.com   / password');
        $this->command->line('  Client: client@example.com   / password');
    }
}
