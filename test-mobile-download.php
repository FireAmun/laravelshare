<?php
/**
 * Test script to debug mobile download issues
 */

// Simulate mobile user agent
$mobileUserAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Mobile/15E148 Safari/604.1';

echo "Mobile Download Test\n";
echo "===================\n\n";

// Test 1: Check if we can detect mobile user agent
echo "1. Mobile User Agent Detection:\n";
$userAgent = $mobileUserAgent;
$isMobile = preg_match('/Mobile|Android|iPhone|iPad/', $userAgent);
echo "User Agent: $userAgent\n";
echo "Is Mobile: " . ($isMobile ? 'YES' : 'NO') . "\n\n";

// Test 2: Check file path construction
echo "2. File Path Construction:\n";
$storagePath = 'uploads/2024/12/test-file.pdf';
$fullPath = __DIR__ . '/storage/app/public/' . $storagePath;
echo "Storage Path: $storagePath\n";
echo "Full Path: $fullPath\n";
echo "File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n\n";

// Test 3: Check mime type handling
echo "3. MIME Type Handling:\n";
$fileName = 'test-file.pdf';
$mimeType = 'application/pdf';
$cleanedFileName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $fileName);
echo "Original: $fileName\n";
echo "Cleaned: $cleanedFileName\n";
echo "MIME Type: $mimeType\n\n";

// Test 4: Headers construction
echo "4. Headers Construction:\n";
$headers = [
    'Content-Type' => $mimeType ?: 'application/octet-stream',
    'Content-Length' => 12345,
    'Content-Disposition' => 'attachment; filename="' . $cleanedFileName . '"',
    'Cache-Control' => 'no-cache, no-store, must-revalidate',
    'Pragma' => 'no-cache',
    'Expires' => '0',
];

if ($isMobile) {
    $headers['X-Content-Type-Options'] = 'nosniff';
    $headers['X-Download-Options'] = 'noopen';
}

foreach ($headers as $key => $value) {
    echo "$key: $value\n";
}

echo "\nTest completed!\n";
