    function fetch_main_subject($headline) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $prompt = 'What is the subject in the following headline: '.$headline;

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
            \"model\": \"text-davinci-002\",
            \"prompt\": \"" . $prompt . "\",
            \"max_tokens\": 100,
            \"top_p\": 1,
            \"stop\": \"\"
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
            $response_text = $response_obj->choices[0]->text ?? '';
        }

        curl_close ($ch);

        // Create the response object
        $response = new stdClass();
        $response->text = trim($response_text);
        $response->status = $httpCode;

        return $response;
    }