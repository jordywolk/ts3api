<?php
/**
 * Created by PhpStorm.
 * User: jordy
 * Date: 28-4-2020
 * Time: 17:38
 */

namespace Jordywolk\Ts3api;

use GuzzleHttp\Exception\TransferException;

class ServerConnection
{
    /**
     * @var resource
     */
    private $connection;

    public function __construct()
    {
        $this->connection = new \GuzzleHttp\Client(['base_uri' => config('ts3api.server_ip') . ":" . config('ts3api.server_port')]);

        try {
            $this->connection->request('GET', "/" . config('ts3api.server_id') . "/clientupdate?client_nickname=" . config('ts3api.nickname') ,[
                'headers' => [
                    'x-api-key' => config('ts3api.api_key')
                ]
            ]);
        }
        catch (TransferException $e) {
            report($e);
        }

//TODO: Remove

//        $this->connection = curl_init();
//        //Pass API key
//        curl_setopt($this->connection, CURLOPT_HTTPHEADER, array('x-api-key:' . config('ts3api.api_key')));
//
//        //Keep response result
//        curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, 1);
//
//        //Set nickname to value specified in config file
//        $url = config('ts3api.server_ip') . ":" . config('ts3api.server_port') . "/" . config('ts3api.server_id') . "/clientupdate?client_nickname=" . config('ts3api.nickname');
//        curl_setopt($this->connection, CURLOPT_URL, $url);
//        curl_exec($this->connection);
    }

    public function __destruct()
    {

    }

    //TODO: Allow '-xxx' flags
    protected function send_command(string $command, array $parameters = null)
    {
        //Base URL
        $url = "/" . config('ts3api.server_id') . "/" . $command;

        //Add parameters to URL, if given any
        if($parameters != null) {
            $url .= "?";
            foreach ($parameters as $parameter => $value) {
                $url .= $parameter . "=" . $value . "&";
            }
            //Remove last & from URL string
            $url = rtrim($url, "&");
        }

        try {
            $response = $this->connection->request('GET', $url ,[
                'headers' => [
                    'x-api-key' => config('ts3api.api_key')
                ]
            ]);
        }
        catch (TransferException $e) {
            report($e);
        }

        return json_decode($response);
    }
}