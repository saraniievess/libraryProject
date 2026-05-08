<?php

namespace database;

interface model {

    /**
     * 
     */
    public function get_tablename() : string;

    /**
     *@return list<string>
     */
    public function get_field_list() : array;

    /**
     *@return array<string, mixed>
     */
    public function get_statement_args() : array;

    /**
     * 
     */
    public function set_id(int $id) : void;

    /**
     * 
     */
    public function get_id() : int;
}