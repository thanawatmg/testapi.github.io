<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบลิงก์ | เติมเงิน True Wallet</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const resultDiv = document.getElementById('voucher_result');
        
        form.addEventListener('submit', function(event) {
            event.preventDefault();  // ป้องกันการ submit แบบปกติ

            // ดึงค่าลิงก์จากฟอร์ม
            const truewalletLink = document.getElementById('truewallet_link').value;

            // ตรวจสอบว่าลิงก์ว่างหรือไม่
            if (truewalletLink === '') {
                resultDiv.innerHTML = '<p class="text-red-500">กรุณากรอกลิงก์อั่งเปา</p>';
                return;
            }

            // สร้าง request ด้วย Ajax
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_payment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // ส่งข้อมูลผ่าน POST
            xhr.send('truewallet_link=' + encodeURIComponent(truewalletLink));

            // เมื่อมีการตอบกลับจาก server
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);

                    if (response.status === 'success') {
                        resultDiv.innerHTML = '<p class="text-green-500">ลิงก์ถูกต้อง จำนวนเงิน: ' + response.message + ' บาท</p>';
                    } else {
                        resultDiv.innerHTML = '<p class="text-red-500">' + response.message + '</p>';
                    }
                } else {
                    resultDiv.innerHTML = '<p class="text-red-500">เกิดข้อผิดพลาดในการติดต่อ API</p>';
                }
            };
        });
    });
</script>
</head>
<body class="bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-black shadow-lg border-b border-green-500">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-green-500">LOGO</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Banner Section -->
    <div class="relative bg-black h-96 border-b border-green-500">
        <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-transparent"></div>
        <div class="max-w-6xl mx-auto px-4 h-full flex items-center relative">
            <div class="text-white">
                <h1 class="text-5xl font-bold mb-4">ตรวจสอบลิงก์ True Wallet</h1>
                <p class="text-xl mb-8 text-gray-300">ตรวจสอบว่าลิงก์อั่งเปาถูกต้องและจำนวนเงิน</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="bg-gray-800 rounded-lg p-8 border border-green-500/30 hover:border-green-500 transition duration-300">
            <h2 class="text-3xl font-bold text-white mb-6">กรอกลิงก์ True Wallet ของคุณ</h2>

            <!-- Form for entering the True Wallet link -->
             <form action="process_payment.php" method="POST">
                <div class="mb-6">
                    <label for="truewallet_link" class="block text-white text-lg font-semibold mb-2">ลิงก์อั่งเปา (True Wallet)</label>
                    <input type="url" name="truewallet_link" id="truewallet_link" class="w-full p-3 bg-gray-700 text-white border border-green-500 rounded-md focus:outline-none focus:border-green-500" placeholder="กรุณากรอกลิงก์อั่งเปา">
                </div>
                
                <div class="mb-6">
                    <button type="submit" class="bg-green-500 text-black px-6 py-3 rounded-md hover:bg-green-400 transition duration-300 font-semibold">
                        ตรวจสอบลิงก์
                    </button>
                </div>

                <!-- ผลลัพธ์การตรวจสอบ -->
                <div id="voucher_result" class="text-white mt-6"></div>
            </form>
        </div>
        
    </div>
</body>
</html>
