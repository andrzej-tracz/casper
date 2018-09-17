<?php

function active($name) {

    if (\Illuminate\Support\Str::startsWith(\Request::route()->getName(), $name)) {
        return 'active';
    };

    return '';
}
