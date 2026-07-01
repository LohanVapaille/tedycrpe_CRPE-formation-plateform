<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Seeder;

class AboutExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = [
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_titre',
                'value' => 'Mon expérience',
                'ordre' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_1',
                'value' => "Professeur des écoles depuis 2004",
                'ordre' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_2',
                'value' => "Formateur Éducation Nationale (généraliste et spécialiste de l'EPS) depuis 15 ans",
                'ordre' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_3',
                'value' => "Jury au CRPE depuis 10 ans",
                'ordre' => 4,
            ],
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_4',
                'value' => "Concepteur de sujets en EPS depuis 7 ans",
                'ordre' => 5,
            ],
            [
                'page' => 'about',
                'section' => 'experience',
                'key' => 'experience_5',
                'value' => "200 oraux blancs à l'année en partenariat avec un organisme de formation, depuis 7 ans",
                'ordre' => 6,
            ],
        ];

        foreach ($experiences as $experience) {
            Content::updateOrCreate(
                [
                    'page' => $experience['page'],
                    'section' => $experience['section'],
                    'key' => $experience['key'],
                ],
                $experience
            );
        }
    }
}