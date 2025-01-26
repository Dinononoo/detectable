<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลลัพธ์ตารางเวลา</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        /* Body Styling */
        body {
            font-family: 'Prompt', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://www.museumthailand.com/upload/user/1573012494_8226.jpg') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
        }

        /* Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(4px);
            z-index: 1;
        }

        /* Container Styling */
        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 90%;
            width: 100%;
            overflow-x: auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        h1 {
            color: #b71c1c;
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        /* Top-right container for status info and report button */
        .top-right {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .status-info {
            text-align: right;
        }

        .status-info span {
            display: inline-block;
            margin-bottom: 5px;
            padding: 5px 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .status-info .available {
            background: linear-gradient(45deg, #32cd32, #32CD32);
        }

        .status-info .unavailable {
            background: linear-gradient(45deg,#ff0000, #FF0000);
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 3;
            display: flex;
            align-items: center;
            padding: 12px 18px;
            background: linear-gradient(45deg, #b71c1c, #d32f2f);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .back-button img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        table {
            width: 100%;
            table-layout: auto;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
        }

        table thead {
            background: linear-gradient(45deg, #000000, #1a1a1a);
            color: white;
            font-size: 1rem;
        }

        table thead th {
            padding: 15px;
            font-size: 1rem;
            text-align: center;
            white-space: nowrap;
            border: 1px solid #333;
        }

        table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        table tbody tr:hover {
            background: linear-gradient(90deg, #f0f0f0, #e6e6e6);
            transform: scale(1.01);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        table td, table th {
            padding: 15px;
            text-align: center;
            font-size: 0.95rem;
            border: 1px solid #333;
            word-wrap: break-word;
        }

        .available {
            background: linear-gradient(45deg, #98FB98, #32CD32);
            color: white;
        }

        .unavailable {
            background: linear-gradient(45deg, #FF7F7F, #FF0000);
            color: white;
        }

        /* Report button styling - Updated */
        .report-button {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background: linear-gradient(135deg, #FFD700, #FFC107, #FFA000, #FFD700);
            background-size: 300% 100%;
            animation: gradientShift 3s ease infinite;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 112, 67, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
        }

        .report-button:hover {
            background: linear-gradient(135deg, #BF360C, #FF7043, #FFA726); /* โทนสีส้มเข้ม */
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(255, 112, 67, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .report-button img {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .report-button:hover img {
            transform: rotate(-10deg);
        }

        .report-button:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 4px 15px rgba(255, 112, 67, 0.2);
        }

        /* เพิ่ม animation ให้กับตาราง */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* เพิ่ม hover effect ให้กับ cell */
        td.available, td.unavailable {
            position: relative;
            overflow: hidden;
        }

        td.available::after, td.unavailable::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        td:hover::after {
            transform: translateX(0);
        }

        /* ปรับปรุง gradient ของปุ่ม */
        .report-button {
            background: linear-gradient(135deg, #FFD700, #FFC107, #FFA000, #FFD700);
            background-size: 300% 100%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* เพิ่ม transition ให้กับ status info */
        .status-info span {
            transition: all 0.3s ease;
        }

        .status-info span:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ปุ่มย้อนกลับ -->
        <a href="{{ route('upload.index') }}" class="back-button">
            <img src="https://cdn-icons-png.flaticon.com/512/271/271220.png" alt="Back Icon">
            ย้อนกลับ
        </a>

        <!-- Top-right section -->
        <div class="top-right">
            <div class="status-info">
                <span class="available">ว่าง</span>
                <span class="unavailable">ไม่ว่าง</span>
            </div>
            <a href="{{ route('report.show') }}" class="report-button">
                <img src="https://cdn-icons-png.flaticon.com/512/9716/9716941.png" alt="Report Icon">
                report
            </a>
        </div>

        <h1>ผลลัพธ์ตารางเวลา</h1>

        <form action="{{ route('processSchedules') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <table>
                <thead>
                    <tr>
                        <th>วัน/เวลา</th>
                        <th>08:00-09:00</th>
                        <th>09:00-10:00</th>
                        <th>10:00-11:00</th>
                        <th>11:00-12:00</th>
                        <th>12:00-13:00</th>
                        <th>13:00-14:00</th>
                        <th>14:00-15:00</th>
                        <th>15:00-16:00</th>
                        <th>16:00-17:00</th>
                        <th>17:00-18:00</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data))
                        @foreach ($data as $day => $slots)
                            <tr>
                                <td>{{ $day }}</td>
                                @foreach ($slots as $time => $slot)
                                    <td class="{{ $slot == 'ว่าง' ? 'available' : 'unavailable' }}"></td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11">ไม่มีข้อมูล</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
