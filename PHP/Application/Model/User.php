<?php

namespace Application\Model;

use Core\Database\Model;

/**
 * The User
 * Class User
 */
class User extends Model implements \JsonSerializable {


    /**
     *
     * @var string email of the User
     * @column(string)
     */
    public $email;


    /**
     * @var string display name of this user
     * @column(string)
     */
    public $displayName;

    /**
     * @var string password of the User
     * @column(string)
     */
    public $password;

    /**
     * @var boolean if user is validated
     * @column(int)
     */
    public $validated;

    /**
     * @var string validate token that send to user's email
     * @column(string)
     */
    public $validateToken;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return array(
                "id" => intval($this->id),
                "email" => $this->email,
                "displayName" => $this->displayName,
                "validated" => boolval($this->validated),
            );
    }
}