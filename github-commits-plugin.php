<?php
/*
Plugin Name: GitHub Commits Plugin 
Plugin URI: https://github.com/migbenwd/github-commits-plugin/
Description: Show the commit history of a public GitHub repository in the WordPress admin menu.
Version: 1.0
Author: Miguel Benitez
Author URI: https://migben.com
License: GPLv2 or later
*/


define('GITHUB_API_URL', 'https://api.github.com');
define('GITHUB_REPOSITORY_OWNER', 'migbenwd');
define('GITHUB_REPOSITORY_NAME', 'github-commits-plugin');

add_action('admin_menu', 'github_commit_history_add_menu');

function github_commit_history_add_menu() {
    add_menu_page('GitHub Commit History', 'GitHub Commit History', 'manage_options', 'github-commit-history', 'github_commit_history_display');
}


function github_commit_history_display() {
    $commits = get_github_commits();
    if ($commits) {
        ?>
		
	<style>
    table, th, td {
	border: 1px solid black;
	}

	thead {
        background-color: #007bff;
        color: white;
      }

    </style>
	<h1>Historial Commits From My Own Repository</h1> 
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Message</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commits as $commit) : ?>
                    <tr>
                        <td><?php echo $commit['commit']['author']['date']; ?></td>
                        <td><?php echo $commit['commit']['message']; ?></td>
                        <td><?php echo $commit['commit']['author']['name']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    } else {
        echo 'there is no commits in the repository.';
    }
}


function get_github_commits() {
	$url = GITHUB_API_URL . '/repos/' . GITHUB_REPOSITORY_OWNER . '/' . GITHUB_REPOSITORY_NAME . '/commits';
	$response = wp_remote_get($url);
	if (is_wp_error($response)) {
		return false;
	} else {
		$body = json_decode($response['body'], true);
		return $body;
	}
}
