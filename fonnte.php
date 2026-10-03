<?php

function kirimWhatsApp($target, $pesan)
{
    $token = "QJYw6xoopEQqkJP434vL";

    if ($token === "IQJYw6xoopEQqkJP434vL") {
        return [
            "status" => false,
            "message" => "Token Fonnte belum diisi."
        ];
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target" => $target,
            "message" => $pesan
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: " . $token
        ]
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {

        $error = curl_error($curl);

        curl_close($curl);

        return [
            "status" => false,
            "message" => $error
        ];
    }

    curl_close($curl);

    return json_decode($response, true);
}