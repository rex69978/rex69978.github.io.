<?php
// Start session and handle votes
session_start();

// Define positions and candidates
$positions = [
    'chair' => ['Mrs. R. Kaliwata', 'Mrs. E. Ndhlove'],
    'secretary' => ['Mrs. K. Mkambeni', 'Mr. R. Jose'],
    'treasurer' => ['Mr. K. Chakwanira', 'Ms. M. Gwejula'],
    'committee' => ['Mr. F. Gwejula', 'Mrs. S. Nkhuwa', 'Ms. M. Ngozo'],
    'discipline' => ['Mrs. T. Jose', 'Ms. L. Chakwanira', 'Mr. D. Kaliwata', 'Ms. F. Jumbe']
];

// Initialize vote file if it doesn't exist
$vote_file = 'vote_counts.txt';
if (!file_exists($vote_file)) {
    $initial_data = [];
    foreach ($positions as $position => $candidates) {
        $initial_data[$position] = array_fill_keys($candidates, 0);
    }
    file_put_contents($vote_file, json_encode($initial_data));
}

// Load current vote counts
$votes = json_decode(file_get_contents($vote_file), true);

// Get the voter's IP address and anonymize it with a hash
$ip_address = $_SERVER['REMOTE_ADDR'];
$ip_hash = hash('sha256', $ip_address . 'your_secret_salt'); // Salt ensures more secure hashing

// Check if the user has already voted
$voted_ips = json_decode(file_get_contents('voted_ips.txt'), true) ?? [];
if (in_array($ip_hash, $voted_ips)) {
    $already_voted = true;
} else {
    $already_voted = false;
}

// Handle the voting submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_voted) {
    // Collect and validate votes
    $chair = $_POST['chair'] ?? null;
    $secretary = $_POST['secretary'] ?? null;
    $treasurer = $_POST['treasurer'] ?? null;
    $committee = $_POST['committee'] ?? [];
    $discipline = $_POST['discipline'] ?? [];

    if ($chair && $secretary && $treasurer) {
        // Update the vote counts
        $votes['chair'][$chair]++;
        $votes['secretary'][$secretary]++;
        $votes['treasurer'][$treasurer]++;
        
        foreach ($committee as $candidate) {
            $votes['committee'][$candidate]++;
        }
        foreach ($discipline as $candidate) {
            $votes['discipline'][$candidate]++;
        }

        // Save updated vote counts
        file_put_contents($vote_file, json_encode($votes));

        // Mark IP as voted by storing the hashed IP in voted_ips.txt
        $voted_ips[] = $ip_hash;
        file_put_contents('voted_ips.txt', json_encode($voted_ips));

        // Indicate successful voting
        $voting_success = true;
    } else {
        $voting_error = "You must vote for all required positions.";
    }
}

// Function to display the current vote count
function display_vote_counts($votes) {
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
    <script>
        function submitVote() {
            const formData = new FormData(document.getElementById('votingForm'));
            fetch('', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.body.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body>

<h1>Family Clan Leadership Voting</h1>

<?php if (!$already_voted): ?>

<form id="votingForm">
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

    <button type="button" onclick="submitVote()">Submit Vote</button>
</form>

<?php else: ?>
    <p>You have already voted. Thank you for participating!</p>
<?php endif; ?>

<?php
// Show success message if voting was successful
if (isset($voting_success)) {
    echo "<p>Thank you for voting! Your vote has been recorded.</p>";
}

// Show error if there was an issue with voting
if (isset($voting_error)) {
    echo "<p>Error: $voting_error</p>";
}

// Display the current vote counts
display_vote_counts($votes);
?>

</body>
</html>
