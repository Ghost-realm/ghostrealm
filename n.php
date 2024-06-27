<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMARC and SPF Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        input[type=text] {
            width: calc(100% - 12px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        pre {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>DMARC and SPF Checker</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Enter an email address:</label>
        <input type="text" id="email" name="email" required>
        <input type="submit" value="Check">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Invalid email address.</p>";
            exit;
        }
        
        // Extract domain from email
        $parts = explode('@', $email);
        $domain = trim(end($parts));

        // Fetch SPF record
        $spf_record = dns_get_record($domain, DNS_TXT);
        $spf = '';
        foreach ($spf_record as $record) {
            if (strpos($record['txt'], 'v=spf1') !== false) {
                $spf = $record['txt'];
                break;
            }
        }

        // Fetch DMARC record
        $dmarc_record = dns_get_record("_dmarc.$domain", DNS_TXT);
        $dmarc = '';
        foreach ($dmarc_record as $record) {
            if (strpos($record['txt'], 'v=DMARC1') !== false) {
                $dmarc = $record['txt'];
                break;
            }
        }

        // Display results
        echo "<h3>Results for $domain</h3>";
        if (!empty($spf)) {
            echo "<h4>SPF Record:</h4>";
            echo "<pre>$spf</pre>";
        } else {
            echo "<p>No SPF record found.</p>";
        }
        if (!empty($dmarc)) {
            echo "<h4>DMARC Record:</h4>";
            echo "<pre>$dmarc</pre>";
        } else {
            echo "<p>No DMARC record found.</p>";
        }
    }
    ?>
</body>
</html>
