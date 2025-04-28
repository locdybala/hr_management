<?php

return [
    'required' => 'Trường :attribute là bắt buộc.',
    'numeric' => 'Trường :attribute phải là số.',
    'min' => [
        'numeric' => 'Trường :attribute không được nhỏ hơn :min.',
    ],
    'max' => [
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
    ],
    'exists' => 'Trường :attribute không tồn tại trong hệ thống.',
    'in' => 'Trường :attribute không hợp lệ.',
    'string' => 'Trường :attribute phải là chuỗi ký tự.',
    
    'attributes' => [
        'employee_id' => 'nhân viên',
        'year' => 'năm',
        'quarter' => 'quý',
        'overall_score' => 'điểm tổng',
        'strengths' => 'điểm mạnh',
        'weaknesses' => 'điểm yếu',
        'improvements' => 'đề xuất cải thiện',
        'kpi_results' => 'kết quả KPI',
        'kpi_results.*.kpi_id' => 'KPI',
        'kpi_results.*.target_value' => 'giá trị mục tiêu',
        'kpi_results.*.actual_value' => 'giá trị thực tế',
        'kpi_results.*.score' => 'điểm số',
        'kpi_results.*.comment' => 'nhận xét',
    ],
]; 