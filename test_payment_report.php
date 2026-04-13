<?php
// Quick script to output a test report URL if one exists

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

try {
    // Find or create a test user
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'testuser@example.com'],
        [
            'name' => 'Test User',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'user'
        ]
    );

    // Find an urgent report that needs payment
    $report = \App\Models\Report::where('urgency', 'urgent')
        ->where('payment_status', '!=', 'completed')
        ->where('user_id', $user->id)
        ->first();

    // If no report, create one
    if (!$report) {
        $report = \App\Models\Report::create([
            'user_id' => $user->id,
            'report_uid' => \Illuminate\Support\Str::uid(),
            'reporter_name' => 'Test Reporter',
            'reporter_email' => 'test@example.com',
            'reporter_phone' => '9841234567',
            'title' => 'Test Mouse - Lost in Lab',
            'description' => 'This is a test report for payment system testing',
            'color' => 'White',
            'type' => 'lost',
            'category' => 'Electronics',
            'location' => 'Nepal Block - Library',
            'latitude' => 28.2096,
            'longitude' => 83.9856,
            'date' => now(),
            'status' => 'pending',
            'urgency' => 'urgent',
            'payment_status' => 'pending',
            'is_anonymous' => false,
        ]);
    }

    echo "\nTest Report Created/Found:\n";
    echo "ID: " . $report->id . "\n";
    echo "Title: " . $report->title . "\n";
    echo "Urgency: " . $report->urgency . "\n";
    echo "Payment Status: " . $report->payment_status . "\n";
    echo "Status: " . $report->status . "\n";
    echo "\nPayment URL: http://localhost:8000/reports/" . $report->id . "/payment\n";
    echo "User: " . $user->email . " / password123\n\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
