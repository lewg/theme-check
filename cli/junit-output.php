<?php
#
# Create a JUnit style xml report from the HTML report for use in build systems
#

include('cli-includes.php');

$arguments = parseArgs($argv);

if (!array_key_exists(0, $arguments)) {
    die("Usage: junit-output.php [--threshold=0] /path/to/report.html\n");
} 

$required_threshold = 0;
if (array_key_exists("threshold", $arguments)) {
	$required_threshold = (int)$arguments["threshold"];
} 

$report_file = realpath($arguments[0]);

# Open the Report
$report_contents = file_get_contents($report_file);

# Parse out the Counts
$required_count = preg_match_all('/REQUIRED<\/span>/', $report_contents, $matches);

$failure = ($required_count > $required_threshold)?true:false;
$failures = ($failure)?1:0;

echo "<?xml version=\"1.0\"?>\n";
echo "<testsuite errors=\"$required_count\" failures=\"$failures\" name=\"Theme Tests\" tests=\"1\" timestamp=\"".microtime(true)."\">\n";
echo "<testcase name=\"countRequired\">\n";
if ($failure) {
	echo '<failure message="Required errors over threshold">';
	echo "Test returned $required_count 'REQUIRED' errors. Please check the report.";
	echo "</failure>\n";
}
echo "<system-out><![CDATA[]]></system-out>\n";
echo "<system-err><![CDATA[]]></system-err>\n";
echo "</testcase>\n";
echo "</testsuite>\n";
