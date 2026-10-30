<?php

// namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     use WithoutModelEvents;

//     /**
//      * Seed the application's database.
//      */
//     public function run(): void
//     {
//         // User::factory(10)->create();

//         User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);
//     }
// }

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION: CHANGE YOUR RECORD COUNTS HERE
        |--------------------------------------------------------------------------
        */

        $applicantCount = 50; // Change to 5000 for 5,000 applicants.
        $applicationsPerApplicant = 2; // Each applicant gets 1–2 applications.
        $batchSize = 500; // Number of records inserted per batch.

        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN TABLE
        |--------------------------------------------------------------------------
        */

        $adminId = DB::table('admins')->insertGetId([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. ACADEMIC SESSION TABLE
        |--------------------------------------------------------------------------
        */

        $sessionIds = [];

        foreach ([2022, 2023, 2024, 2025, 2026] as $year) {
            $sessionIds[$year] = DB::table('academic_sessions')
                ->insertGetId([
                    'name' => $year . '/' . ($year + 1),
                    'start_year' => $year,
                    'end_year' => $year + 1,
                    'application_start_date' => "{$year}-01-01",
                    'application_end_date' => "{$year}-08-31",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. FACULTY TABLE
        |--------------------------------------------------------------------------
        */

        $facultyNames = [
            'Faculty of Computing',
            'Faculty of Engineering',
            'Faculty of Science',
            'Faculty of Management Sciences',
            'Faculty of Social Sciences',
        ];

        $facultyIds = [];

        foreach ($facultyNames as $index => $name) {
            $facultyIds[] = DB::table('faculties')->insertGetId([
                'name' => $name,
                'code' => 'FAC' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. DEPARTMENT TABLE
        |--------------------------------------------------------------------------
        */

        $departmentNames = [
            ['Computer Science', 'Software Engineering', 'Cybersecurity'],
            ['Civil Engineering', 'Electrical Engineering', 'Mechanical Engineering'],
            ['Mathematics', 'Physics', 'Chemistry'],
            ['Accounting', 'Business Administration', 'Banking and Finance'],
            ['Economics', 'Political Science', 'Sociology'],
        ];

        $departmentIds = [];

        foreach ($departmentNames as $facultyIndex => $departments) {
            foreach ($departments as $index => $name) {
                $departmentIds[] = DB::table('departments')->insertGetId([
                    'faculty_id' => $facultyIds[$facultyIndex],
                    'name' => $name,
                    'code' => 'DEP' . str_pad(
                        count($departmentIds) + 1,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. PROGRAMME TABLE
        |--------------------------------------------------------------------------
        */

        $programmeNames = [
            'B.Sc. Computer Science',
            'B.Sc. Software Engineering',
            'B.Sc. Cybersecurity',
            'B.Eng. Civil Engineering',
            'B.Eng. Electrical Engineering',
            'B.Eng. Mechanical Engineering',
            'B.Sc. Mathematics',
            'B.Sc. Physics',
            'B.Sc. Chemistry',
            'B.Sc. Accounting',
            'B.Sc. Business Administration',
            'B.Sc. Banking and Finance',
            'B.Sc. Economics',
            'B.Sc. Political Science',
            'B.Sc. Sociology',
        ];

        $programmeIds = [];

        foreach ($programmeNames as $index => $name) {
            // Each department receives one programme in this sample dataset.
            $programmeIds[] = DB::table('programmes')->insertGetId([
                'department_id' => $departmentIds[$index],
                'name' => $name,
                'degree_award_type' => 'B.Sc.',
                'duration_years' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. PROGRAMME CAPACITY TABLE
        |--------------------------------------------------------------------------
        |
        | Every programme gets a capacity record for every academic session.
        |
        */

        foreach ($sessionIds as $sessionId) {
            foreach ($programmeIds as $programmeId) {
                DB::table('programme_capacities')->insert([
                    'academic_session_id' => $sessionId,
                    'programme_id' => $programmeId,
                    'approved_quota_capacity' => random_int(50, 200),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. PROGRAMME CUTOFF SCORE TABLE
        |--------------------------------------------------------------------------
        |
        | Each programme has cutoff scores for every academic session.
        |
        */

        foreach ($sessionIds as $sessionId) {
            foreach ($programmeIds as $programmeId) {
                $utmeCutoff = random_int(180, 250);
                $postUtmeCutoff = random_int(40, 70);

                DB::table('programme_cutoff_scores')->insert([
                    'academic_session_id' => $sessionId,
                    'programme_id' => $programmeId,
                    'utme_cutoff_score' => $utmeCutoff,
                    'post_utme_cutoff_score' => $postUtmeCutoff,
                    'aggregate_cutoff_score' => round(
                        ($utmeCutoff / 400 * 50) +
                        ($postUtmeCutoff / 100 * 50),
                        2
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. APPLICANT TABLE
        |--------------------------------------------------------------------------
        |
        | CHANGE $applicantCount ABOVE TO CONTROL THE NUMBER OF APPLICANTS.
        |
        */

        $firstNames = [
            'Usman', 'Aisha', 'Ibrahim', 'Fatima', 'Musa',
            'Zainab', 'Abdullahi', 'Maryam', 'Hassan', 'Khadija',
            'Sani', 'Amina', 'Yusuf', 'Hauwa', 'Adam',
            'Grace', 'Samuel', 'David', 'Esther', 'Daniel',
        ];

        $lastNames = [
            'Adams', 'Musa', 'Abdullahi', 'Bello', 'Ibrahim',
            'Yusuf', 'Mohammed', 'Okafor', 'Okonkwo', 'Adeyemi',
            'Balogun', 'Usman', 'Garba', 'Eze', 'Okoro',
            'James', 'Peter', 'Suleiman', 'Lawal', 'Olawale',
        ];

        $middleNames = [
            'Aliyu', 'Abubakar', 'Omar', 'Samuel', 'David',
            'Emeka', 'Chinedu', 'Ifeanyi', 'Tunde', 'Bashir',
        ];

        $states = [
            'Abuja', 'Kano', 'Kaduna', 'Katsina', 'Lagos',
            'Oyo', 'Rivers', 'Plateau', 'Bauchi', 'Borno',
            'Gombe', 'Sokoto', 'Zamfara', 'Kogi', 'Benue',
            'Enugu', 'Anambra', 'Delta', 'Edo', 'Ogun',
            'Niger', 'Kwara', 'Adamawa', 'Taraba', 'Yobe',
            'Jigawa', 'Kebbi', 'Osun', 'Ondo', 'Ekiti',
            'Cross River', 'Akwa Ibom', 'Bayelsa', 'Ebonyi',
            'Imo', 'Abia', 'Nasarawa', 'Kebbi', 'Kano',
        ];

        $applicantRecords = [];

        for ($i = 1; $i <= $applicantCount; $i++) {
            $applicantRecords[] = [
                'applicant_number' => 'APP' . str_pad(
                    $i,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
                'first_name' => $firstNames[array_rand($firstNames)],
                'middle_name' => random_int(0, 1)
                    ? $middleNames[array_rand($middleNames)]
                    : null,
                'last_name' => $lastNames[array_rand($lastNames)],
                'gender' => ['Male', 'Female'][random_int(0, 1)],
                'state_of_origin' => $states[array_rand($states)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert applicants in batches to reduce database overhead.
        foreach (array_chunk($applicantRecords, $batchSize) as $batch) {
            DB::table('applicants')->insert($batch);
        }

        /*
        |--------------------------------------------------------------------------
        | 9. APPLICATION TABLE
        |--------------------------------------------------------------------------
        |
        | Each applicant receives one or more applications.
        | Applications reference existing applicants, sessions and programmes.
        |
        */

        $applicants = DB::table('applicants')
            ->whereIn(
                'applicant_number',
                array_column($applicantRecords, 'applicant_number')
            )
            ->orderBy('id')
            ->get(['id', 'applicant_number']);

        $applicationRecords = [];
        $applicationNumber = 1;

        foreach ($applicants as $applicant) {
            $numberOfApplications = random_int(
                1,
                max(1, $applicationsPerApplicant)
            );

            for ($j = 0; $j < $numberOfApplications; $j++) {
                $year = array_rand($sessionIds);
                $sessionId = $sessionIds[$year];
                $programmeId = $programmeIds[
                    array_rand($programmeIds)
                ];

                $status = [
                    'admitted',
                    'rejected',
                    'pending',
                ][random_int(0, 2)];

                $utmeScore = random_int(100, 400);
                $postUtmeScore = random_int(20, 100);

                $aggregateScore = round(
                    ($utmeScore / 400 * 50) +
                    ($postUtmeScore / 100 * 50),
                    2
                );

                $applicationRecords[] = [
                    'applicant_id' => $applicant->id,
                    'academic_session_id' => $sessionId,
                    'programme_id' => $programmeId,

                    'application_number' => 'APPN' . str_pad(
                        $applicationNumber++,
                        8,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'application_date' => sprintf(
                        '%d-%02d-%02d',
                        $year,
                        random_int(1, 8),
                        random_int(1, 28)
                    ),

                    'utme_score' => $utmeScore,
                    'post_utme_score' => $postUtmeScore,
                    'aggregate_score' => $aggregateScore,
                    'application_status' => $status,

                    'admission_date' => $status === 'admitted'
                        ? sprintf(
                            '%d-%02d-%02d',
                            $year,
                            random_int(9, 12),
                            random_int(1, 28)
                        )
                        : null,

                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($applicationRecords, $batchSize) as $batch) {
            DB::table('applications')->insert($batch);
        }

        /*
        |--------------------------------------------------------------------------
        | 10. SAVED DASHBOARD FILTER TABLE
        |--------------------------------------------------------------------------
        |
        | One sample filter for the administrator.
        |
        */

        DB::table('saved_dashboard_filters')->insert([
            'admin_id' => $adminId,
            'filter_name' => 'Current Academic Session',
            'saved_filter_settings' => json_encode([
                'academic_session_id' => end($sessionIds),
                'faculty_id' => null,
                'department_id' => null,
                'programme_id' => null,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}