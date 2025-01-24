<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function processSchedules(Request $request)
    {
        if (!$request->hasFile('images')) {
            Log::error('No files found in the request.');
            return back()->withErrors(['error' => 'กรุณาอัปโหลดไฟล์อย่างน้อยหนึ่งไฟล์']);
        }

        try {
            $images = $request->file('images');
            if (empty($images)) {
                Log::error('No valid files found.');
                return back()->withErrors(['error' => 'ไม่มีไฟล์ที่อัปโหลด']);
            }

            $uploadedImages = [];
            foreach ($images as $file) {
                $filename = uniqid() . '-' . $file->getClientOriginalName();
                $filePath = public_path('uploads/' . $filename);
                $file->move(public_path('uploads'), $filename);
                Log::info("Uploaded file path: " . $filePath);
                $uploadedImages[] = $filePath;
            }

            $schedules = [];
            $debugInfo = [];
            foreach ($uploadedImages as $imagePath) {
                $result = $this->callPythonScript($imagePath);
                if (!empty($result) && isset($result['status']) && $result['status'] === 'success') {
                    $schedules[] = $result['data'];
                    if (isset($result['debug_info'])) {
                        $debugInfo[] = [
                            'image' => basename($imagePath),
                            'info' => $result['debug_info']
                        ];
                    }
                } else {
                    Log::error("Failed to process image: " . $imagePath);
                    Log::error("Python script output: " . json_encode($result, JSON_UNESCAPED_UNICODE));
                }
            }

            $mergedSchedule = $this->mergeSchedules($schedules);
            
            // เก็บข้อมูลใน session
            session(['data' => $mergedSchedule]);
            
            // ส่งข้อมูลไปยังหน้า schedules
            return view('schedules', ['data' => $mergedSchedule]);

        } catch (\Exception $e) {
            Log::error('Error processing schedules: ' . $e->getMessage());
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการประมวลผลตารางเวลา: ' . $e->getMessage()]);
        }
    }

    private function callPythonScript($imagePath)
    {
        try {
            $pythonScript = base_path('scripts/analyze_image_with_color.py');
            Log::info("Python script path: " . $pythonScript);
            
            $process = new Process([
                'python3',  // หรือใช้ path เต็ม เช่น '/usr/bin/python3'
                $pythonScript,
                $imagePath
            ]);
            
            $process->setTimeout(60); // เพิ่มเวลา timeout เป็น 60 วินาที
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error("Python script error: " . $process->getErrorOutput());
                return [
                    'status' => 'error',
                    'message' => 'Python script execution failed'
                ];
            }

            $output = $process->getOutput();
            Log::info("Python script output: " . $output);
            
            $result = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("JSON decode error: " . json_last_error_msg());
                return [
                    'status' => 'error',
                    'message' => 'Invalid JSON output'
                ];
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Exception in callPythonScript: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function mergeSchedules(array $schedules)
    {
        if (empty($schedules)) {
            return [];
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $timeSlots = [
            "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
            "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
            "16:00-17:00", "17:00-18:00"
        ];

        $merged = array_fill_keys($days, array_fill_keys($timeSlots, 'ว่าง'));

        foreach ($schedules as $schedule) {
            if (!is_array($schedule)) continue;
            
            foreach ($days as $day) {
                if (!isset($schedule[$day])) continue;
                
                foreach ($timeSlots as $time) {
                    if (isset($schedule[$day][$time]) && $schedule[$day][$time] === 'ไม่ว่าง') {
                        $merged[$day][$time] = 'ไม่ว่าง';
                    }
                }
            }
        }

        return $merged;
    }
}
