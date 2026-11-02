<?php

// FILE: app/Support/CsvAdmissionAnalyzer.php
// Reads a .csv file with PHP's own fgetcsv() (no package) and turns it into the numbers used by the charts.
// It understands common column names (see ALIASES). Columns that are missing are simply skipped.

namespace App\Support;

class CsvAdmissionAnalyzer
{
    // field => accepted header names (after lower-casing and replacing spaces with "_")
    private const ALIASES = [
        'number'    => ['application_number', 'applicant_number', 'reg_no', 'regno', 'id'],
        'name'      => ['applicant_name', 'full_name', 'name', 'student_name'],
        'status'    => ['status', 'application_status', 'admission_status'],
        'session'   => ['academic_session', 'session', 'admission_year', 'year'],
        'faculty'   => ['faculty'],
        'programme' => ['programme', 'program', 'course'],
        'gender'    => ['gender', 'sex'],
        'state'     => ['state_of_origin', 'state'],
        'date'      => ['application_date', 'date', 'created_at'],
        'utme'      => ['utme_score', 'utme'],
        'postUtme'  => ['post_utme_score', 'post_utme'],
        'aggregate' => ['aggregate_score', 'aggregate'],
    ];

    // Nice names used in the "columns not found" message
    private const LABELS = [
        'status' => 'status', 'session' => 'academic_session', 'faculty' => 'faculty', 'programme' => 'programme',
        'gender' => 'gender', 'state' => 'state_of_origin', 'date' => 'application_date', 'aggregate' => 'aggregate_score',
    ];

    private const MAX_ROWS = 200000;

    /**
     * @throws \RuntimeException with a message that can be shown to the admin
     */
    public function analyse(string $path, string $fileName): array
    {
        $handle = @fopen($path, 'r');
        if (! $handle) {
            throw new \RuntimeException('The file could not be opened.');
        }

        // ---- 1) Header line: remove BOM, detect the separator, find which column is which ----
        $firstLine = fgets($handle);
        if ($firstLine === false || trim($firstLine) === '') {
            fclose($handle);
            throw new \RuntimeException('The file is empty.');
        }
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        $delimiter = $this->detectDelimiter($firstLine);
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter, '"', '\\');
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $headerClean = array_map(fn ($h) => $this->clean($h), $header);
        $normalised  = array_map(fn ($h) => trim(strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($h))), '_'), $headerClean);

        $map = [];
        foreach (self::ALIASES as $field => $names) {
            foreach ($names as $alias) {
                $index = array_search($alias, $normalised, true);
                if ($index !== false) {
                    $map[$field] = $index;
                    break;
                }
            }
        }

        if (! array_intersect(array_keys($map), ['status', 'session', 'faculty', 'programme', 'gender', 'state', 'date', 'aggregate', 'utme', 'postUtme'])) {
            fclose($handle);
            throw new \RuntimeException('No known columns were found. Use headers such as status, academic_session, faculty, programme, gender, application_date and aggregate_score. Download the sample file to see the format.');
        }

        // ---- 2) Read every row and count ----
        $rows = 0;
        $statusCounts = $genderCounts = $monthCounts = [];
        $counters = ['faculty' => [], 'programme' => [], 'state' => []];
        $sessionStats = [];
        $aggregates   = [];
        $utmeSum = 0.0; $utmeN = 0;
        $preview = [];

        while (($row = fgetcsv($handle, 0, $delimiter, '"', '\\')) !== false) {
            if ($row === [null] || count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;   // blank line
            }
            if (++$rows > self::MAX_ROWS) {
                break;
            }

            if (count($preview) < 10) {
                $preview[] = array_map(fn ($v) => $this->clean($v), array_pad($row, count($header), ''));
            }

            $status = $this->status($this->get($row, $map, 'status'));
            if (isset($map['status'])) {
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            }

            if (isset($map['session']) && ($v = $this->get($row, $map, 'session')) !== '') {
                $sessionStats[$v]['apps']     = ($sessionStats[$v]['apps'] ?? 0) + 1;
                $sessionStats[$v]['admitted'] = ($sessionStats[$v]['admitted'] ?? 0) + ($status === 'admitted' ? 1 : 0);
            }
            if (isset($map['gender']) && ($v = $this->get($row, $map, 'gender')) !== '') {
                $g = $this->gender($v);
                $genderCounts[$g] = ($genderCounts[$g] ?? 0) + 1;
            }
            foreach (array_keys($counters) as $field) {
                if (isset($map[$field]) && ($v = $this->get($row, $map, $field)) !== '') {
                    $counters[$field][$v] = ($counters[$field][$v] ?? 0) + 1;
                }
            }

            if (isset($map['date']) && ($v = $this->get($row, $map, 'date')) !== '') {
                $time = strtotime($v);
                if ($time !== false) {
                    $key = date('Y-m', $time);
                    $monthCounts[$key] = ($monthCounts[$key] ?? 0) + 1;
                }
            }
            if (isset($map['aggregate']) && is_numeric($v = $this->get($row, $map, 'aggregate'))) {
                $aggregates[] = (float) $v;
            }
            if (isset($map['utme']) && is_numeric($v = $this->get($row, $map, 'utme'))) {
                $utmeSum += (float) $v;
                $utmeN++;
            }
        }
        fclose($handle);

        if ($rows === 0) {
            throw new \RuntimeException('The file has a header line but no data rows.');
        }

        // ---- 3) Turn the counts into chart-ready arrays ----
        [$facultyCounts, $programmeCounts, $stateCounts] = [$counters['faculty'], $counters['programme'], $counters['state']];
        $admitted = $statusCounts['admitted'] ?? 0;
        $pending  = $statusCounts['pending'] ?? 0;
        $rejected = $statusCounts['rejected'] ?? 0;

        $report = [
            'fileName' => $fileName,
            'rows'     => $rows,
            'columns'  => array_values(array_filter($headerClean, fn ($h) => $h !== '')),
            'missing'  => array_values(array_map(fn ($f) => self::LABELS[$f], array_diff(array_keys(self::LABELS), array_keys($map)))),
            'kpis'     => [
                'total'       => $rows,
                'admitted'    => $admitted,
                'pending'     => $pending,
                'rejected'    => $rejected,
                'hasStatus'   => isset($map['status']),
                'admitRate'   => isset($map['status']) && $rows ? round($admitted / $rows * 100, 1) : null,
                'avgAggregate'=> $aggregates ? round(array_sum($aggregates) / count($aggregates), 1) : null,
                'avgUtme'     => $utmeN ? round($utmeSum / $utmeN, 1) : null,
            ],
            'byStatus'    => $statusCounts ? $this->pairs($statusCounts) : null,
            'bySession'   => null,
            'byGender'    => $genderCounts ? $this->pairs($genderCounts) : null,
            'byFaculty'   => $facultyCounts ? $this->top($facultyCounts, 8) : null,
            'byProgramme' => $programmeCounts ? $this->top($programmeCounts, 8) : null,
            'byState'     => $stateCounts ? $this->top($stateCounts, 8) : null,
            'byMonth'     => null,
            'scoreBuckets'=> $aggregates ? $this->buckets($aggregates) : null,
            'preview'     => ['headers' => $headerClean, 'rows' => $preview],
        ];

        if ($sessionStats) {
            ksort($sessionStats);
            $report['bySession'] = [
                'labels'       => array_map('strval', array_keys($sessionStats)),
                'applications' => array_values(array_column($sessionStats, 'apps')),
                'admitted'     => array_values(array_column($sessionStats, 'admitted')),
            ];
        }
        if ($monthCounts) {
            ksort($monthCounts);
            $report['byMonth'] = [
                'labels' => array_map(fn ($k) => date('M Y', strtotime($k . '-01')), array_keys($monthCounts)),
                'values' => array_values($monthCounts),
            ];
        }

        $report['insights'] = $this->insights($report);

        return $report;
    }

    // ---------------------------------------------------------------- helpers

    private function detectDelimiter(string $line): string
    {
        $best = ','; $bestCount = 0;
        foreach ([',', ';', "\t", '|'] as $d) {
            $count = substr_count($line, $d);
            if ($count > $bestCount) { $best = $d; $bestCount = $count; }
        }

        return $best;
    }

    // value of a column in a row (cleaned UTF-8 text), or '' when the column does not exist
    private function get(array $row, array $map, string $field): string
    {
        return isset($map[$field]) ? $this->clean($row[$map[$field]] ?? '') : '';
    }

    // trims and makes sure the text is valid UTF-8 (Excel often saves as Windows-1252)
    private function clean($value): string
    {
        $value = trim((string) $value);

        return mb_check_encoding($value, 'UTF-8') ? $value : mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }

    private function status(string $value): string
    {
        return match (strtolower($value)) {
            'admitted', 'admit', 'accepted', 'admission granted'   => 'admitted',
            'pending', 'processing', 'in review', 'under review'   => 'pending',
            'rejected', 'denied', 'declined', 'not admitted'       => 'rejected',
            default                                                => 'other',
        };
    }

    private function gender(string $value): string
    {
        return match (strtolower($value[0] ?? '')) {
            'm' => 'Male',
            'f' => 'Female',
            default => 'Other',
        };
    }

    // ['a' => 3, 'b' => 1] -> ['labels' => ['a','b'], 'values' => [3,1]]
    private function pairs(array $counts): array
    {
        return ['labels' => array_map('strval', array_keys($counts)), 'values' => array_values($counts)];
    }

    private function top(array $counts, int $limit): array
    {
        arsort($counts);

        return $this->pairs(array_slice($counts, 0, $limit, true));
    }

    // groups the aggregate scores into 8 equal ranges for the histogram
    private function buckets(array $values): array
    {
        $min   = (int) floor(min($values));
        $max   = (int) ceil(max($values));
        $width = max(1, (int) ceil(($max - $min + 1) / 8));

        $labels = $counts = [];
        for ($start = $min; $start <= $max; $start += $width) {
            $labels[] = $start . '-' . ($start + $width - 1);
            $counts[] = 0;
        }
        foreach ($values as $v) {
            $index = min(count($counts) - 1, (int) floor(($v - $min) / $width));
            $counts[$index]++;
        }

        return ['labels' => $labels, 'values' => $counts];
    }

    // short sentences shown under the summary cards
    private function insights(array $r): array
    {
        $out = [];
        $k   = $r['kpis'];

        if ($k['admitRate'] !== null) {
            $out[] = "Admission rate is {$k['admitRate']}% ({$k['admitted']} of " . number_format($k['total']) . ' applications).';
        }
        if ($r['bySession'] && count($r['bySession']['labels']) > 1) {
            $apps = $r['bySession']['applications'];
            $i    = array_search(max($apps), $apps, true);
            $out[] = "Busiest session: {$r['bySession']['labels'][$i]} with " . number_format($apps[$i]) . ' applications.';
        }
        if ($r['byProgramme']) {
            $out[] = "Most applied-for programme: {$r['byProgramme']['labels'][0]} ({$r['byProgramme']['values'][0]} applications).";
        }
        if ($r['byFaculty']) {
            $out[] = "Largest faculty: {$r['byFaculty']['labels'][0]} ({$r['byFaculty']['values'][0]} applications).";
        }
        if ($r['byMonth'] && count($r['byMonth']['labels']) > 1) {
            $i = array_search(max($r['byMonth']['values']), $r['byMonth']['values'], true);
            $out[] = "Peak month: {$r['byMonth']['labels'][$i]} ({$r['byMonth']['values'][$i]} applications).";
        }
        if ($r['byState']) {
            $out[] = "Top state of origin: {$r['byState']['labels'][0]} ({$r['byState']['values'][0]} applicants).";
        }

        return $out;
    }
}