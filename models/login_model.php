<?php

function get_user(callable $get_db_data, $conditions) {
    return $get_db_data('users', $conditions, true);
}