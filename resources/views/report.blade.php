<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานตารางเวลาว่าง</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #006400;      /* Dark Green - สีหลัก */
            --primary-light: #228B22; /* Forest Green - สีหลักอ่อน */
            --primary-dark: #004d00;  /* Darker Green - สีหลักเข้ม */
            --success: #32CD32;      /* Lime Green - แสดงสถานะว่าง */
            --success-light: #F0FFF0; /* Honeydew - พื้นหลังสถานะว่าง */
            --danger: #FF4444;       /* สีแดง - แสดงสถานะไม่ว่าง */
            --danger-light: #FFEBEE;  /* Red 50 - พื้นหลังสถานะไม่ว่าง */
            --bg: #E8F5E9;           /* Green 50 - พื้นหลัง */
            --card: #ffffff;         /* สีขาว - พื้นหลังการ์ด */
            --text: #003300;         /* Very Dark Green - สีข้อความ */
            --text-light: #006400;    /* Dark Green - สีข้อความอ่อน */
            --border: #90EE90;       /* Light Green - สีขอบ */
            --gradient-start: #004d00; /* Darker Green */
            --gradient-end: #006400;   /* Dark Green */
        }

        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, var(--bg) 0%, #C8E6C9 100%);
            padding: 20px;
            margin: 0;
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        .report-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px;
            background: var(--card);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 100, 0, 0.1),
                       0 8px 10px -6px rgba(0, 100, 0, 0.1);
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 100, 0, 0.2);
        }

        h2 {
            color: var(--text);
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            border-radius: 2px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .summary-card {
            background: var(--success-light);
            padding: 25px;
            border-radius: 16px;
            border: 2px solid var(--primary);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
        }

        .summary-card:hover {
            transform: translateY(-5px) rotate(1deg);
            box-shadow: 0 10px 20px rgba(0, 100, 0, 0.15);
        }

        .summary-label {
            color: var(--danger);
            font-size: 1rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(255, 68, 68, 0.1);
        }

        .summary-label svg {
            stroke: var(--danger);
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-align: center;
        }

        .schedule-details {
            background: var(--card);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px rgba(0, 100, 0, 0.1);
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-table th {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: white;
            font-weight: 600;
            padding: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .schedule-table td {
            padding: 20px;
            border-bottom: 1px solid var(--primary-light);
            vertical-align: middle;
            text-align: center;
        }

        .schedule-table tr:nth-child(even) {
            background: var(--success-light);
        }

        .schedule-table tr:hover {
            background-color: rgba(144, 238, 144, 0.5);
        }

        .schedule-table tr:hover td {
            background-color: transparent;
            color: var(--primary-dark);
        }

        .schedule-table tr {
            transition: all 0.3s ease;
        }

        .schedule-table tr:hover .time-slot {
            border-color: var(--primary-dark);
            color: var(--primary-dark);
        }

        .schedule-table tr:hover .hours-badge {
            box-shadow: 0 2px 4px rgba(0, 100, 0, 0.3);
        }

        .time-slot {
            background: var(--success-light);
            border: 1px solid var(--success);
            color: var(--primary-dark);
            font-weight: 500;
            margin: 6px 4px;
            padding: 8px 16px;
            border-radius: 999px;
            display: inline-block;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            background: var(--success);
            color: white;
            transform: scale(1.1);
        }

        .no-slot {
            background: var(--danger-light);
            border: 1px solid var(--danger);
            color: var(--danger);
            padding: 8px 16px;
            border-radius: 999px;
            display: inline-block;
        }

        .hours-badge {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 4px rgba(0, 100, 0, 0.2);
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 50%;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 100, 0, 0.2);
            transition: all 0.3s ease;
        }

        .back-button svg {
            width: 24px;
            height: 24px;
            stroke-width: 2.5px;
        }

        .back-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 100, 0, 0.3);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: var(--text-light);
            font-size: 0.9rem;
            background: var(--success-light);
            border-radius: 12px;
            border: 1px solid var(--primary-light);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .summary-card {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .summary-card:nth-child(1) { animation-delay: 0.1s; }
        .summary-card:nth-child(2) { animation-delay: 0.2s; }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .hours-badge {
            animation: pulse 2s infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }

        .back-button {
            animation: float 3s ease-in-out infinite;
        }

        @media (max-width: 768px) {
            .report-container {
                padding: 20px;
                margin: 10px;
            }
            
            .schedule-table th {
                font-size: 1rem;
                padding: 15px;
            }
            
            .schedule-table td {
                padding: 15px;
            }
            
            .summary-value {
                font-size: 1.6rem;
            }

            .back-button {
                position: static;
                margin-bottom: 20px;
            }

            h2 {
                font-size: 1.8rem;
                margin-top: 20px;
            }
        }

        .hours-badge.low-hours {
            background: linear-gradient(45deg, var(--danger), #ff6b6b);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0); }
        }

        .hours-badge.zero-hours {
            background: linear-gradient(45deg, var(--danger), #ff6b6b);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0); }
        }

        .day-name {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .day-name.no-available {
            color: var(--danger);
            font-weight: 600;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 30px auto;
            padding: 20px;
            max-width: 1200px;
        }

        .chart-wrapper {
            background: var(--card);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 100, 0, 0.08);
            border: 1px solid var(--border);
            min-height: 350px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
                padding: 15px;
                gap: 20px;
            }

            .chart-wrapper {
                padding: 15px;
                min-height: 300px;
            }

            canvas {
                max-height: 300px !important;
            }
        }

        @media (max-width: 480px) {
            .chart-wrapper {
                min-height: 250px;
                padding: 10px;
            }

            canvas {
                max-height: 250px !important;
            }
        }

        .export-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 100, 0, 0.2);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            text-align: right;
        }

        .export-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 100, 0, 0.3);
        }

        .available-slot {
            background-color: #e8f5e9;
            padding: 5px 10px;
            margin: 2px 0;
            border-radius: 4px;
            display: inline-block;
            margin-right: 10px;
        }
        
        .hours-badge {
            background-color: #4caf50;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }
        
        .schedule-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .schedule-table th,
        .schedule-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .schedule-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .stat-card p {
            margin: 0;
            font-size: 1.5em;
            color: #4caf50;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var chartData = {
            totalAvailable: Number('{{ $totalAvailableSlots }}'),
            availabilityData: JSON.parse('{!! addslashes(json_encode($availabilityData)) !!}')
        };

        document.addEventListener('DOMContentLoaded', function() {
            const totalAvailableHours = chartData.totalAvailable;
            const availabilityData = chartData.availabilityData;
            
            // ข้อมูลสำหรับ Pie Chart
            const totalPossibleHours = 7 * 10;
            const totalUnavailableHours = totalPossibleHours - totalAvailableHours;

            // ฟังก์ชันปรับขนาด Charts
            function resizeCharts() {
                const container = document.querySelector('.charts-container');
                const wrappers = document.querySelectorAll('.chart-wrapper');
                
                wrappers.forEach(wrapper => {
                    const canvas = wrapper.querySelector('canvas');
                    if (canvas) {
                        if (window.innerWidth <= 480) {
                            canvas.style.height = '250px';
                        } else if (window.innerWidth <= 768) {
                            canvas.style.height = '300px';
                        } else {
                            canvas.style.height = '350px';
                        }
                    }
                });
            }

            // เรียกใช้ฟังก์ชันเมื่อโหลดหน้าและปรับขนาดหน้าจอ
            window.addEventListener('load', resizeCharts);
            window.addEventListener('resize', resizeCharts);

            // ตั้งค่า Charts ให้ responsive
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                // ... (options อื่นๆ คงเดิม)
            };

            // สร้าง Pie Chart
            const pieCtx = document.getElementById('availabilityPie').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['ชั่วโมงที่ว่าง', 'ชั่วโมงที่ไม่ว่าง'],
                    datasets: [{
                        data: [totalAvailableHours, totalUnavailableHours],
                        backgroundColor: [
                            'rgba(50, 205, 50, 0.9)',
                            'rgba(255, 0, 0, 0.9)'
                        ],
                        borderColor: [
                            'rgba(50, 205, 50, 1)',
                            'rgba(255, 0, 0, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 20,
                        hoverBorderWidth: 3,
                        borderRadius: 8,
                        spacing: 3,
                        rotation: -45
                    }]
                },
                options: {
                    ...chartOptions,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Prompt', size: 14, weight: '500' },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'rectRounded',
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => ({
                                        text: `${label} (${Math.round((data.datasets[0].data[i] / total) * 100)}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: 2,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'สัดส่วนชั่วโมงว่าง/ไม่ว่าง',
                            font: { family: 'Prompt', size: 18, weight: 'bold' },
                            padding: { bottom: 25 },
                            color: '#8B0000'
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#8B0000',
                            titleFont: { family: 'Prompt', size: 15, weight: 'bold' },
                            bodyColor: '#666',
                            bodyFont: { family: 'Prompt', size: 14 },
                            borderColor: '#8B0000',
                            borderWidth: 1,
                            padding: 15,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} ชั่วโมง (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    },
                    layout: {
                        padding: {
                            top: window.innerWidth > 768 ? 20 : 10,
                            bottom: window.innerWidth > 768 ? 20 : 10,
                            left: window.innerWidth > 768 ? 20 : 10,
                            right: window.innerWidth > 768 ? 20 : 10
                        }
                    },
                    elements: {
                        arc: {
                            borderWidth: 2,
                            borderJoinStyle: 'round'
                        }
                    }
                }
            });

            // ข้อมูลสำหรับ Bar Chart
            const days = Object.keys(availabilityData);
            const availableHours = days.map(day => availabilityData[day].total_hours);
            const unavailableHours = days.map(day => 10 - availabilityData[day].total_hours);

            // สร้าง Bar Chart
            const barCtx = document.getElementById('dailyHoursBar').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [
                        {
                            label: 'ชั่วโมงที่ว่าง',
                            data: availableHours,
                            backgroundColor: 'rgba(50, 205, 50, 0.85)',
                            borderColor: 'rgba(50, 205, 50, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(50, 205, 50, 1)',
                            hoverBorderColor: 'rgba(50, 205, 50, 1)',
                            hoverBorderWidth: 2
                        },
                        {
                            label: 'ชั่วโมงที่ไม่ว่าง',
                            data: unavailableHours,
                            backgroundColor: 'rgba(255, 0, 0, 0.85)',
                            borderColor: 'rgba(255, 0, 0, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(255, 0, 0, 1)',
                            hoverBorderColor: 'rgba(255, 0, 0, 1)',
                            hoverBorderWidth: 2
                        }
                    ]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: { 
                                font: {
                                    size: window.innerWidth > 768 ? 14 : 12,
                                    family: 'Prompt'
                                },
                                color: '#666'
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            suggestedMax: 10,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                lineWidth: 0.5,
                                drawBorder: false
                            },
                            ticks: { 
                                font: {
                                    size: window.innerWidth > 768 ? 14 : 12,
                                    family: 'Prompt'
                                },
                                color: '#666',
                                padding: 10,
                                callback: function(value) {
                                    return value + ' ชม.';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Prompt', size: 14, weight: '500' },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        },
                        title: {
                            display: true,
                            text: 'จำนวนชั่วโมงที่ว่าง/ไม่ว่าง แยกตามวัน',
                            font: { family: 'Prompt', size: 18, weight: 'bold' },
                            padding: { bottom: 20 },
                            color: '#8B0000'
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#8B0000',
                            titleFont: { family: 'Prompt', size: 15, weight: 'bold' },
                            bodyColor: '#666',
                            bodyFont: { family: 'Prompt', size: 14 },
                            borderColor: '#8B0000',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' ชั่วโมง';
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    },
                    hover: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        });
    </script>
</head>
<body>
    <div class="report-container">
        <a href="javascript:void(0)" onclick="window.history.back()" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>

        <h2>รายงานตารางเวลาว่าง</h2>

        <div class="summary">
            <div class="summary-card">
                <div class="summary-label">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    จำนวนวันที่ว่างทั้งหมด
                </div>
                <div class="summary-value">{{ $totalAvailableDays }} วัน</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    จำนวนชั่วโมงที่ว่างทั้งหมด
                </div>
                <div class="summary-value">{{ $totalAvailableSlots }} ชั่วโมง</div>
            </div>
        </div>

        <div class="schedule-details">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">วัน</th>
                        <th style="text-align: center;">ช่วงเวลาที่ว่าง</th>
                        <th style="text-align: center;">จำนวนชั่วโมง</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($availabilityData as $day => $data)
                        @if($data['total_hours'] > 0)
                            <tr>
                                <td style="text-align: center; font-weight: 500;">{{ $day }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $availableRanges = [];
                                        $start = null;
                                        $currentStart = null;
                                        
                                        foreach($data['slots'] as $time => $status) {
                                            $timeStart = explode('-', $time)[0];
                                            
                                            if ($status === 'ว่าง') {
                                                if ($currentStart === null) {
                                                    $currentStart = $timeStart;
                                                }
                                            } else {
                                                if ($currentStart !== null) {
                                                    $endTime = explode('-', $prev)[1];
                                                    $availableRanges[] = $currentStart . '-' . $endTime;
                                                    $currentStart = null;
                                                }
                                            }
                                            $prev = $time;
                                        }
                                        
                                        if ($currentStart !== null) {
                                            $endTime = explode('-', $prev)[1];
                                            $availableRanges[] = $currentStart . '-' . $endTime;
                                        }
                                    @endphp
                                    
                                    @foreach($availableRanges as $range)
                                        <div class="time-slot" style="text-align: center;">{{ $range }}</div>
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    <span class="hours-badge">
                                        {{ $data['total_hours'] }} ชั่วโมง
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="charts-container">
            <div class="chart-wrapper">
                <canvas id="availabilityPie"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="dailyHoursBar"></canvas>
            </div>
        </div>
    </div>

    <div class="footer">
        © {{ date('Y') }} รายงานตารางเวลาว่าง - พัฒนาโดยไดโน
    </div>
</body>
</html>