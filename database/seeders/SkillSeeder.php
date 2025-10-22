<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $technicalSkills = [
            'Python', 'Java', 'C#', 'JavaScript', 'HTML', 'CSS', 'SQL', 'Laravel',
            'Django', 'React', 'Node.js', 'Flutter', 'Git', 'Docker', 'Kubernetes'
        ];

        $softSkills = [
            'Teamwork', 'Communication', 'Problem Solving', 'Leadership',
            'Time Management', 'Adaptability', 'Creativity',
            'Critical Thinking', 'Work Ethic'
        ];

        // Use factory to create each skill
        foreach ($technicalSkills as $skill) {
            Skill::factory()->create([
                'skill_name' => $skill,
                'type' => 'technical',
            ]);
        }

        foreach ($softSkills as $skill) {
            Skill::factory()->create([
                'skill_name' => $skill,
                'type' => 'soft',
            ]);
        }
    }
}
