<?php
    session_start();
    
// ตรวจสอบว่าเป็นการร้องขอผ่าน POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับลิงก์ True Wallet จากฟอร์ม
    $link = isset($_POST['truewallet_link']) ? $_POST['truewallet_link'] : '';

    if (!empty($link)) {
        // ดึง voucher_hash จากลิงก์อั่งเปา
        $voucher_hash = str_replace("https://gift.truemoney.com/campaign/?v=", "", $link);
        // echo json_encode([
        //     'status' => 'test',
        //     'message' => $voucher_hash
        // ]);
        // ตรวจสอบ voucher_hash
        if (empty($voucher_hash)) {
            echo json_encode([
                'status' => 'fail',
                'message' => 'ลิงก์อั่งเปาไม่ถูกต้อง'
            ]);
            exit();
        }
    }
        // URL API ที่จะเรียก
        $api_url = "https://gift.truemoney.com/campaign/vouchers/{$voucher_hash}/redeem";
        // ข้อมูลที่ต้องส่งใน POST
        $phone = '064877832';  // ใช้เบอร์โทรที่ต้องการเติมเงิน (สามารถแก้ไขเป็นเบอร์จริงได้)
        $data = json_encode([
            'mobile' => $phone,
            'voucher_hash' => $voucher_hash,
        ]);
        // เริ่มการเชื่อมต่อด้วย cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // ปิดการตรวจสอบ SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ปิดการตรวจสอบ SSL
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  // ส่งข้อมูล POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json'
        ]);

        // รอผลลัพธ์จาก API
        $response = curl_exec($ch);
        curl_close($ch);

        // ตรวจสอบผลลัพธ์จาก API
        $result = json_decode($response,true);  // แปลง JSON เป็น array
        // $codesta = $result['status']['code'];
        echo json_encode([
            'status' => 'success',
            'message' => $response
        ]);
        // ตรวจสอบสถานะจาก API response
        if(isset($result->status->code)){
            $codestatus = $result->status->code;
            $member = $result->data->voucher->member;
            
        }
    }
?>
