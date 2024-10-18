<?php
// Start session to track if the IP has voted
session_start();

// Get the voter's IP address
$ip_address = $_SERVER['REMOTE_ADDR'];
$voted_positions = $_SESSION['voted_positions'] ?? [];

// Positions available for voting
$positions = ['chair', 'secretary', 'treasurer', 'committee', 'discipline'];

// Ensure the user hasn't voted for all positions
foreach ($positions as $position) {
    if (in_array($position, $voted_positions)) {
        echo json_encode(['success' => false, 'message' => 'You have already voted on ' . $position . '.']);
        exit;
    }
}

// Collect the vote data
$chair = $_POST['chair'] ?? null;
$secretary = $_POST['secretary'] ?? null;
$treasurer = $_POST['treasurer'] ?? null;
$committee = $_POST['committee'] ?? [];
$discipline = $_POST['discipline'] ?? [];

// Ensure a vote was cast for each required position
if (!$chair || !$secretary || !$treasurer) {
    echo json_encode(['success' => false, 'message' => 'Please vote for all required positions.']);
    exit;
}

// Block the IP from voting again
$_SESSION['voted_positions'][] = 'chair';
$_SESSION['voted_positions'][] = 'secretary';
$_SESSION['voted_positions'][] = 'treasurer';
$_SESSION['voted_positions'][] = 'committee';
$_SESSION['voted_positions'][] = 'discipline';

// You would typically store these votes in a database, here we're just showing the flow
file_put_contents('votes.txt', "$ip_address voted:\nChair: $chair\nSecretary: $secretary\nTreasurer: $treasurer\nCommittee: " . implode(', ', $committee) . "\nDiscipline: " . implode(', ', $discipline) . "\n\n", FILE_APPEND);

echo json_encode(['success' => true]);

?>
