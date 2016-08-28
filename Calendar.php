<?php
//
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Calendar {
    private $month;
    private $year;
    private $days_of_week;
    private $day_of_week;
    private $num_days;
    private $date_info;

    // @param {} days_of_week can be whatever you want
    public function __construct($month, $year, $days_of_week = array('Su', 'M', 'T', 'W', 'Th', 'F', 'Sa')) {
        $this->month = $month ?: date('m');
        $this->year = $year ?: date('Y');
        $this->days_of_week = $days_of_week;
        $this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $this->date_info = getdate(strtotime('first day of', (mktime(0,0,0,$this->month,1,$this->year)))); //array of lots of date data
        $this->day_of_week = $this->date_info['wday'];
    }

    // build the calendar table
    public function show() {

        //month and year caption
        $output = "<table class='calendar'>";
        $output .= "<caption>" . $this->date_info["month"] . " " . $this->year . "</caption>";

        // days of the week header
        $output .= "<tr>";
        foreach ($this->days_of_week as $day) {
            $output .= "<th class='header'>$day</th>";
        }
        $output .= "</tr>";

        // begin adding rows of days. if the beginning of the month doesn't fall on a Sunday,
        // then fill the preceding days with spaces
        $output .= "<tr>";
        if ($this->day_of_week > 0) {
            $output .= "<td colspan='" . $this->day_of_week . "'></td>";
        }

        // current day counter
        $current = 1;
        while ($current <= $this->num_days) {

            // reset day of week
            if ($this->day_of_week == 7) {
                $this->day_of_week = 0;
                $output .= "</tr><tr>";
            }

            $output .= "<td class='day'>$current</td>";

            // update counters
            ++$current;
            ++$this->day_of_week;
        }

        // fill the remaining spaces in the row, if necessary
        if ($this->day_of_week != 7) {
            $spaces = 7 - $this->day_of_week;
            $output .= "<td colspan='$spaces'></td>";
        }

        $output .= "</tr></table>";

        echo $output;
    }

    public function addTableRow($content) {
        return "<tr>$content</tr>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="styles.css" rel="stylsheet">
</head>
<body>
<?php
$calendar = new Calendar();
$calendar->show();
?>
</body>
</html>

