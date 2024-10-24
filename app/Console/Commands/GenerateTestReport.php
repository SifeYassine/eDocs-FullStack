<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateTestReport extends Command
{
    protected $signature = 'test:report';
    protected $description = 'Run tests and generate a detailed report';

    public function handle()
    {
        // Create logs directory if it doesn't exist
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0777, true);
        }

        $this->info('Running tests and generating report...');

        // Run the tests and capture output
        $process = new Process(['php', 'artisan', 'test', '--without-tty']);
        $process->run();

        $output = $process->getOutput();
        
        // Parse the output
        $report = $this->generateReport($output);
        
        // Save the report
        $filename = storage_path('logs/test-report-' . date('Y-m-d-His') . '.txt');
        file_put_contents($filename, $report);

        $this->info("Report generated: storage/logs/" . basename($filename));
    }

    protected function generateReport(string $testOutput): string
    {
        $lines = explode("\n", $testOutput);
        $report = "Test Execution Report\n";
        $report .= "====================\n\n";
        $report .= "Generated at: " . date('Y-m-d H:i:s') . "\n\n";

        $currentTestClass = '';
        $testResults = [];
        $summary = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0,
            'errors' => 0,
        ];

        foreach ($lines as $line) {
            // Track test class
            if (str_contains($line, 'Tests\\')) {
                $currentTestClass = trim($line);
                $testResults[$currentTestClass] = [];
                continue;
            }

            // Track individual tests
            if (str_starts_with(trim($line), '✓')) {
                $summary['passed']++;
                $summary['total']++;
                $testResults[$currentTestClass][] = [
                    'status' => 'PASSED',
                    'name' => trim(preg_replace('/✓|\d+\.\d+s/', '', $line)),
                    'time' => trim(preg_replace('/.*?(\d+\.\d+s)$/', '$1', $line))
                ];
            } elseif (str_starts_with(trim($line), '⨯')) {
                $summary['failed']++;
                $summary['total']++;
                $testResults[$currentTestClass][] = [
                    'status' => 'FAILED',
                    'name' => trim(preg_replace('/⨯|\d+\.\d+s/', '', $line)),
                    'time' => trim(preg_replace('/.*?(\d+\.\d+s)$/', '$1', $line))
                ];
            }
        }

        // Generate Summary
        $report .= "Summary\n";
        $report .= "-------\n";
        $report .= "Total Tests: {$summary['total']}\n";
        $report .= "Passed: {$summary['passed']}\n";
        $report .= "Failed: {$summary['failed']}\n";
        $report .= "Errors: {$summary['errors']}\n\n";

        // Generate Detailed Results
        $report .= "Detailed Results\n";
        $report .= "---------------\n\n";

        foreach ($testResults as $class => $tests) {
            if (!empty($tests)) {
                $report .= "Test Class: $class\n";
                $report .= str_repeat('-', strlen("Test Class: $class")) . "\n";
                
                foreach ($tests as $test) {
                    $report .= sprintf(
                        "%-60s [%s] %s\n",
                        $test['name'],
                        $test['status'],
                        $test['time']
                    );
                }
                $report .= "\n";
            }
        }

        // Add original output as reference
        $report .= "\nOriginal Test Output\n";
        $report .= "-------------------\n";
        $report .= $testOutput;

        return $report;
    }
}