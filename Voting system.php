<?php
session_start();

// Prevent re-voting based on IP check
$ip_file = 'ips.json';
$user_ip = $_SERVER['REMOTE_ADDR'];

// Load voted IPs
$voted_ips = file_exists($ip_file) ? json_decode(file_get_contents($ip_file), true) : [];
$already_voted = in_array($user_ip, $voted_ips);
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

<form action="submit_vote.php" method="POST">
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

<a href="results.php">View Current Vote Results</a>

</body>
</html>
