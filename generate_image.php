    function generate_image($target_subject) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/images/generations");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
            \"model\": \"image-alpha-001\",
            \"prompt\": \"A picture of a clown laughing in front of $target_subject'\",
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

        // Get the HTTP status code
        $info = curl_getinfo($ch);
        $httpCode = $info['http_code'];

        if (curl_error($ch)) {
            // There was an error
            $response_text = curl_error($ch);
        } else {
            // The request was successful
            $response_obj = json_decode($result);
            $pic_path = $response_obj->data[0]->url ?? '';

            //remove unwanted whitespace
            $pic_path = trim($pic_path);
       
            //does the pic path look look?
            $str_start = substr($pic_path, 0, 4);

            if($str_start !== 'http') {
                $httpCode = 500; //Server Error
                $response_text = 'We could not generate an image on this occasion.';
            } else {
                $response_text = $pic_path;
            }

        }

        curl_close ($ch);

        // Create the output object
        $output = new stdClass();
        $output->text = $response_text;
        $output->status = $httpCode;

        return $output;
    }