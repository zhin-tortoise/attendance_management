<?php

/**
 * 時間を管理するオブジェクト
 */

class TimeValueObject
{
    private $hours; // 時。
    private $minutes; // 分。

    public function __construct(string $time)
    {
        $this->hours = explode(':', $time)[0];
        $this->minutes = explode(':', $time)[1];
    }

    /**
     * 時間の加算。
     * @param TimeValueObject 時間。
     * @return TimeValueObject 時分。
     */
    public function add(TimeValueObject $time)
    {
        $hours = $this->hours + $time->getHours();
        $minutes = $this->minutes + $time->getMinutes();

        if ($minutes >= 60) {
            $hours += 1;
            $minutes -= 60;
        }

        if ($hours === 0) {
            $hours = '00';
        }

        if ($minutes === 0) {
            $minutes = '00';
        }

        return new TimeValueObject("{$hours}:{$minutes}");
    }

    /**
     * 時のgetter。
     * @return int 時。
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * 分のgetter。
     * @return int 分。
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * 時分のゲッター。間にコロンが挟まる。
     * @return string 時分。
     */
    public function getHoursAndMinutes()
    {
        $hours = $this->hours;
        if (strlen($this->hours) === 1) {
            $hours = '0' . $hours;
        }

        $minutes = $this->minutes;
        if (strlen($this->minutes) === 1) {
            $minutes = '0' . $minutes;
        }
        return "{$hours}:{$minutes}";
    }
}
