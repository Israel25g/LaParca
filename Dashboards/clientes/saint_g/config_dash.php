<?php  

return [
  'db' => [
    'host' => 'localhost',
    'user' => 'u366386740_IPLGroup',
    'pass' => '1plGr0up01*',
    'name' => 'u366386740_dataWarehouse',
    'options' => [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    ]
  ]
];

?>