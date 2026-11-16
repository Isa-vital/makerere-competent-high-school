<?php

namespace Database\Seeders;

use App\Models\AcademicCombination;
use App\Models\AcademicSubject;
use Illuminate\Database\Seeder;

/** Review-only curriculum imported from the older, unverified ContentSeeder. */
class LegacyCurriculumDraftSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['Mathematics', 'MATH', 'core'],
            ['English Language', 'ENG', 'core'],
            ['Physics', 'PHY', 'sciences'],
            ['Chemistry', 'CHM', 'sciences'],
            ['Biology', 'BIO', 'sciences'],
            ['Agriculture', 'AGR', 'sciences'],
            ['History', 'HIS', 'humanities'],
            ['Geography', 'GEO', 'humanities'],
            ['Religious Education', 'RE', 'humanities'],
            ['Entrepreneurship', 'ENT', 'commercial'],
            ['Runyoro', 'RUN', 'humanities'],
            ['Kiswahili', 'KIS', 'humanities'],
            ['ICT / Computer Studies', 'ICT', 'core'],
            ['Fine Art', 'ART', 'elective'],
            ['Physical Education', 'PE', 'elective'],
            ['Technical Drawing', 'TD', 'elective'],
            ['Luganda', 'LUG', 'elective'],
            ['Home Economics', 'HEC', 'elective'],
            ['Music', 'MUS', 'elective'],
        ];

        foreach ($subjects as $index => [$name, $code, $category]) {
            AcademicSubject::firstOrCreate(['name' => $name, 'level' => 'O-Level'], [
                'code' => $code,
                'category' => $category,
                'sort_order' => $index + 1,
                'status' => 'inactive',
            ]);
        }

        $combinations = [
            ['PCM', 'Physics, Chemistry, Mathematics', 'sciences'],
            ['PEM', 'Physics, Economics, Mathematics', 'sciences'],
            ['PCB', 'Physics, Chemistry, Biology', 'sciences'],
            ['BCM', 'Biology, Chemistry, Mathematics', 'sciences'],
            ['BAG', 'Biology, Agriculture, Geography', 'sciences'],
            ['HEG', 'History, Economics, Geography', 'arts'],
            ['HLD', 'History, Literature, Divinity', 'arts'],
            ['LEK', 'Literature, Economics, Kinyarwanda', 'arts'],
            ['MEG', 'Mathematics, Economics, Geography', 'commercial'],
            ['MEA', 'Math, Economics, Agriculture', 'commercial'],
            ['GEA', 'Geography, Economics, Agriculture/Art', 'commercial'],
        ];

        foreach ($combinations as $index => [$code, $subjects, $faculty]) {
            AcademicCombination::firstOrCreate(['code' => $code], [
                'subjects' => $subjects,
                'faculty' => $faculty,
                'sort_order' => $index + 1,
                'status' => 'inactive',
            ]);
        }
    }
}
