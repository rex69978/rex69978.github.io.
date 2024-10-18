<?php
session_start();

// Files for storing votes and IPs
$vote_file = 'votes.json';
$ip_file = 'ips.json';

// Positions and candidates
$positions = [
    'chair' => ['Mrs. R. Kaliwata', 'Mrs. E. Ndhlove'],
    'secretary' => ['Mrs. K. Mkambeni', 'Mr. R. Jose'],
    'treasurer' => ['Mr. K. Chakwanira', 'Ms. M. Gwejula'],
    'committee' => ['Mr. F. Gwejula', 'Mrs. S. Nkhuwa', 'Ms. M. Ngozo'],
    'discipline' => ['Mrs. T. Jose', 'Ms. L. Chakwanira', 'Mr. D. Kaliwata', 'Ms. F. Jumbe']
];

// Get user's IP
$user_ip = $_SERVER['REMOTE_ADDR'];

// Load vote counts
if (!file_exists($vote_file)) {
    $votes = [];
    foreach ($positions as $position => $candidates) {
        $votes[$position] = array_fill_keys($candidates, 0);
    }
    file_put_contents($vote_file, json_encode($votes));
} else {
    $votes = json_decode(file_get_contents($vote_file), true);
}

// Load voted IPs
$voted_ips = file_exists($ip_file) ? json_decode(file_get_contents($ip_file), true) : [];

// Check if the user has already voted
if (in_array($user_ip, $voted_ips)) {
    $already_voted = true;
} else {
    $already_voted = false;
}

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_voted) {
    // Collect votes
    $chair = $_POST['chair'] ?? null;
    $secretary = $_POST['secretary'] ?? null;
    $treasurer = $_POST['treasurer'] ?? null;
    $committee = $_POST['committee'] ?? [];
    $discipline = $_POST['discipline'] ?? [];

    // Validate votes (ensure all required positions are voted)
    if ($chair && $secretary && $treasurer) {
        // Update vote counts
        $votes['chair'][$chair]++;
        $votes['secretary'][$secretary]++;
        $votes['treasurer'][$treasurer]++;
        foreach ($committee as $person) {
            $votes['committee'][$person]++;
        }
        foreach ($discipline as $person) {
            $votes['discipline'][$person]++;
        }

        // Save updated votes and mark the user as having voted
        file_put_contents($vote_file, json_encode($votes));
        $voted_ips[] = $user_ip;
        file_put_contents($ip_file, json_encode($voted_ips));

        // Indicate successful voting
        $vote_success = true;
    } else {
        $vote_error = "Please vote for all required positions.";
    }
}

// Function to display the current vote counts
function display_votes($votes) {
    echo "<h2>Current Vote Counts</h2>";
    foreach ($votes as $position => $candidates) {
        echo "<h3>" . ucfirst($position) . "</h3><ul>";
        foreach ($candidates as $candidate => $count) {
            echo "<li>$candidate: $count votes</li>";
        }
        echo "</ul>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Clan Leadership Voting</title>
</head>
<body>

<h1>Family Clan Leadership Voting</h1>

<?php if (!$already_voted): ?>

    <!-- Display Voting Form -->
    <form method="POST">
        <!-- Chair Vote -->
        <label>Chair:</label><br>
        <input type="radio" name="chair" value="Mrs. R. Kaliwata" required> Mrs. R. Kaliwata<br>
        <input type="radio" name="chair" value="Mrs. E. Ndhlove"> Mrs. E. Ndhlove<br><br>

        <!-- Secretary Vote -->
        <label>Secretary:</label><br>
        <input type="radio" name="secretary" value="Mrs. K. Mkambeni" required> Mrs. K. Mkambeni<br>
        <input type="radio" name="secretary" value="Mr. R. Jose"> Mr. R. Jose<br><br>

        <!-- Treasurer Vote -->
        <label>Treasurer:</label><br>
        <input type="radio" name="treasurer" value="Mr. K. Chakwanira" required> Mr. K. Chakwanira<br>
        <input type="radio" name="treasurer" value="Ms. M. Gwejula"> Ms. M. Gwejula<br><br>

        <!-- Committee Vote -->
        <label>Committee:</label><br>
        <input type="checkbox" name="committee[]" value="Mr. F. Gwejula"> Mr. F. Gwejula<br>
        <input type="checkbox" name="committee[]" value="Mrs. S. Nkhuwa"> Mrs. S. Nkhuwa<br>
        <input type="checkbox" name="committee[]" value="Ms. M. Ngozo"> Ms. M. Ngozo<br><br>

        <!-- Discipline Vote -->
        <label>Discipline:</label><br>
        <input type="checkbox" name="discipline[]" value="Mrs. T. Jose"> Mrs. T. Jose<br>
        <input type="checkbox" name="discipline[]" value="Ms. L. Chakwanira"> Ms. L. Chakwanira<br>
        <input type="checkbox" name="discipline[]" value="Mr. D. Kaliwata"> Mr. D. Kaliwata<br>
        <input type="checkbox" name="discipline[]" value="Ms. F. Jumbe"> Ms. F. Jumbe<br><br>

        <button type="submit">Submit Vote</button>
    </form>

<?php else: ?>
    <p>You have already voted. Thank you for participating!</p>
<?php endif; ?>

<?php
// Display vote success message
if (isset($vote_success)) {
    echo "<p>Your vote has been submitted successfully. Thank you!</p>";
}

// Display any errors during voting
if (isset($vote_error)) {
    echo "<p style='color:red;'>Error: $vote_error</p>";
}

// Display the current vote counts
display_votes($votes);
?>

</body>
</html>
