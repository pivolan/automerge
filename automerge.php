<?php
namespace Pivolan;
/**
 * Created by PhpStorm.
 * User: igorpecenikin
 * Date: 24/09/14
 * Time: 02:15
 */
const BRANCH = 'prod_rack';
const KEY_WORD = '#complete';

chdir(__DIR__);
exec("git fetch", $output, $return);
var_dump($return);
print_r($output);
unset($output);
exec("git log --abbrev-commit --pretty=oneline ..refs/remotes/origin/" . BRANCH, $gitLog, $return);
var_dump($return);
print_r($gitLog);
exec("git checkout " . BRANCH, $output, $return);
var_dump($return);
print_r($output);
unset($output);
exec("git pull --rebase", $output, $return);
var_dump($return);
print_r($output);
$completeCommits = [];
if (is_array($gitLog)) {
    foreach ($gitLog as $string) {
        if (strpos($string, KEY_WORD) !== false) {
            $string_parts = explode(' ', $string);
            if (isset($string_parts[0])) {
                $commitHash = $string_parts[0];
                $mergeOutput = [];
                exec("git merge --no-ff --no-edit " . $commitHash, $mergeOutput, $return);
                var_dump($return);
                print_r($mergeOutput);

                if ($return !== 0) {
                    exec("git reset --hard ", $output, $return);
                    var_dump($return);
                    print_r($output);
                } elseif ($mergeOutput[0] != 'Already up-to-date.') {
                    exec("git push", $output, $return);
                    var_dump($return);
                    print_r($output);
                }
            }
        }
    }
}