<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    private function prepareReportData($scheduleData)
    {
        $days = [
            'Monday' => 'วันจันทร์',
            'Tuesday' => 'วันอังคาร',
            'Wednesday' => 'วันพุธ',
            'Thursday' => 'วันพฤหัสบดี',
            'Friday' => 'วันศุกร์',
            'Saturday' => 'วันเสาร์',
            'Sunday' => 'วันอาทิตย์'
        ];

        $availabilityData = [];
        $totalAvailableDays = 0;
        $totalAvailableSlots = 0;

        foreach ($days as $englishDay => $thaiDay) {
            if (isset($scheduleData[$englishDay])) {
                $daySlots = [];
                $availableHours = 0;

                foreach ($scheduleData[$englishDay] as $time => $status) {
                    $daySlots[$time] = $status;
                    if ($status === 'ว่าง') {
                        $availableHours++;
                        $totalAvailableSlots++;
                    }
                }

                // เพิ่ม debug log
                Log::debug("Processing day: {$englishDay}", [
                    'slots' => $daySlots,
                    'available_hours' => $availableHours
                ]);

                $availabilityData[$thaiDay] = [
                    'slots' => $daySlots,
                    'total_hours' => $availableHours
                ];

                if ($availableHours > 0) {
                    $totalAvailableDays++;
                }
            }
        }

        // คำนวณเปอร์เซ็นต์
        $totalPossibleSlots = count($days) * 10; // 7 วัน * 10 ช่วงเวลา
        $percentageAvailable = ($totalPossibleSlots > 0) 
            ? round(($totalAvailableSlots / $totalPossibleSlots) * 100, 1) 
            : 0;

        return [
            'days' => array_values($days),
            'timeSlots' => array_keys($scheduleData['Monday'] ?? []),
            'availabilityData' => $availabilityData,
            'totalAvailableDays' => $totalAvailableDays,
            'totalAvailableSlots' => $totalAvailableSlots,
            'percentageAvailable' => $percentageAvailable
        ];
    }

    public function generate(Request $request)
    {
        $scheduleData = session('data');
        
        if (!$scheduleData) {
            return back()->with('error', 'ไม่พบข้อมูลตารางเวลา');
        }

        // ใช้ข้อมูลเดียวกับ show()
        $data = $this->prepareReportData($scheduleData);
        
        $html = view('pdf.report', $data)->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'THSarabunNew');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('Schedule_Report.pdf', ['Attachment' => true]);
    }

    public function show()
    {
        $scheduleData = session('data');
        if (!$scheduleData) {
            return redirect()->route('upload.index')
                            ->withErrors(['error' => 'ไม่พบข้อมูลตารางเวลา กรุณาอัปโหลดไฟล์ใหม่']);
        }

        $data = $this->prepareReportData($scheduleData);
        
        // เพิ่ม debug log
        Log::debug('Session Data:', ['data' => $scheduleData]);
        Log::debug('Prepared Report Data:', $data);
        
        return view('report', [
            'days' => $data['days'],
            'timeSlots' => $data['timeSlots'],
            'availabilityData' => $data['availabilityData'],
            'totalAvailableDays' => $data['totalAvailableDays'],
            'totalAvailableSlots' => $data['totalAvailableSlots'],
            'percentageAvailable' => $data['percentageAvailable']
        ]);
    }

    public function downloadExcel()
    {
        $data = session('data');
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'ไม่มีข้อมูลใน Session']);
        }
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // ตั้งค่าหัวตาราง
        $sheet->setCellValue('A1', 'วัน/เวลา');
        $timeSlots = [
            '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00',
            '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00',
            '16:00-17:00', '17:00-18:00'
        ];
        $col = 'B';
        foreach ($timeSlots as $slot) {
            $sheet->setCellValue($col . '1', $slot);
            $col++;
        }
    
        $row = 2;
        foreach ($data as $day => $slots) {
            $sheet->setCellValue('A' . $row, $day);
            $col = 'B';
            foreach ($slots as $slot) {
                $sheet->setCellValue($col . $row, $slot);
                $col++;
            }
            $row++;
        }
    
        // บันทึกไฟล์ชั่วคราว
        $filePath = storage_path('app/temp/Schedule_Report.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function downloadPDF()
    {
        $scheduleData = session('data');
        if (!$scheduleData) {
            return redirect()->back()->withErrors(['error' => 'ไม่มีข้อมูลใน Session']);
        }

        $data = $this->prepareReportData($scheduleData);
        
        $html = view('pdf.report', $data)->render();

        $options = new Options();
        $options->set('defaultFont', 'THSarabunNew');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('Schedule_Report.pdf', ['Attachment' => true]);
    }
}
