<?php

function get_user(callable $get_db_data, $column, $value) {
    return $get_db_data('users', $column, $value);
}