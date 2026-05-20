<?php

declare(strict_types=1);

require_once("auth.php");

logout();

header('Location: index.php');

exit(0);
