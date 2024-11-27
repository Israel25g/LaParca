<?php  
return [
  'db' => [
    'host' => 'localhost',
    'user' => 'u366386740_adminDP',
    'pass' => '1plGr0up01*',
    'name' => 'main_dash',
    'options' => [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    ]
  ]
];

?>