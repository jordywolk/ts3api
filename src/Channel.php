<?php

namespace Jordywolk\Ts3api;


use http\Exception\UnexpectedValueException;

/**
 * Class Channel
 * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf TS3 Server Query documentation
 * @package Jordywolk\Ts3api
 */
class Channel extends ServerConnection
{
    /**
     * Channel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Channel destructor
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    public function client_permission_list(int $channel_id, int $client__db_id)
    {
        return $this->send_command("channnelclientpermlist", array("cid" => $channel_id, "cldbid" => $client_db_id));
    }

    public function client_add_permission_by_id(int $channel_id, int $client_db_id, int $permission_id, int $permission_value)
    {
        return $this->send_command("channelclientaddperm", array("cid" => $channel_id, "cldbid" => $client_db_id, "permid" => $$permission_id, "permvalue" => $permission_value));
    }

    public function client_add_permission_by_name(int $channel_id, int $client_db_id, int $permission_name, int $permission_value)
    {
        return $this->send_command("channelclientaddperm", array("cid" => $channel_id, "cldbid" => $client_db_id, "permname" => $permission_name, "permvalue" => $permission_value));
    }

    public function client_delete_permission_by_id(int $channel_id, int $client_db_id, int $permission_id)
    {
        return $this->send_command("channelclientdelperm", array("cid" => $channel_id, "cldbid" => $client_db_id, "permid" => $$permission_id));
    }

    public function client_delete_permission_by_name(int $channel_id, int $client_db_id, int $permission_name)
    {
        return $this->send_command("channelclientdelperm", array("cid" => $channel_id, "cldbid" => $client_db_id, "permname" => $permission_name));
    }

    public function list()
    {
        return $this->send_command("channellist");
    }

    public function info(int $channel_id)
    {
        return $this->send_command("channelinfo", array("cid" => $channel_id));
    }

    public function find(string $chanel_name)
    {
        return $this->send_command("channelfind", array("pattern" => $channel_name));
    }

    public function move(int $channel_id, int $parent_channel_id, int $sort_order = 0)
    {
        return $this->send_command("channelclientdelperm", array("cid" => $channel_id, "cpid" => $parent_channel_id, "order" => $sort_order));
    }

    public function create(string $channel_name, string $channel_topic, string $channel_description, int $parent_channel, bool $is_permanent)
    {
        return $this->send_command("channelcreate", array("channel_name" => $channel_name, "channel_topic" => $channel_topic, "channel_description" => $channel_description, "cpid" => $parent_channel, "channel_flag_permanent" => $is_permanent));
    }

    public function delete(int $channel_id)
    {
        return $this->send_command("channeldelete", array("cid" => $channel_id));
    }
}