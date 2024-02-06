<?php

$file = fopen("TesteAlunos.csv", "r");

while ($data = fgetcsv($file, 1000, ';'))
	print_r($data);