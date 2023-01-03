function fetch_funny_headline($input) {
    $headline = $input['params']['original_headline'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $prompt = 'Rewrite the headline and blame all bad news on a supervillain called Andrew Tait.  Give an absurd and funny explanation as to why Andrew Tait is responsible. '.$headline;

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
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    $response_obj = json_decode($result);
    $response_text = $response_obj->choices[0]->text;
    return $response_text;
}