<?php

class sms77_API {
    public $api_key;
    public $error;
    public $rid = 0;

    public function __construct() {
    }

    public function set_credentials($api_key = '') {
        $this->api_key = $api_key;
    }

    public function Submit($title = null, $message = null, $number = 0) {
        $recipients = [];

        foreach (is_array($number) ? $number : [$number] as $n) $recipients[] = $n;

        $json_data = [
            'from' => $title,
            'json' => 1,
            'text' => $message,
            'to' => implode(',', $recipients),
        ];

        $solve = $this->curl_use('sms', $json_data);

        if (!$solve) {
            $this->error = 'The API response could not be resolved.';
            return false;
        }

        if ('100' !== $solve['success']) {
            $this->error = 'Error: <' . $solve['success'] . '>';
            return false;
        }

        $message = $solve->messages;

        if (!$message || !isset($message['id'])) {
            $this->error = print_r($solve, true);
            return false;
        }

        $this->rid = $message->id;

        return true;
    }

    private function curl_use($endpoint, $post_data = []) {
        $ch = curl_init('https://gateway.sms77.io/api/' . $endpoint);

        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'SentWith: WISECP',
                'X-Api-Key: ' . $this->api_key,
            ],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
        ];

        if ($post_data) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = Utility::jencode($post_data);
        }

        curl_setopt_array($ch, $options);

        $res = curl_exec($ch);

        return '900' === $res ? false : Utility::jdecode($res, true);
    }

    public function Balance() {
        error_log('wisecp.sms.balance'); // TODO: remove!
        syslog(LOG_INFO, 'wisecp.sms.balance'); // TODO: remove!

        $solve = $this->curl_use('balance');

        if (!$solve) {
            $this->error = 'The API response could not be resolved.';
            return false;
        }

        return [
            'balance' => $solve,
            'currency' => 'EUR',
        ];
    }

    public function ReportLook($rid) {
        $outcome = $this->curl_use('status?msg_id=' . $rid);
        $lines = explode(PHP_EOL, $outcome);

        if (count($lines) !== 2) {
            $this->error = 'The API response could not be resolved.';
            return false;
        }

        return reset($lines);
    }

    public function get_prices() {
        $solve = $this->curl_use('pricing');
        $rows = [];

        if (isset($solve['countries'])) {
            foreach ($solve['countries'] as $row) {
                $network = reset($row['networks']);

                if (!$network) continue;

                $rows[] = [
                    'countryCode' => $row['countryCode'],
                    'prices' => [
                        'EUR' => $network['price'],
                    ],
                ];
            }
        }

        return $rows;
    }
}
