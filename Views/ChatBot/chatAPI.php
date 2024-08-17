<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mensaje'])) {
        $pregunta = $_POST['mensaje'];

        $api_key = "";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        ]);

        $data = [
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [],
        ];

        $data['messages'][] = ['role' => 'system', 'content' => 'Actua como un experto '];
        $data['messages'][] = ['role' => 'user', 'content' => $pregunta];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


        $response = curl_exec($ch);
        $respuesta = '';
        $decoded_response = json_decode($response, true);

        if (isset($decoded_response['choices'][0]['message']['content'])) {
            $respuesta = $decoded_response['choices'][0]['message']['content'];
        }

        curl_close($ch);

        echo $respuesta;
    }
}