<?php

class seven {
    public $international = true;
    public $lang;
    public $config;
    public $error;
    private $instance;
    private $title;
    private $body;
    private $numbers = [];

    public function __construct($external_config = []) {
        if (!class_exists('seven_API')) include __DIR__ . DS . 'api.php';

        $this->lang = Modules::Lang('SMS', __CLASS__);
        $this->config = array_merge(Modules::Config('SMS', __CLASS__), $external_config);
        $this->title = $this->config['origin'];
        $this->instance = new seven_API;
        $this->instance->set_credentials($this->config['api-key']);
    }

    public function body($text = '', $template = false, $variables = [], $lang = '', $user = 0) {
        $this->numbers_reset();

        if ($template) {
            $look = View::notifications('sms', $template, $text, $variables, $lang, $user);

            if ($look !== false && isset($look['content'])) {
                if (isset($look['title'])) $this->title($look['title']);

                $text = $look['content'];
            }
        }

        if (!class_exists('Money')) Helper::Load('Money');

        foreach (Money::getCurrencies() as $row) {
            if (($row['prefix'] && substr($row['prefix'], -1, 1) === ' ')
                || ($row['suffix'] && 0 === strpos($row['suffix'], ' ')))
                $code = $row['code'];
            else
                $code = $row['prefix'] ? $row['code'] . ' ' : ' ' . $row['code'];

            $row['prefix'] = Utility::text_replace($row['prefix'], [' ' => '']);
            $row['suffix'] = Utility::text_replace($row['suffix'], [' ' => '']);

            if ($row['prefix'] && !Validation::isEmpty($row['prefix']))
                $text = Utility::text_replace($text, [$row['prefix'] => $code]);
            elseif ($row['suffix'] && !Validation::isEmpty($row['suffix']))
                $text = Utility::text_replace($text, [$row['suffix'] => $row['code']]);
        }

        $this->body = Filter::transliterate($text);

        return $this;
    }

    public function numbers_reset() {
        return $this->numbers = [];
    }

    public function title($arg = '') {
        $this->title = $arg;
        return $this;
    }

    public function AddNumber($arg = 0, $cc = null) {
        if (!is_array($arg)) {
            if ($cc !== null) $arg = [$cc . '|' . $arg];
            else $arg = [$arg];
        }

        foreach ($arg as $num) {
            if (false !== strpos($num, '|')) {
                $split = explode('|', $num);
                $num = $split[0] . $split[1];
            }

            if (!in_array($num, $this->numbers)) $this->numbers[] = $num;
        }

        return $this;
    }

    public function submit($return_this = false) {
        if (Validation::isEmpty($this->body)) {
            $this->error = 'Message content can not be left blank!';
            return false;
        }

        if (!$this->numbers) {
            $this->error = 'Enter the phone number to be sent.';
            return false;
        }

        $send = $this->instance->Submit($this->title, $this->body, $this->numbers);
        $this->error = $this->instance->error;

        if (!$send) echo ERROR_DEBUG ? $this->getError() : null;

        return $return_this ? $this : $send;
    }

    public function getError() {
        return $this->error;
    }

    public function getReport($id = 0) {
        $content = $this->instance->ReportLook($id == 0 ? $this->getReportID() : $id);

        if (!$content) {
            $this->error = $this->instance->error;
            return false;
        }

        return $content; // ['data' => $content, 'count' => 1]
    }

    public function getReportID() {
        return $this->instance->rid;
    }

    public function getBalance() {
        return $this->instance->Balance();
    }

    public function get_prices() {
        $prices = $this->instance->get_prices();
        $result = [];

        if ($prices)
            foreach ($prices as $row) $result[$row['countryCode']] = $row['prices'];
        else {
            $this->error = $this->instance->error;

            return false;
        }

        return $result;
    }

    public function getNumbers() {
        return $this->numbers;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getBody() {
        return $this->body;
    }
}
