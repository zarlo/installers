<?php

function bash_comment($string)
{
    $hash = md5($string);
    return "<< ////$hash\n $string \n ////$hash";
}

$debain = [
    "12" => "Bookworm",
    "11" => "Bullseye",
    "10" => "Buster",
    "9"  => "Stretch",
    "8"  => "Jessie"
];

$ubuntu = [
    "20_04" => "Focal Fossa",
    "19_10" => "Eoan Ermine",
    "19_04" => "Disco Dingo",
    "18_04" => "Bionic Beaver",
    "16_04" => "Xenial Xerus",
    "14_04" => "Trusty Tahr",
];

return [
    "codename" => [
        "debain" => $debain,
        "ubuntu" => $ubuntu
    ],
    "license" => bash_comment(file_get_contents(__DIR__ . '/../LICENSE'))
];