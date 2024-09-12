<?php
if (isset($_POST['pincode'])) {
    $pincode = $_POST['pincode'];

    $api_url = "http://www.postalpincode.in/api/pincode/{$pincode}";
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check if any error occurred
    if (curl_errno($ch)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error: ' . curl_error($ch)
        ]);
    } else {
        $data = json_decode($response, true);
        
        if ($data && isset($data['PostOffice']) && !empty($data['PostOffice'])) {
            $city = $data['PostOffice'][0]['District'];
            $state = $data['PostOffice'][0]['State'];
            
            echo json_encode([
                'status' => 'success',
                'city' => $city,
                'state' => $state
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid Pincode or no data found.'
            ]);
        }
    }
    
    // Close cURL session
    curl_close($ch);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Pincode is required.'
    ]);
}
?>
