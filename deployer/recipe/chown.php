<?php

namespace Deployer;

// chown
desc('To change both the owner and the group');
task('deploy:owner', function () {
    $dirs = join(' ', get('chown_dirs'));
    $httpUser = get('http_user', false);
    $httpGroup = get('http_group', false);
    $sudo = get('chown_use_sudo') ? 'sudo' : '';
    $runOpts = [];
    if ($sudo) {
        $runOpts['tty'] = get('chown_tty', false);
    }
    if ($httpUser === false) {
        // Attempt to detect http user in process list.
        $httpUserCandidates = explode("\n", run("ps axo comm,user | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | sort | awk '{print \$NF}' | uniq"));
        if (count($httpUserCandidates)) {
            $httpUser = array_shift($httpUserCandidates);
        }

        if (empty($httpUser)) {
            throw new \RuntimeException(
                "Can't detect http user name.\n" .
                "Please setup `http_user` config parameter."
            );
        }
    } elseif ($httpGroup) {
        $httpUser .= ':'.$httpGroup;
    }
    try {
        $recursive = get('chown_recursive') ? '-R' : '';
        run("$sudo chown $recursive $httpUser $dirs", $runOpts);
    } catch (\RuntimeException $e) {
        $formatter = Deployer::get()->getHelper('formatter');

        $errorMessage = [
            "Unable to setup correct permissions for writable dirs.                  ",
            "You need to configure sudo's sudoers files to not prompt for password,",
            "or setup correct permissions manually.                                  ",
        ];
        write($formatter->formatBlock($errorMessage, 'error', true));

        throw $e;
    }
});
