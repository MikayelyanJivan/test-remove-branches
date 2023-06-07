<?php
$branchesToPreserve  = ['development', 'staging', 'pre-production', 'production', 'master']; // Replace with the names of the branches to exclude
$branchNamePrefix = 'feat/'; // Replace with the desired branch name prefix
$remoteName = 'origin';

// Execute the Git command to retrieve the remote branch list
$command = "git ls-remote --heads $remoteName";
$output = shell_exec($command);

// Process the output to get the remote branch names
$branches = explode("\n", $output);
$branches = array_map('trim', $branches);
$branches = array_filter($branches);

//  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

// Iterate over the remote branches and delete the ones that match the criteria
foreach ($branches as $branch) {
    // Extract the branch name from the output
    $branchName = explode('refs/heads/', $branch)[1];

    // Check if the branch starts with the desired prefix
    if (strpos($branchName, $branchNamePrefix) === 0 && !in_array($branchName, $branchesToPreserve)) {
        // Execute the Git command to delete the remote branch
        $command = "git push $remoteName --delete $branchName";
        $output = shell_exec($command);

        // Check the output for any error messages
        if (strpos($output, 'error:') !== false) {
            echo "Error: Failed to delete the remote branch '$branchName'.";
        } else {
            echo "Remote branch '$branchName' has been deleted successfully.";
        }
    }
}
?>
