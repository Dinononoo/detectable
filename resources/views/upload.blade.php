<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดตารางเวลา</title>
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
            overflow: hidden;
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
            backdrop-filter: blur(3px);
            z-index: 1;
        }

        /* Butterfly GIF Styling */
        .yellow-butterfly {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: 2;
            opacity: 0.6;
        }

        /* Form Container */
        form {
            position: relative;
            z-index: 3;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1),
                        0 15px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: formSlide 1.5s ease-out;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            overflow-y: auto;
            max-height: 90vh;
        }

        form:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2),
                        0 20px 50px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }

        @keyframes formSlide {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Form Heading */
        h1 {
            color: #b71c1c;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }

        /* File Upload Styling */
        .custom-file {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: rgba(245, 245, 245, 0.9);
            border: 2px dashed #f5c6cb;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .custom-file:hover {
            background-color: #fce4ec;
            border-color: #b71c1c;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .custom-file input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .custom-file img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .preview-item {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            background: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-item button {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        /* Button Styling */
        button {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1rem;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(to right, #b71c1c, #d32f2f);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: linear-gradient(to right, #d32f2f, #b71c1c);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Note Text */
        .note {
            font-size: 0.8rem;
            color: #b71c1c;
            margin-top: 12px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            h1 {
                font-size: 1.5rem;
            }

            .custom-file {
                padding: 20px;
            }

            .custom-file img {
                width: 50px;
                height: 50px;
            }

            button {
                padding: 10px;
                font-size: 0.9rem;
            }

            .preview-item {
                width: 60px;
                height: 60px;
            }

            .note {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            form {
                width: 100%;
                padding: 20px;
            }

            .custom-file {
                padding: 15px;
            }

            button {
                padding: 8px;
                font-size: 0.8rem;
            }

            .note {
                font-size: 0.6rem;
            }
        }

        /* Alert Styling */
        .custom-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            z-index: 9999;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background-color: #FFD700;
            color: #000;
        }

        .custom-alert.error {
            background-color: #FFCC00;
        }

        .custom-alert.success {
            background-color: #FFD700;
        }
    </style>
</head>
<body>
    <img src="https://img1.picmix.com/output/stamp/normal/6/0/1/5/1955106_d24dc.gif" alt="Yellow Butterfly Animation" class="yellow-butterfly">
    <form id="upload-form" action="{{ route('processSchedules') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1>อัปโหลดตารางเวลา</h1>
        <div class="custom-file">
            <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" alt="Upload Icon">
            <input type="file" name="images[]" id="file" multiple accept=".jpg" onchange="handleFileUpload()">
            <label for="file">คลิกเพื่อเลือกไฟล์</label>
            <div id="preview-container" class="preview-container"></div>
        </div>
        <button type="submit" onclick="submitForm(event)">ประมวลผล</button>
        <p class="note">รองรับไฟล์ประเภท JPG, JPEG, PNG</p>
    </form>

    <script>
        const fileInput = document.getElementById('file');
        const previewContainer = document.getElementById('preview-container');
        const uploadedFiles = new DataTransfer();

        function handleFileUpload() {
            const newFiles = Array.from(fileInput.files);

            newFiles.forEach(async (file) => {
                const isValid = await validateFile(file);
                if (isValid) {
                    uploadedFiles.items.add(file);
                    displayPreview(file);
                }
            });

            fileInput.files = uploadedFiles.files;
        }

        function validateFile(file) {
            const img = new Image();
            const reader = new FileReader();

            return new Promise((resolve) => {
                reader.onload = (e) => {
                    img.src = e.target.result;
                };

                img.onload = () => {
                    if (img.width < 550 || img.height < 150) {
                        customAlert(`ขนาดของไฟล์ <b>${file.name}</b> เล็กเกินไป! <b></b>.`, "error");
                        resolve(false);
                    } else {
                        resolve(true);
                    }
                };

                reader.readAsDataURL(file);
            });
        }

        function displayPreview(file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.classList.add('preview-item');
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview Image">
                    <button type="button" onclick="removePreview('${file.name}')">×</button>
                `;
                previewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }

        function removePreview(fileName) {
            const newFiles = Array.from(uploadedFiles.files).filter(file => file.name !== fileName);
            uploadedFiles.items.clear();

            newFiles.forEach(file => uploadedFiles.items.add(file));
            fileInput.files = uploadedFiles.files;

            refreshPreview();
        }

        function refreshPreview() {
            previewContainer.innerHTML = '';
            Array.from(uploadedFiles.files).forEach(file => displayPreview(file));
        }

        function customAlert(message, type) {
            const alertBox = document.createElement('div');
            alertBox.classList.add('custom-alert', type);
            alertBox.innerHTML = message;

            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.remove();
            }, 3000);
        }

        function submitForm(event) {
            if (uploadedFiles.files.length === 0) {
                event.preventDefault();
                customAlert("กรุณาอัปโหลดไฟล์อย่างน้อยหนึ่งไฟล์ก่อนประมวลผล", "error");
            }
        }
    </script>
</body>
</html>
