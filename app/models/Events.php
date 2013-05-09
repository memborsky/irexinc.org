<?php

class Events extends Eloquent {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'events';

  /**
  * Checks to see if the event is out of state.
  *
  * @return boolean
  */
  public function isIREX()
  {
    if ($this->calendar_id == 1)
    {
      return true;
    }

    return false;
  }

  /**
  * Get the events date in the proper format for displaying on the calendar page.
  *
  * @return string
  */
  public function getDate()
  {
    $start = strtotime($this->start_date);
    $end = strtotime($this->end_date);

    // The event either only has a start date or it is all in the same month.
    // So we will only seed the date for this case.
    $date = strftime("%B %-e", $start);

    // If we have an end date, lets figure out how to provide the correct date.
    if ($end != false) {

      if (substr($this->end_date, 0, 4) == substr($this->start_date, 0, 4)) {

        if (substr($this->end_date, 5, 2) == substr($this->start_date, 5, 2)) {

          if (substr($this->end_date, 8, 2) == substr($this->start_date, 8, 2)) {

            // The event is a single day event; because of this, we can just use
            // the seeded date from our start date.
            null;

          } else {

            // The event is multi day but in the same month.
            $date .= " &dash; " . strftime("%-e", $end);

          }

        } else {

          // The event spans over the end of the month.
          $date .= " &dash; " . strftime("%B %-e", $end);

        }

      } else {

        // The event spans over the end of the year.
        $date = strftime("%B %-e, %Y", $start) . " &dash; " . strftime("%B %-e, %Y", $end);

      }

    }

    return $date;
  }

  /**
  * Gets the next IREX meeting from the events table.
  *
  * @return string
  */
  public function getNextMeeting()
  {
    $meeting = Events::where('calendar_id', '=', 1)
      ->where("end_date", ">", strftime("%F %T", time()))
      ->where('active', '=', 1)
      ->take(1)
      ->get(array("start_date"))
      ->toArray();

    // strftime("%B %e at %l %p", next_meeting_unix_timestamp)
    // -> Weekday, Month Day at Hour AM/PM
    return strftime("%A, %B %e at %l %p", strtotime($meeting[0]['start_date']));
  }

  /**
  * Gets the calendar id the event belongs too.
  *
  * @return int
  */
  public function calendar()
  {
    return $this->belongsTo('Calendars');
  }

}