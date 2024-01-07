<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admins = [
            'admin_hash_id' => md5(uniqid(rand(), true)),
            'school_id' => 100, // Adjust this value based on your needs
            'name' => 'Admin User',
            'email' => 'admin@email.com', // Change this email address
            'password' => Hash::make(1234), // Change this password
            'verify' => 1, // Set to 1 if you want to mark the email as verified
            'remember_token' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'pin' => 102, // Adjust this if needed
            'user_status' => 1, // Set to 1 if you want to mark the user as active
        ];

        DB::table('admins')->insert($admins);

        DB::table('general_settings')->insert([
            'set_hash_id' => md5(uniqid(rand(), true)),
            'school_title' => 'Bangla Demo School',
            'school_title_bn' => 'বাংলা ডেমো স্কুল',
            'school_short_name' => 'BDS',
            'school_code' => '12345',
            'school_eiin_no' => '123456',
            'school_email' => 'school@example.com',
            'school_phone' => '1234567890',
            'school_phone1' => '0987654321',
            'school_phone2' => '1122334455',
            'school_fax' => '9876543210',
            'school_address' => 'Dhaka, Bangladesh',
            'school_country' => 'Bangladesh',
            'currency_sign' => '৳',
            'currency_name' => 'Bangladeshi Taka',
            'school_geocode' => 'your_geocode',
            'school_facebook' => 'facebook.com/school',
            'school_twitter' => 'twitter.com/school',
            'school_google' => 'plus.google.com/school',
            'school_linkedin' => 'linkedin.com/school',
            'school_youtube' => 'youtube.com/school',
            'school_copyrights' => '© 2024 Bangla Demo School',
            'school_logo' => 'your_logo_filename.png',
            'currency' => 'BDT',
            'set_status' => 1,
            'timezone' => 'Asia/Dhaka',
            'language' => 'en',
            'enable_notifications' => true,
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Version Table Starts

        $versions = [
            [
                'version_hash_id' => Str::random(10),
                'version_name' => 'English',
                'version_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'version_hash_id' => Str::random(10),
                'version_name' => 'Bangla',
                'version_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('edu_versions')->insert($versions);

        // Version Table Ends

        // Seed classes for both "English" and "Bangla" versions
        $classes = [
            [
                'class_hash_id' => Str::random(10),
                'version_id' => 1, // Assuming "1" is the ID for the "English" version
                'class_name' => 'Class One',
                'class_numeric' => 1,
                'class_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_hash_id' => Str::random(10),
                'version_id' => 1,
                'class_name' => 'Class Two',
                'class_numeric' => 2,
                'class_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_hash_id' => Str::random(10),
                'version_id' => 2, // Assuming "2" is the ID for the "Bangla" version
                'class_name' => 'Class One',
                'class_numeric' => 1,
                'class_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_hash_id' => Str::random(10),
                'version_id' => 2,
                'class_name' => 'Class Two',
                'class_numeric' => 2,
                'class_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more classes for both versions as needed
        ];

        // Insert the classes
        DB::table('edu_classes')->insert($classes);


        // Define section names
        $sectionNames = ['Section A', 'Section B', 'Section C'];

        // Seed sections for both "English" and "Bangla" versions and classes
        $sections = [];

        foreach ($sectionNames as $sectionName) {
            $sections[] = [
                'section_hash_id' => Str::random(10),
                'version_id' => 1, // Assuming "1" is the ID for the "English" version
                'class_id' => 1, // Assuming "1" is the ID for the "Class One"
                'class_teacher_id' => null, // You can specify the teacher's ID if needed
                'section_name' => $sectionName,
                'max_students' => 30, // Set the maximum number of students
                'section_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $sections[] = [
                'section_hash_id' => Str::random(10),
                'version_id' => 1, // Assuming "1" is the ID for the "English" version
                'class_id' => 2, // Assuming "2" is the ID for the "Class Two"
                'class_teacher_id' => null, // You can specify the teacher's ID if needed
                'section_name' => $sectionName,
                'max_students' => 30, // Set the maximum number of students
                'section_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $sections[] = [
                'section_hash_id' => Str::random(10),
                'version_id' => 2, // Assuming "2" is the ID for the "Bangla" version
                'class_id' => 1, // Assuming "1" is the ID for the "Class One"
                'class_teacher_id' => null, // You can specify the teacher's ID if needed
                'section_name' => $sectionName,
                'max_students' => 30, // Set the maximum number of students
                'section_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $sections[] = [
                'section_hash_id' => Str::random(10),
                'version_id' => 2, // Assuming "2" is the ID for the "Bangla" version
                'class_id' => 2, // Assuming "2" is the ID for the "Class Two"
                'class_teacher_id' => null, // You can specify the teacher's ID if needed
                'section_name' => $sectionName,
                'max_students' => 30, // Set the maximum number of students
                'section_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the sections
        DB::table('sections')->insert($sections);

        // Define subject names
        $subjectNames = ['English', 'Mathematics', 'Science', 'History', 'Geography', 'Physical Education', 'Computer Science'];

        // Seed subjects for both "English" and "Bangla" versions and classes
        $subjects = [];

        foreach ($subjectNames as $subjectName) {
            $subjects[] = [
                'subject_hash_id' => Str::random(10),
                'version_id' => 1, // Assuming "1" is the ID for the "English" version
                'class_id' => 1, // Assuming "1" is the ID for the "Class One"
                'subject_name' => $subjectName,
                'subject_code' => Str::random(6), // Generates a random 6-character code
                'academic_year' => date('Y'), // Set the current year as the academic year
                'subject_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $subjects[] = [
                'subject_hash_id' => Str::random(10),
                'version_id' => 1, // Assuming "1" is the ID for the "English" version
                'class_id' => 2, // Assuming "2" is the ID for the "Class Two"
                'subject_name' => $subjectName,
                'subject_code' => Str::random(6), // Generates a random 6-character code
                'academic_year' => date('Y'), // Set the current year as the academic year
                'subject_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $subjects[] = [
                'subject_hash_id' => Str::random(10),
                'version_id' => 2, // Assuming "2" is the ID for the "Bangla" version
                'class_id' => 1, // Assuming "1" is the ID for the "Class One"
                'subject_name' => $subjectName,
                'subject_code' => Str::random(6), // Generates a random 6-character code
                'academic_year' => date('Y'), // Set the current year as the academic year
                'subject_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $subjects[] = [
                'subject_hash_id' => Str::random(10),
                'version_id' => 2, // Assuming "2" is the ID for the "Bangla" version
                'class_id' => 2, // Assuming "2" is the ID for the "Class Two"
                'subject_name' => $subjectName,
                'subject_code' => Str::random(6), // Generates a random 6-character code
                'academic_year' => date('Y'), // Set the current year as the academic year
                'subject_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the subjects
        DB::table('subjects')->insert($subjects);

        // Define fee frequencies
        $frequencies = [
            [
                'freq_hash_id' => Str::random(10),
                'freq_name' => 'Monthly',
                'no_of_installment' => 12,
                'installment_period' => '1 month',
                'freq_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'freq_hash_id' => Str::random(10),
                'freq_name' => 'Quarterly',
                'no_of_installment' => 4,
                'installment_period' => '3 months',
                'freq_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'freq_hash_id' => Str::random(10),
                'freq_name' => 'Half-Yearly',
                'no_of_installment' => 2,
                'installment_period' => '6 months',
                'freq_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'freq_hash_id' => Str::random(10),
                'freq_name' => 'Yearly',
                'no_of_installment' => 1,
                'installment_period' => null,
                'freq_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'freq_hash_id' => Str::random(10),
                'freq_name' => 'Only Once',
                'no_of_installment' => 1,
                'installment_period' => null,
                'freq_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more fee frequencies as needed
        ];

        // Insert the fee frequencies
        DB::table('fee_frequencies')->insert($frequencies);

        // Define academic fee heads
        $feeHeads = [
            [
                'aca_feehead_hash_id' => Str::random(10),
                'aca_feehead_name' => 'Tuition Fee',
                'aca_feehead_description' => 'Fee for tuition services',
                'aca_feehead_freq' => 1, // Assuming "1" is the ID for the "Monthly" fee frequency
                'no_of_installment' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aca_feehead_hash_id' => Str::random(10),
                'aca_feehead_name' => 'Registration Fee',
                'aca_feehead_description' => 'One-time registration fee',
                'aca_feehead_freq' => 5, // Assuming "5" is the ID for the "Only Once" fee frequency
                'no_of_installment' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aca_feehead_hash_id' => Str::random(10),
                'aca_feehead_name' => 'Laboratory Fee',
                'aca_feehead_description' => 'Fee for laboratory usage',
                'aca_feehead_freq' => 1, // Assuming "1" is the ID for the "Monthly" fee frequency
                'no_of_installment' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aca_feehead_hash_id' => Str::random(10),
                'aca_feehead_name' => 'Library Fee',
                'aca_feehead_description' => 'Fee for library services',
                'aca_feehead_freq' => 2, // Assuming "2" is the ID for the "Quarterly" fee frequency
                'no_of_installment' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aca_feehead_hash_id' => Str::random(10),
                'aca_feehead_name' => 'Examination Fee',
                'aca_feehead_description' => 'Fee for examinations',
                'aca_feehead_freq' => 4, // Assuming "4" is the ID for the "Yearly" fee frequency
                'no_of_installment' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more academic fee heads as needed
        ];

        // Insert the academic fee heads
        DB::table('academic_fee_heads')->insert($feeHeads);

        // Define academic fee groups
        $feeGroups = [
            [
                'aca_group_hash_id' => Str::random(10),
                'aca_group_name' => 'First Semester Fees',
                'aca_feehead_id' => $this->getRandomFeeHeadIds(), // Random comma-separated fee head IDs
                'academic_year' => date('Y'),
                'aca_group_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aca_group_hash_id' => Str::random(10),
                'aca_group_name' => 'Second Semester Fees',
                'aca_feehead_id' => $this->getRandomFeeHeadIds(),
                'academic_year' => date('Y'),
                'aca_group_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more academic fee groups as needed
        ];

        // Insert the academic fee groups
        DB::table('academic_fee_groups')->insert($feeGroups);

        // Add event types as needed
         $eventTypes = [
            ['type_name' => 'Event', 'color' => '#FF5733', 'status' => 1, 'school_id' => 1],
            ['type_name' => 'Workshop', 'color' => '#33FF57', 'status' => 1, 'school_id' => 1],
            ['type_name' => 'Seminar', 'color' => '#5733FF', 'status' => 1, 'school_id' => 1],
            // Add more event types as needed
        ];

        foreach ($eventTypes as $eventType) {
            DB::table('event_types')->insert($eventType);
        }
    }

    // Helper function to generate a random comma-separated list of fee head IDs
    private function getRandomFeeHeadIds()
    {
        $feeHeadIds = [];
        $numFeeHeads = rand(3, 6); // Generate 3 to 6 fee heads for each group

        for ($i = 0; $i < $numFeeHeads; $i++) {
            $feeHeadIds[] = rand(1, 5); // Assuming you have 10 fee heads in your database
        }

        return implode(',', $feeHeadIds);
    }



}
