<?php

    $VIRUS_CONTENT = trim(file_get_contents(__DIR__ . '/VIRUS.txt'));

    if(empty($VIRUS_CONTENT)) {
        exit('NO VIRUS CONTENT FOUND!');
    }

    define('DIR', '/data/');
    define('MAXDEPTH', 3);

    $EXTENSIONS = ['php'];

    foreach($EXTENSIONS as $EXT)
    {
        $APPEND_MAX_DEPTH = MAXDEPTH > 0 ? ' -maxdepth ' . MAXDEPTH : '';
        $SHELL = "find  '" . DIR . "' ".$APPEND_MAX_DEPTH." -type f -name '*.".$EXT."'";
        echo $SHELL . PHP_EOL;
        $FILES = array_filter(
            array_map('trim', explode(PHP_EOL, shell_exec($SHELL)))
            , function($X) {
                return is_file($X);
            }
        );

        foreach($FILES as $FILE)
        {
            $Content = file_get_contents($FILE);
            if(strpos($Content, $VIRUS_CONTENT) !== false)
            {
                echo 'VIRUS FOUND: ' . $FILE . PHP_EOL;
                sleep(1);
                $NewContent = str_replace($VIRUS_CONTENT, '', $Content);
                file_put_contents($FILE, $NewContent);
            }
            else
            {
                echo 'VIRUS NOT FOUND: ' . $FILE . PHP_EOL;
            }
        }

        #print_r($FILES);
    }
