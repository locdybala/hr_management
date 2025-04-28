<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kpi;

class KpiSeeder extends Seeder
{
    public function run()
    {
        $kpis = [
            [
                'name' => 'Hiệu suất công việc',
                'description' => 'Đánh giá khả năng hoàn thành công việc đúng hạn và đạt chất lượng',
                'unit' => '%',
                'target_value' => 90,
                'weight' => 30,
                'is_active' => true
            ],
            [
                'name' => 'Chất lượng công việc',
                'description' => 'Đánh giá chất lượng sản phẩm/dịch vụ đầu ra',
                'unit' => '%',
                'target_value' => 95,
                'weight' => 25,
                'is_active' => true
            ],
            [
                'name' => 'Tinh thần làm việc nhóm',
                'description' => 'Đánh giá khả năng hợp tác và hỗ trợ đồng nghiệp',
                'unit' => 'điểm',
                'target_value' => 8,
                'weight' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Sáng tạo và cải tiến',
                'description' => 'Đánh giá khả năng đề xuất ý tưởng mới và cải tiến quy trình',
                'unit' => 'điểm',
                'target_value' => 7,
                'weight' => 10,
                'is_active' => true
            ],
            [
                'name' => 'Tuân thủ quy định',
                'description' => 'Đánh giá việc tuân thủ nội quy và quy định công ty',
                'unit' => 'điểm',
                'target_value' => 9,
                'weight' => 10,
                'is_active' => true
            ],
            [
                'name' => 'Đào tạo và phát triển',
                'description' => 'Đánh giá việc tham gia các khóa đào tạo và phát triển kỹ năng',
                'unit' => 'giờ',
                'target_value' => 20,
                'weight' => 10,
                'is_active' => true
            ]
        ];

        foreach ($kpis as $kpi) {
            Kpi::create($kpi);
        }
    }
} 