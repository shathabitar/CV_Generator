
<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class CvViewModel
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $about,
        public readonly ?string $photoPath,
        public readonly Collection $education,
        public readonly Collection $experience,
        public readonly Collection $references,
        public readonly Collection $certificates,
        public readonly array     $skills = ['technical' => [], 'soft' => []],
        public readonly ?int      $totalYears = null
    ) {}

    public function getPhotoUrl(): ?string
    {
        if (blank($this->photoPath)) {
            return null;
        }

        $path = $this->photoPath;

        // Support both "public/..." and already-prefixed paths
        if (!Str::startsWith($path, 'public/')) {
            $path = 'public/' . $path;
        }

        // determine a path relative to the 'public' disk (strip the "public/" prefix)
        $relativePath = Str::startsWith($path, 'public/') ? Str::after($path, 'public/') : $path;

        return Storage::disk('public')->exists($path)
            ? Storage::url($relativePath)
            : null;
    }

    public static function fromArray(array $payload): self
    {
        $normalizeSkills = function (mixed $skills): Collection {
            if (is_null($skills)) {
                return collect();
            }

            if (is_string($skills)) {
                $skills = preg_split('/\s*,\s*/', $skills, -1, PREG_SPLIT_NO_EMPTY);
            }

            $skills = is_array($skills) ? $skills : [$skills];

            return collect($skills)->map(fn ($s) => trim($s))->filter();
        };

        $education = collect($payload['education'] ?? [])
            ->map(fn (array $e) => [
                'degree' => trim($e['degree'] ?? ''),
                'institution' => trim($e['institution'] ?? ''),
                'year' => $e['year'] ?? null,
            ])
            ->filter(fn (array $e) => $e['degree'] || $e['institution']);

        $experience = collect($payload['experience'] ?? [])
            ->map(fn (array $e) => [
                'position' => trim($e['position'] ?? ''),
                'company' => trim($e['company'] ?? ''),
                'start_date' => $e['start_date'] ?? '',
                'end_date' => isset($e['end_date']) && $e['end_date'] !== '' ? $e['end_date'] : null,
                'description' => trim($e['description'] ?? ''),
            ])
            ->filter(fn (array $e) => $e['position'] || $e['company']);

        $references = collect($payload['reference'] ?? [])
            ->map(fn (array $r) => [
                'name' => trim($r['name'] ?? ''),
                'company' => trim($r['company'] ?? ''),
                'phone_number' => trim($r['phone_number'] ?? ''),
                'email' => trim($r['email'] ?? ''),
            ])
            ->filter(fn (array $r) => $r['name'] || $r['company']);

        $certificates = collect($payload['certificate'] ?? [])
            ->map(fn (array $c) => [
                'title' => trim($c['title'] ?? ''),
                'issuer' => trim($c['issuer'] ?? $c['company'] ?? ''),
                'year' => $c['year'] ?? $c['date'] ?? null,
            ])
            ->filter(fn (array $c) => $c['title']);

        $technical = $normalizeSkills($payload['technical_skills'] ?? $payload['skills'] ?? $payload['technical'] ?? null);
        $soft = $normalizeSkills($payload['soft_skills'] ?? $payload['soft'] ?? null);

        $totalYears = self::calcTotalYears($experience);

        return new self(
            name: trim($payload['name'] ?? ''),
            about: trim($payload['about'] ?? ''),
            photoPath: $payload['photo'] ?? null,
            education: $education,
            experience: $experience,
            references: $references,
            certificates: $certificates,
            skills: ['technical' => $technical, 'soft' => $soft],
            totalYears: $totalYears,
        );
    }

    private static function calcTotalYears(Collection $experience): ?int
    {
        $now = now();
        $totalDays = 0;

        foreach ($experience as $exp) {
            $start = $exp['start_date'] ? strtotime($exp['start_date']) : null;
            $end = ($exp['end_date'] ?? null) ? strtotime($exp['end_date']) : $now->getTimestamp();

            if ($start && $end && $end >= $start) {
                $totalDays += ($end - $start);
            }
        }

        if ($totalDays <= 0) {
            return null;
        }

        return (int) floor($totalDays / 365.25);
    }
}