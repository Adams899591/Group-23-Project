<?php

// FILE: app/Support/SampleStudentData.php  (create the folder app/Support if it does not exist)
// Generates 300 realistic student/application records for the downloadable sample CSV.
// No package needed: the CSV is built with PHP's own fputcsv().

namespace App\Support;

class SampleStudentData
{
    // Column names of the sample file (the analyser understands all of them)
    public const HEADERS = [
        'application_number', 'applicant_name', 'gender', 'state_of_origin', 'faculty', 'programme',
        'academic_session', 'application_date', 'utme_score', 'post_utme_score', 'aggregate_score', 'status',
    ];

    // programme => [faculty, popularity weight]  (the same 15 programmes as in fudma_db)
    private const PROGRAMMES = [
        'B.Sc. Computer Science'         => ['Faculty of Computing', 18],
        'B.Sc. Software Engineering'     => ['Faculty of Computing', 14],
        'B.Sc. Cybersecurity'            => ['Faculty of Computing', 6],
        'B.Eng. Civil Engineering'       => ['Faculty of Engineering', 10],
        'B.Eng. Electrical Engineering'  => ['Faculty of Engineering', 8],
        'B.Eng. Mechanical Engineering'  => ['Faculty of Engineering', 6],
        'B.Sc. Mathematics'              => ['Faculty of Science', 5],
        'B.Sc. Physics'                  => ['Faculty of Science', 4],
        'B.Sc. Chemistry'                => ['Faculty of Science', 4],
        'B.Sc. Accounting'               => ['Faculty of Management Sciences', 10],
        'B.Sc. Business Administration'  => ['Faculty of Management Sciences', 9],
        'B.Sc. Banking and Finance'      => ['Faculty of Management Sciences', 5],
        'B.Sc. Economics'                => ['Faculty of Social Sciences', 7],
        'B.Sc. Political Science'        => ['Faculty of Social Sciences', 4],
        'B.Sc. Sociology'                => ['Faculty of Social Sciences', 3],
    ];

    // session => [how many records, share of applications still pending]
    private const SESSIONS = [
        '2022/2023' => [50, 0.03],
        '2023/2024' => [58, 0.04],
        '2024/2025' => [64, 0.08],
        '2025/2026' => [66, 0.20],
        '2026/2027' => [62, 0.45],
    ];

    private const STATES = [
        'Katsina' => 22, 'Kano' => 14, 'Kaduna' => 10, 'Sokoto' => 6, 'Zamfara' => 6, 'Jigawa' => 5, 'Bauchi' => 5,
        'Niger' => 4, 'Kebbi' => 4, 'FCT' => 4, 'Borno' => 3, 'Gombe' => 3, 'Kwara' => 3, 'Lagos' => 3,
        'Yobe' => 2, 'Plateau' => 2, 'Kogi' => 2, 'Oyo' => 1,
    ];

    private const MALE = ['Abdullahi', 'Usman', 'Musa', 'Ibrahim', 'Sani', 'Yusuf', 'Bashir', 'Suleiman', 'Aliyu', 'Garba',
        'Ahmad', 'Kabir', 'Nasir', 'Salisu', 'Auwal', 'Sadiq', 'Chinedu', 'Emeka', 'Tunde', 'Ayo'];

    private const FEMALE = ['Aisha', 'Fatima', 'Zainab', 'Hauwa', 'Khadija', 'Maryam', 'Halima', 'Hadiza', 'Amina', 'Rabi',
        'Safiya', 'Rukayya', 'Jamila', 'Ngozi', 'Chioma', 'Blessing', 'Grace', 'Bukola', 'Funke', 'Hafsat'];

    private const LAST = ['Ahmed', 'Ibrahim', 'Bello', 'Sani', 'Yusuf', 'Lawal', 'Garba', 'Mohammed', 'Usman', 'Danjuma',
        'Aliyu', 'Umar', 'Idris', 'Tanko', 'Abdullahi', 'Suleiman', 'Musa', 'Salisu', 'Kabir', 'Abubakar',
        'Okafor', 'Adeyemi', 'Balogun', 'Okoro'];

    /** Returns the rows (without the header line). The same 300 records every time. */
    public static function rows(): array
    {
        mt_srand(2026);   // fixed seed -> identical file on every download

        $programmeWeights = array_map(fn ($p) => $p[1], self::PROGRAMMES);
        $rows = [];

        foreach (self::SESSIONS as $session => [$count, $pendingRate]) {
            $startYear = (int) substr($session, 0, 4);

            for ($i = 0; $i < $count; $i++) {
                $gender    = mt_rand(1, 100) <= 52 ? 'Male' : 'Female';
                $first     = $gender === 'Male' ? self::MALE[mt_rand(0, count(self::MALE) - 1)] : self::FEMALE[mt_rand(0, count(self::FEMALE) - 1)];
                $last      = self::LAST[mt_rand(0, count(self::LAST) - 1)];
                $programme = self::weighted($programmeWeights);

                // scores: UTME out of 400, post-UTME out of 100, aggregate = UTME/8 + post-UTME/2 (out of 100)
                $utme      = (int) round(120 + 220 * self::bell());
                $postUtme  = (int) round(25 + 70 * self::bell());
                $aggregate = round($utme / 8 + $postUtme / 2, 2);

                // application date: between 1 Jan and about 31 Aug of the session's start year
                $date = date('Y-m-d', strtotime("{$startYear}-01-01 +" . mt_rand(0, 240) . ' days'));

                // status: some are still pending, the rest are decided by the aggregate score
                if (mt_rand() / mt_getrandmax() < $pendingRate) {
                    $status = 'pending';
                } else {
                    $status = $aggregate >= 58 ? 'admitted' : 'rejected';
                }

                $rows[] = [
                    '',                                   // application_number is filled below
                    "{$first} {$last}",
                    $gender,
                    self::weighted(self::STATES),
                    self::PROGRAMMES[$programme][0],
                    $programme,
                    $session,
                    $date,
                    $utme,
                    $postUtme,
                    number_format($aggregate, 2, '.', ''),
                    $status,
                ];
            }
        }

        // oldest first, then number them APPN0000001, APPN0000002, ...
        usort($rows, fn ($a, $b) => strcmp($a[7], $b[7]));
        foreach ($rows as $n => &$row) {
            $row[0] = 'APPN' . str_pad((string) ($n + 1), 7, '0', STR_PAD_LEFT);
        }
        unset($row);

        mt_srand();   // give PHP back a random seed

        return $rows;
    }

    // random number 0..1 that clusters around the middle (average of 3 dice)
    private static function bell(): float
    {
        return (mt_rand() + mt_rand() + mt_rand()) / (3 * mt_getrandmax());
    }

    // picks a key from ['key' => weight, ...]
    private static function weighted(array $weights): string
    {
        $pick = mt_rand(1, array_sum($weights));

        foreach ($weights as $key => $weight) {
            $pick -= $weight;
            if ($pick <= 0) {
                return (string) $key;
            }
        }

        return (string) array_key_first($weights);
    }
}