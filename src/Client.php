<?php

namespace Jordywolk\Ts3api;


use http\Exception\UnexpectedValueException;

/**
 * Class Client
 * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf TS3 Server Query documentation
 * @package Jordywolk\Ts3api
 */
class Client extends ServerConnection
{
    /**
     * Client constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Client destructor
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Displays a list of clients online on a virtual server including their ID, nickname, status flags, etc.
     *
     * The output can be modified using several command options: -uid, -away, -voice, -times, -groups, -info, -icon, -country
     *
     * @param array parameters Command options
     *
     * @return mixed json containing online clients
     */
    public function list(array $parameters = null)
    {
        $command = "clientlist";

        if($parameters != null) {
            $command .= "?";

            foreach ($parameters as $parameter) {
                $command .= $parameter . "&";
            }
        }
        $command = rtrim($command, "&");

        return $this->send_command($command);
    }

    /**
     * Displays detailed configuration information about a client including unique ID, nickname, client version, etc.
     *
     * @param int client_id Client info is requested of
     *
     * @return mixed json Server response
     */
    public function info(int $client_id)
    {
        return $this->send_command("clientinfo", array("clid" => $client_id));
    }

    /**
     * Displays a list of clients matching a given name pattern.
     *
     * @param string $pattern String to be searched for
     *
     * @return mixed json List of clients
     */
    public function find(string $pattern)
    {
        return $this->send_command("clientfind", array("pattern" => $pattern));
    }

    /**
     * Changes a clients settings using given properties.
     *
     * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf#page=45 Only properties marked as changeble are allowed
     * @todo Implement filter to only allow these properties
     * @param int $client_id ID of client to be edited
     * @param array $properties Properties and their new values
     * @todo Check datatype of passed properties against expected type
     *
     * @return mixed json Server response
     */
    public function edit(int $client_id, array $properties)
    {
        $properties = array("clid" => $client_id) + $properties;
        return $this->send_command("clientedit", $properties);
    }

    /**
     * Displays a list of client identities known by the server including their database ID, last nickname, etc.
     *
     * @return mixed json List of clients and their details
     */
    public function db_list()
    {
        return $this->send_command("clientdblist");
    }

    /**
     * Displays detailed database information about a client including unique ID, creation date, etc.
     *
     * @param int $client_db_id ID of client info is requested of
     *
     * @return mixed json Client details
     */
    public function db_info(int $client_db_id)
    {
        return $this->send_command("clientdbinfo", array("clientdbid" => $client_db_id));
    }

    /**
     * Displays a list of client database IDs matching a given name.
     *
     * @param string $client_name Name or partial name to be searched for
     *
     * @return mixed json List of clients
     */
    public function db_find_by_name(string $client_name)
    {
        return $this->send_command("clientdbfind", array("pattern" => $client_name));
    }

    /**
     * Displays a list of client database IDs matching a given unique id.
     *
     * @todo Add -uid to command string. This shouldn't return anything useful without it
     * @param string $client_uid ID or partial ID to be searched for
     *
     * @return mixed json List of clients
     */
    public function db_find_by_uid(string $client_uid)
    {
        return $this->send_command("clientdbfind", array("pattern" => $client_uid));
    }

    /**
     * Changes a clients settings using given properties
     *
     * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf#page=45 Only properties marked as changeble are allowed
     * @todo Implement a filter to only allow these properties
     * @param int $client_db_id Database ID of client to be edited
     * @param array $properties Properties and their values to be edited
     * @todo Check datatype of passed properties against expected type
     *
     * @return mixed json Server response
     */
    public function db_edit(int $client_db_id, array $properties)
    {
        $properties = array("cldbid" => $client_db_id) + $properties;
        return $this->send_command("clientdbedit", $properties);
    }

    /**
     * Deletes a clients properties from the database.
     *
     * @param int $client_db_id Database ID of client to be deleted
     *
     * @return mixed json Server response
     */
    public function db_delete(int $client_db_id)
    {
        return $this->send_command("clientdbdelete", array("cldbid" => $client_db_id));
    }

    /**
     * Displays all client IDs matching the unique identifier specified by client_uid.
     *
     * @param string $client_uid Unique ID or partial unique ID to be searched for
     * @todo: Check if partial ID is allowed
     *
     * @return mixed json List of client IDs
     */
    public function get_ids(string $client_uid)
    {
        return $this->send_command("clientgetids", array("cluid" => $client_uid));
    }

    /**
     * Displays the database ID matching the unique identifier specified by client_uid.
     *
     * @param string $client_uid Unique ID or partial unique ID to be searched for
     * @todo Check if partial ID is allowed
     *
     * @return mixed json List of client database IDs
     */
    public function get_db_id_from_uid(string $client_uid)
    {
        return $this->send_command("clientgetdbidfromuid", array("cluid" => $client_uid));
    }

    /**
     * Displays the database ID and nickname matching the unique identifier specified by client_uid.
     *
     * @param string $client_uid Unique ID or partial unique ID to be searched for
     * @todo Check if partial ID is allowed
     *
     * @return mixed json List of client names
     */
    public function get_name_from_uid(string $client_uid)
    {
        return $this->send_command("clientgetnamefromuid", array("cluid" => $client_uid));
    }

    /**
     * Displays the unique identifier and nickname matching the database ID specified by client_db_id.
     *
     * @param int $client_db_id Database ID of client to be searched for
     *
     * @return mixed json Unique ID and name of client
     */
    public function get_name_from_db_id(int $client_db_id)
    {
        return $this->send_command("clientgetnamefromdbid", array("cldbid" => $client_db_id));
    }

    /**
     * Updates your own ServerQuery login credentials using a specified username. The password will be autogenerated.
     * This function will most likely create a server query admin account. Use this function carefully!
     *
     * @todo Make sure password is handled securely, or remove this function from the package completely.
     *
     * @param string $username Username of the account to be created
     *
     * @return mixed json Server response
     */
    public function set_server_query_login(string $username)
    {
        return $this->send_command("clientsetserverquerylogin", array("client_login_name" => $username));
    }

    /**
     * Change your ServerQuery clients settings using given properties.
     *
     * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf#page=45 Only properties marked as changeble are allowed
     * @todo Implement a filter to only allow these properties
     * @param array $properties Properties and their values to be edited
     * @todo Check datatype of passed properties against expected type
     *
     * @return mixed json Server response
     */
    public function update(array $properties)
    {
        return $this->send_command("clientupdate", $properties);
    }

    /**
     * Moves client specified with client_id to the channel with ID channel_id. If the target channel has a password, it needs to be specified with cpw. If the channel has no password, the parameter can be omitted.
     * @todo The API most likely has admin access and doesn't need a password. Has to be verified to be sure.
     *
     * @param int $client_id ID of client to be moved
     * @param int $channel_id ID of channel the client will be moved to
     *
     * @return mixed json Server response
     */
    public function move(int $client_id, int $channel_id)
    {
        return $this->send_command("clientmove", array("clid" => $client_id, "cid" => $channel_id));
    }

    /**
     * Kicks one or more clients specified with clid from their currently joined channel or from the server, depending on reasonid.
     * The reasonmsg parameter specifies a text message sent to the kicked clients.
     * This parameter is optional and may only have a maximum of 40 characters.
     *
     * @todo Implement character limit on reason
     *
     * @param int $client_id ID of client to be kicked
     * @param int $type Type of kick: From channel (4) or from server (5)
     * @see https://lethallhost.com.br/clientes/TeamSpeak%203%20Server%20Query%20Manual.pdf#page=47 ENUM definition of types
     * @param string $reason String containing the reason of the kick. Has a maximum length of 40 characters
     *
     * @throws UnexpectedValueException Unexpected value passed to function
     *
     * @return mixed json Server response
     */
    public function kick(int $client_id, int $type, string $reason="")
    {
        if($type != 4 || $type != 5) {
            throw new UnexpectedValueException("Type must be 4 or 5");
        }
        return $this->send_command("clientkick", array("clid" => $client_id, "reasonid" => $type, "reasonmsg" => $reason));
    }

    /**
     * Sends a poke message to the client specified with clid.
     *
     * @todo Check max message length. Probably is 40.
     *
     * @param int $client_id ID of client to be poked
     * @param string $message String containing the poke message
     * @return mixed json Server response
     */
    public function poke(int $client_id, string $message)
    {
        return $this->send_command("clientpoke", array("clid" => $client_id, "msg" => $message));
    }

    /**
     * Displays a list of permissions defined for a client.
     *
     * @param int $client_db_id Database ID of client to be searched for
     * @return mixed json Permissions and their values assigned to user
     */
    public function perm_list(int $client_db_id)
    {
        return $this->send_command("clientpermlist", array("cldbid" => $client_db_id));
    }

    /**
     * Adds specified permission to a client.
     *
     * @param int $client_db_id Database of client the permission should be assigned to
     * @param int $permission_id ID of the permission to be assigned
     * @param int $permission_value Value of the permission
     * @param bool $skip Option to override channel group permission (1) or let the channel group override this permission (0)
     * @return mixed json Server response
     */
    public function add_perm_by_id(int $client_db_id, int $permission_id, int $permission_value, bool $skip)
{
    return $this->send_command("clientaddperm", array("cldbid" => $client_db_id, "permid" => $permission_id, "permvalue" => $permission_value, "permskip" => $skip));
}

    /**
     * Adds specified permission to a client.
     *
     * @param int $client_db_id Database of client the permission should be assigned to
     * @param int $permission_name Name of the permission to be assigned
     * @param int $permission_value Value of the permission
     * @param bool $skip Option to override channel group permission (1) or let the channel group override this permission (0)
     * @return mixed json Server response
     */
    public function add_perm_by_name(int $client_db_id, string $permission_name, int $permission_value, bool $skip)
    {
        return $this->send_command("clientaddperm", array("cldbid" => $client_db_id, "permsid" => $permission_name, "permvalue" => $permission_value, "permskip" => $skip));
    }

    /**
     * Removes a specified permission from a client.
     *
     * @param int $client_db_id Database of client the permission should be removed from
     * @param int $permission_id ID of the permission to be removed
     * @return mixed json Server response
     */
    public function del_perm_by_id(int $client_db_id, int $permission_id)
    {
        return $this->send_command("clientdelperm", array("cldbid" => $client_db_id, "permid" => $permission_id));
    }

    /**
     * Removes a specified permission from a client.
     *
     * @param int $client_db_id Database of client the permission should be removed from
     * @param int $permission_name Name of the permission to be removed
     * @return mixed json Server response
     */
    public function del_perm_by_name(int $client_db_id, string $permission_name)
    {
        return $this->send_command("clientdelperm", array("cldbid" => $client_db_id, "permsid" => $permission_name));
    }
}