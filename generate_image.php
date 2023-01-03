function generate_image() {
    $posted_data = file_get_contents('php://input');
    $data = json_decode($posted_data);
    $target_object = $data->target_object ?? '';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/images/generations");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
      \"model\": \"image-alpha-001\",
      \"prompt\": \"A cartoon with muscular, bald man, with a beard, laughing in front of $target_object'\",
      \"num_images\":1,
      \"size\":\"512x512\",
      \"response_format\":\"url\"
    }");
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = "Content-Type: application/json";
    $headers[] = "Authorization: Bearer ".API_KEY;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    echo $result;

    die();
} 