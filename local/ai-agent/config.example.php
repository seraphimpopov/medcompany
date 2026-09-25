<?php

// Шаблон config.php. Настоящий config.php лежит только на сервере (в git не попадает).

return [
    "IBLOCK_ID" => 16,

    // External AI gateway, for example: https://ai.example.com/classify
    "AI_GATEWAY_URL" => "http://169.40.3.157:8088/classify",
    "AI_GATEWAY_TOKEN" => "CHANGE_ME",

    "LIMIT" => 10,
    "MIN_CONFIDENCE" => 0.78,

    // false = заполненные поля не трогаем
    "OVERWRITE_FILLED_FIELDS" => false,

    "PROPS" => [
        "BIND" => "ATT_TOVAR",
        "VIEW" => "ATT_VIEW",
        "TYPE" => "ATT_TYPE",
        "TEXT" => "ATT_TEXT",
    ],

    "ARTICLE_PROP" => "CML2_ARTICLE",

    // group_first_product_id = в ATT_TOVAR записываем ID первого товара группы.
    // ai_key = в ATT_TOVAR записываем текстовый ключ, который вернул ИИ.
    "BIND_MODE" => "group_first_product_id",
];
