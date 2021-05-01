<?php

if (!function_exists('dump')) {
    function dump($subject)
    {
        echo '<pre>';
        print_r($subject);
        echo '</pre>';
        die();
    }
}

if (!function_exists('kDump')) {
    function kDump($subject)
    {
        if ($subject instanceof Throwable) {
            $class = get_class($subject);

            $echo = <<<EOF
            \033[01;37;41m Exception: {$class} \033[0m

            \033[00;36mMessage:\033[0m \033[00;32m{$subject->getMessage()}\033[0m

            \033[01;37;45m Stack Trace: \033[0m

            \033[00;33m{$subject->getTraceAsString()}\033[0m
            EOF;
            echo "$echo\n";
            die();
        } else {
            $dump = print_r($subject, true);
            $echo = <<<EOF
            \033[01;32m{$dump}\033[0m
            EOF;
            echo "$echo\n";
        }
        die();
    }
}

if (!function_exists('src')) {

    function src(string $path)
    {
        $_ = DIRECTORY_SEPARATOR;
        return __DIR__ . "$_..$_..$_..$_..$_..$_$path";
    }
}

if (!function_exists('push_download')) {
    function push_download(string $tempPath, array $files)
    {

        $zipName = $tempPath . md5(date('Y-m-d H:i:s:u')) . '.tar.gz';

        $command = array_reduce($files, fn ($acc, $curr) => $acc.' '.basename($curr), "tar -czf $zipName -C $tempPath");

        exec($command, $output, $returnCode);

        if ($returnCode == 0) {
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Type: application/octet-stream');

            header('Content-Disposition: attachment; filename="' . basename($zipName) . '";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($zipName));

            readfile($zipName);
        }

        unlink($zipName);
    }
}

if (!function_exists('concrete_of')) {
    /**
     * @param string|object $class
     * @param string $interface
     * @return bool
     */
    function concrete_of($class, string $interface): bool
    {
        return in_array($interface, class_implements($class));
    }
}
