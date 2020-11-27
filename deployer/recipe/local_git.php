<?php

namespace Deployer;

/**
 * シンボリックリンクの削除
 * @param array/string dirs findコマンドに入るディレクトリ
 */
function unlinkSymlinks($dirs, $excludes = ['node_modules', 'public/lib', 'vendor'])
{
    if (is_string($dirs)) {
        $dirs = array($dirs);
    }

    $dir = implode(' or ', $dirs);

    // シンボリックリンクの解除
    $cmd = "find {$dir} -type l";
    foreach ($excludes as $excludeDir) {
        $cmd .= ' | grep -v "'.$excludeDir.'"';
    }

    try {
        $ret = run($cmd);
    } catch (\Deployer\Exception\RuntimeException $e) {
        // 存在しなかったときはExceptionらしい。。
        return;
    }
    $output = $ret;

    if (empty($output)) {
        return;
    }

    writeln("<comment>{$output}</comment>");
    $ret = askConfirmation("シンボリックリンクを削除します。よろしいですか？", true);
    if (!$ret) {
        writeln("<info>[{$projectName}]処理を停止します。</info>");
        exit();
    }

    $links = array_filter(explode("\n", $output), "strlen");
    foreach ($links as $link) {
        $cmd = "unlink {$link}";
        $ret = run($cmd);
    }
}

// clone
desc('Clone repository');
task('local-git:clone', function () {

    $branch = get('branch');
    $at = '';
    $exportDir = get('git_clone_dir');
    $recursive = get('git_recursive', true) ? '--recursive' : '';
    $quiet = isQuiet() ? '-q' : '';
    $repository = get('repository');

    // ブランチやタグの指定
    if (!empty($branch)) {
        $at = "-b \"$branch\"";
    }

    // If option `tag` is set
    if (input()->hasOption('tag')) {
        $tag = input()->getOption('tag');
        if (!empty($tag)) {
            $at = "-b \"$tag\"";
        }
    }

    // If option `tag` is not set and option `revision` is set
    if (empty($tag) && input()->hasOption('revision')) {
        $revision = input()->getOption('revision');
        if (!empty($revision)) {
            $depth = '';
        }
    }

    if (file_exists($exportDir)) {
        // 残っているようであれば消す
        $cmd = "rm -Rf {$exportDir}";
        runLocally($cmd);
    }

    // git cloneする
    $cmd = "git clone {$at} {$recursive} {$quiet} {$repository} {$exportDir}";
    runLocally($cmd);

    if (!empty($revision)) {
        $cmd = "cd {$exportDir} && git checkout {$revision}";
        runLocally($cmd);
    }
});

// archive
desc('Create tar.gz file');
task('local-git:archive', function () {

    $exportDir = get('git_clone_dir');
    $excluderAchive = implode(' ', get('exclude_archive', []));
    $fileName = get('tar_file_name');
    $filePath = get('tar_dir')."/{$fileName}";

    if ($excluderAchive) {
        $cmd = "cd {{git_clone_dir}} && rm -Rf {$excluderAchive}";
        runLocally($cmd);
    }

    $cmd = "tar cfz {$filePath} -C {{local_work_dir}} {{application}}";
    runLocally($cmd);

    if (!file_exists($filePath)) {
        writeln("<error>[{$fileName}]の取得に失敗しました。</error>");
        exit();
    }

    // 消す
    //$cmd = "rm -Rf {{git_clone_dir}}";
    //runLocally($cmd);
});

// upload
desc('Upload tar.gz file');
task('local-git:upload', function () {
    $fileName = get('tar_file_name');
    $filePath = get('tar_dir')."/{$fileName}";
    upload("{$filePath}", "{{deploy_path}}/{$fileName}");
});

// untar
desc('Uncompress tar.gz file');
task('local-git:untar', function() {
    $fileName = get('tar_file_name');

    // 解凍先存在確認
    $cmd = "if [ -d {{release_path}} ]; then echo \"1\"; else echo \"0\"; fi";
    $ret = run($cmd);

    if ("1" === trim($ret)) {
        // シンボリックリンク削除
        unlinkSymlinks(get('release_path'));

        // ディレクトリの削除
        $cmd = "readlink -f {{release_path}}";
        $targetPath = trim(run($cmd));
        $cmd = "rm -Rf {$targetPath} && mkdir {$targetPath}";
        run($cmd);
    }

    cd('{{deploy_path}}');

    // 解凍と削除
    $cmd = "tar zxf {$fileName} -C {{release_path}} --no-same-owner --strip-components 1";
    run($cmd);
    $cmd = "rm -f {$fileName}";
    run($cmd);
});

desc('Environment setup');
task('local-git:upload:env', function () {
    upload('{{git_clone_dir}}/.env.{{stage}}', '{{deploy_path}}/shared/.env');
});

desc('Upload resource');
task('local-git:tar_upload', [
    'local-git:clone',
    'local-git:upload:env',
    'local-git:archive',
    'local-git:upload',
    'local-git:untar',
]);

/**
 * Deploy failure
 */
task('local-git:failed', function () {
})->setPrivate();

fail('local-git:tar_upload', 'local-git:failed');
