<?php

namespace App\Services;

use App\Models\User;
use App\ViewModels\CvViewModel;
use Illuminate\Support\Facades\Cache;

class CvService
{
    public function skillsFromInput(?array $selected, ?array $custom): array
    {
        $normalized = [];

        if (is_array($selected)) {
            foreach ($selected as $s) {
                $s = trim((string) $s);
                if ($s !== '') {
                    $normalized[] = $s;
                }
            }
        }

        if (is_array($custom)) {
            foreach ($custom as $c) {
                $c = trim((string) $c);
                if ($c !== '') {
                    $normalized[] = $c;
                }
            }
        }

        // Deduplicate case-insensitive, preserve first occurrence case.
        $seen = [];
        $result = [];
        foreach ($normalized as $item) {
            $key = mb_strtolower($item);
            if (!array_key_exists($key, $seen)) {
                $seen[$key] = true;
                $result[] = $item;
            }
        }

        return $result;
    }

    public function composeCvData(array $validated, \Illuminate\Http\UploadedFile|null $photo): array
    {
        $data = [
            'name'  => $validated['name'],
            'about' => $validated['about'],
            'technical_skills' => $this->skillsFromInput(
                $validated['technical_skills'] ?? [],
                $validated['custom_technical_skills'] ?? []
            ),
            'soft_skills' => $this->skillsFromInput(
                $validated['soft_skills'] ?? [],
                $validated['custom_soft_skills'] ?? []
            ),
            'education' => $this->coerceList($validated['education'] ?? []),
            'experience' => $this->coerceList($validated['experience'] ?? []),
            'certificate' => $this->coerceList($validated['certificate'] ?? []),
            'reference' => $this->coerceList($validated['reference'] ?? []),
        ];

        if ($photo && $photo->isValid()) {
            $data['photo'] = $photo->store('photos', 'public');
        }

        return $data;
    }

    /**
     * Get user CV data with caching and optimized queries
     */
    public function getUserCvData(int $userId): array
    {
        return Cache::remember("user_cv_data_{$userId}", 3600, function () use ($userId) {
            $user = User::with([
                'educations',
                'experiences',
                'skills',
                'references',
                'certificates'
            ])->findOrFail($userId);

            return $this->transformUserToCvData($user);
        });
    }

    /**
     * Transform User model to CV data array (centralized logic)
     */
    public function transformUserToCvData(User $user): array
    {
        return [
            'name' => $user->name,
            'photo' => $user->photo,
            'about' => $user->about,
            'education' => $user->educations->map(fn($edu) => [
                'degree' => $edu->degree,
                'institution' => $edu->institution,
                'year' => $edu->year,
            ])->toArray(),
            'experience' => $user->experiences->map(fn($exp) => [
                'position' => $exp->position,
                'company' => $exp->company,
                'start_date' => $exp->start_date,
                'end_date' => $exp->end_date,
                'description' => $exp->description,
            ])->toArray(),
            'technical_skills' => $user->skills->where('type', 'technical')->pluck('skill_name')->toArray(),
            'soft_skills' => $user->skills->where('type', 'soft')->pluck('skill_name')->toArray(),
            'reference' => $user->references->map(fn($ref) => [
                'name' => $ref->name,
                'company' => $ref->company,
                'phone_number' => $ref->phone_number,
                'email' => $ref->email,
            ])->toArray(),
            'certificate' => $user->certificates->map(fn($cert) => [
                'title' => $cert->title,
                'company' => $cert->company,
                'date' => $cert->date,
            ])->toArray(),
        ];
    }

    /**
     * Clear user CV cache
     */
    public function clearUserCvCache(int $userId): void
    {
        Cache::forget("user_cv_data_{$userId}");
    }

    protected function coerceList(array $list): array
    {
        // Remove empty rows and trim strings
        $clean = [];
        foreach ($list as $row) {
            $row = array_map(fn($v) => is_string($v) ? trim($v) : $v, $row);
            $hasValue = false;
            foreach ($row as $v) {
                if ($v !== null && $v !== '') {
                    $hasValue = true;
                    break;
                }
            }
            if ($hasValue) {
                $clean[] = $row;
            }
        }
        return $clean;
    }
}