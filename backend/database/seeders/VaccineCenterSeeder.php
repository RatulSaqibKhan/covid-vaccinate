<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VaccineCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Getting tomorrow's date
        $availableDate = Carbon::now()->addDay()->toDateString();

        // Define 50 Dhaka City vaccine centers with randomized daily capacity between 6 and 23
        $vaccineCenters = [
            ['name' => 'Dhaka Medical College', 'address' => 'Secretariat Road, Dhaka'],
            ['name' => 'Shaheed Suhrawardy Medical College', 'address' => 'Sher-E-Bangla Nagar, Dhaka'],
            ['name' => 'Kurmitola General Hospital', 'address' => 'Airport Road, Dhaka'],
            ['name' => 'Mugda Medical College', 'address' => 'Mugdapara, Dhaka'],
            ['name' => 'Sir Salimullah Medical College', 'address' => 'Mitford Road, Dhaka'],
            ['name' => 'Ibrahim Cardiac Hospital', 'address' => 'Shahbag, Dhaka'],
            ['name' => 'National Institute of Traumatology', 'address' => 'Sher-E-Bangla Nagar, Dhaka'],
            ['name' => 'Ahsania Mission Cancer Hospital', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Bangabandhu Sheikh Mujib Medical University', 'address' => 'Shahbagh, Dhaka'],
            ['name' => 'Holly Family Red Crescent Hospital', 'address' => 'Eskaton Garden Road, Dhaka'],
            ['name' => 'Ad-Din Hospital', 'address' => 'Moghbazar, Dhaka'],
            ['name' => 'Square Hospital', 'address' => 'Panthapath, Dhaka'],
            ['name' => 'Labaid Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Popular Diagnostic Centre', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Birdem General Hospital', 'address' => 'Shahbagh, Dhaka'],
            ['name' => 'United Hospital', 'address' => 'Gulshan, Dhaka'],
            ['name' => 'Evercare Hospital', 'address' => 'Bashundhara, Dhaka'],
            ['name' => 'Apollo Diagnostic Centre', 'address' => 'Banani, Dhaka'],
            ['name' => 'BRB Hospital', 'address' => 'Panthapath, Dhaka'],
            ['name' => 'Dhaka Shishu Hospital', 'address' => 'Sher-E-Bangla Nagar, Dhaka'],
            ['name' => 'National Institute of Neurosciences', 'address' => 'Agargaon, Dhaka'],
            ['name' => 'Ahsania Mission Medical College', 'address' => 'Uttara, Dhaka'],
            ['name' => 'Asgar Ali Hospital', 'address' => 'Gandaria, Dhaka'],
            ['name' => 'Central Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Ibn Sina Hospital', 'address' => 'Kallyanpur, Dhaka'],
            ['name' => 'Shahid Ziaur Rahman Medical College', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Mirpur General Hospital', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Tairunnessa Memorial Medical College', 'address' => 'Uttara, Dhaka'],
            ['name' => 'Al Helal Specialized Hospital', 'address' => 'Kallyanpur, Dhaka'],
            ['name' => 'Kuwait Bangladesh Friendship Hospital', 'address' => 'Uttara, Dhaka'],
            ['name' => 'Al Manar Hospital', 'address' => 'Mohammadpur, Dhaka'],
            ['name' => 'National Heart Foundation Hospital', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Samorita Hospital', 'address' => 'Panthapath, Dhaka'],
            ['name' => 'Dhanmondi General Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Bashundhara Eye Hospital', 'address' => 'Bashundhara, Dhaka'],
            ['name' => 'LabAid Cancer Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Dhaka Orthopaedic Hospital', 'address' => 'Malibagh, Dhaka'],
            ['name' => 'Islamia Eye Hospital', 'address' => 'Farmgate, Dhaka'],
            ['name' => 'Zainul Haque Sikder Women’s Medical College', 'address' => 'Gulshan, Dhaka'],
            ['name' => 'Dhaka Urology and Nephrology Hospital', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Azimpur Maternity Center', 'address' => 'Azimpur, Dhaka'],
            ['name' => 'Dhaka Community Hospital', 'address' => 'Moghbazar, Dhaka'],
            ['name' => 'Holy Crescent Hospital', 'address' => 'Banani, Dhaka'],
            ['name' => 'Khidmah Hospital', 'address' => 'Jatrabari, Dhaka'],
            ['name' => 'Japan Bangladesh Friendship Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Life Hospital', 'address' => 'Khilgaon, Dhaka'],
            ['name' => 'Pangu Hospital', 'address' => 'Sher-E-Bangla Nagar, Dhaka'],
            ['name' => 'Ibn Sina Medical College', 'address' => 'Kallyanpur, Dhaka'],
        ];

        foreach ($vaccineCenters as $center) {
            DB::table('vaccine_centers')->insert([
                'name' => $center['name'],
                'address' => $center['address'],
                'daily_capacity' => rand(6, 23), // Random capacity between 6 and 23
                'available_date' => $availableDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
