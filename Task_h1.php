<?php

function calculate_time($access_time, $exit_time)
{
    $difference = $access_time->diff($exit_time);
    return $difference;
}

function free_time($difference)
{
    $worked_hours = $difference->h + ($difference->i / 60);

    if ($worked_hours > 8) {
        $overtime_hours = $worked_hours - 8;
        return $overtime_hours;
    } else {
        $deficit_hours = 8 - $worked_hours;
        return -$deficit_hours;
    }
}

echo "Enter the number of your employees: ";
$count_employees = (int)fgets(STDIN);

$employees = [];

for ($i = 0; $i < $count_employees; $i++) {
    echo "Enter the name of your employee " . ($i + 1) . ": ";
    $name = rtrim(fgets(STDIN));

    echo "Enter the time when $name enters the office (format: HH:MM): ";
    $access_time_str = rtrim(fgets(STDIN));
    $access_time = DateTime::createFromFormat('H:i', $access_time_str);

    if (!$access_time) {
        echo "Invalid access time format. Please enter in HH:MM format.\n";
        continue;
    }

    echo "Enter the time when $name leaves the office (format: HH:MM): ";
    $exit_time_str = rtrim(fgets(STDIN));
    $exit_time = DateTime::createFromFormat('H:i', $exit_time_str);

    if (!$exit_time) {
        echo "Invalid exit time format. Please enter in HH:MM format.\n";
        continue;
    }

    $employees[] = [
        'name' => $name,
        'access_time' => $access_time,
        'exit_time' => $exit_time
    ];
}

echo "\n";
echo "Employee access information:\n";
foreach ($employees as $employee) {
    echo "Name: " . $employee['name'] . "\n";
    echo "Access Time: " . $employee['access_time']->format('H:i') . "\n";
    echo "Exit Time: " . $employee['exit_time']->format('H:i') . "\n";
    echo "\n";
}

echo "\n";

foreach ($employees as $employee) {
    $difference = calculate_time($employee['access_time'], $employee['exit_time']);
    $free_time = free_time($difference);

    $total_hours = floor($difference->h + ($difference->i / 60));
    $total_minutes = $difference->i % 60;

    echo $employee['name'] . "'s working time: " . $total_hours . " hours " . $total_minutes . " minutes\n";

    if ($free_time > 0) {
        $overtime_hours_int = floor($free_time);
        $overtime_minutes = round(($free_time - $overtime_hours_int) * 60);
        echo $employee['name'] . " worked " . $overtime_hours_int . " hours " . $overtime_minutes . " minutes overtime.\n";
    } else {
        $deficit_hours_int = floor(-$free_time);
        $deficit_minutes = round((-$free_time - $deficit_hours_int) * 60);
        echo $employee['name'] . " owes " . $deficit_hours_int . " hours " . $deficit_minutes . " minutes.\n";
    }

    echo "\n";
}
