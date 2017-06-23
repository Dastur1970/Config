<?php

return [
    "someint" => 123,

    "somestring" => "string",

    "someArray" => [
        "boolean" => true,

        "anotherArray" => [
            "functionCall" => str_replace(".", ",", "test.test.tester"),
            "somethingNull" => null
        ]
    ]
];
