import cv2
import numpy as np
import json
import sys
import os
import io

# ตั้งค่าการเข้ารหัสให้ stdout เป็น UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DEBUG = True  # ตั้งค่า True เพื่อเปิด Debug Mode
DEBUG_PATH = "debug"  # โฟลเดอร์สำหรับบันทึกภาพ Debug

# สร้างโฟลเดอร์ Debug ถ้ายังไม่มี
if DEBUG and not os.path.exists(DEBUG_PATH):
    os.makedirs(DEBUG_PATH)

def resize_image_auto(img, max_dimension=1024, min_dimension=256):
    """Resize image with aspect ratio maintained."""
    height, width = img.shape[:2]

    if max(height, width) > max_dimension:
        scale = max_dimension / max(height, width)
        new_width = int(width * scale)
        new_height = int(height * scale)
        resized_img = cv2.resize(img, (new_width, new_height))
    elif min(height, width) < min_dimension:
        scale = min_dimension / min(height, width)
        new_width = int(width * scale)
        new_height = int(height * scale)
        resized_img = cv2.resize(img, (new_width, new_height))
    else:
        resized_img = img

    if DEBUG:
        cv2.imwrite(os.path.join(DEBUG_PATH, "resized_image.jpg"), resized_img)

    return resized_img

def detect_and_crop_table_precise(img):
    """Detect and crop the table from the image."""
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    _, thresh = cv2.threshold(blurred, 200, 255, cv2.THRESH_BINARY_INV)

    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    largest_contour = max(contours, key=cv2.contourArea)
    x, y, w, h = cv2.boundingRect(largest_contour)

    # Add margins to the crop
    top_margin = int(h * 0.12)
    bottom_margin = int(h * 0.02)
    left_margin = int(w * 0.16)
    right_margin = int(w * 0.005)

    y += top_margin
    h -= (top_margin + bottom_margin)
    x += left_margin
    w -= (left_margin + right_margin)

    cropped_img = img[y:y + h, x:x + w]

    if DEBUG:
        debug_img = img.copy()
        cv2.rectangle(debug_img, (x, y), (x + w, y + h), (0, 255, 0), 2)
        cv2.imwrite(os.path.join(DEBUG_PATH, "table_detection.jpg"), debug_img)
        cv2.imwrite(os.path.join(DEBUG_PATH, "cropped_table.jpg"), cropped_img)

    return cropped_img

def analyze_single_image_auto(img):
    """Analyze the schedule image."""
    try:
        img = resize_image_auto(img)
        cropped_img = detect_and_crop_table_precise(img)

        hsv = cv2.cvtColor(cropped_img, cv2.COLOR_BGR2HSV)
        lower_blue = np.array([100, 50, 50])
        upper_blue = np.array([140, 255, 255])
        mask_blue = cv2.inRange(hsv, lower_blue, upper_blue)

        kernel = np.ones((7, 7), np.uint8)
        mask_blue = cv2.morphologyEx(mask_blue, cv2.MORPH_CLOSE, kernel)
        mask_blue = cv2.GaussianBlur(mask_blue, (7, 7), 0)

        if DEBUG:
            cv2.imwrite(os.path.join(DEBUG_PATH, "blue_mask.jpg"), mask_blue)

        rows, cols = 7, 10
        height, width = mask_blue.shape[:2]
        cell_height = height // rows
        cell_width = width // cols

        result = {}
        time_slots = [
            "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
            "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
            "16:00-17:00", "17:00-18:00"
        ]
        days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

        debug_img = cropped_img.copy()
        for i in range(rows):
            day = days[i]
            result[day] = {}
            for j in range(cols):
                x1, y1 = j * cell_width, i * cell_height
                x2, y2 = (j + 1) * cell_width, (i + 1) * cell_height

                cell = mask_blue[y1:y2, x1:x2]
                white_pixels = np.sum(cell == 255)
                total_pixels = cell.size
                ratio = white_pixels / total_pixels

                result[day][time_slots[j]] = "ว่าง" if ratio > 0.5 else "ไม่ว่าง"

                if DEBUG:
                    color = (0, 255, 0) if ratio > 0.5 else (0, 0, 255)
                    cv2.rectangle(debug_img, (x1, y1), (x2, y2), color, 2)

        if DEBUG:
            cv2.imwrite(os.path.join(DEBUG_PATH, "final_debug.jpg"), debug_img)

        return {
            'status': 'success',
            'data': result
        }

    except Exception as e:
        return {
            'status': 'error',
            'message': str(e)
        }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "กรุณาระบุ path ของรูปภาพ"}, ensure_ascii=False))
        sys.exit(1)

    image_path = sys.argv[1]
    img = cv2.imread(image_path)

    if img is None:
        print(json.dumps({"error": f"ไม่สามารถอ่านรูปภาพได้: {image_path}"}, ensure_ascii=False))
        sys.exit(1)

    result = analyze_single_image_auto(img)
    print(json.dumps(result, ensure_ascii=False, indent=2))
