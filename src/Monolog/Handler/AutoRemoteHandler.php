<?php

namespace Monolog\Handler;

use Monolog\Logger;

/**
 * Sends notification to AutoRemote Devices
 * (AutoRemote is a Tasker Plugin for Android)
 *
 * @link http://joaoapps.com/autoremote/
 * @see http://tasker.dinglisch.net/
 *
 * @author Caio Costa <github@bycosta.com>
 */
class AutoRemoteHandler extends AbstractProcessingHandler
{
    protected $host = 'https://autoremotejoaomgcd.appspot.com/sendnotification';

    /**
     * @var array
     */
    protected $keys = array();

    /**
     * @param string|array  $keys   Device key(s). Use array for multiple keys
     * @param integer       $level  The minimum logging level at which this handler will be triggered
     * @param Boolean       $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($keys, $level = Logger::ERROR, $bubble = true)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }

        $this->keys = $keys;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        foreach ($this->keys as $key) {
            $data = array();
            $data['key'] = $key;
            $data['title'] = $record['level_name'] . ': ' . $record['channel'];
            $data['text'] = $record['message'];
            $data['message'] = 'say 5=:=alerta';
            $data['priority'] = 2;
            $data['led'] = "'red'";
            $data['ledon'] = 100;
            $data['ledoff'] = 1000;
            $data['id'] = microtime(true);
            $data['vibration'] = '0,119,1215,131,690,129,904,106';

            file_get_contents($this->host . '?' . http_build_query($data));
        }
    }
}