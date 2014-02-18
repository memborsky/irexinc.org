<?php

class NewsController extends BaseController {

  /**
  * The events implementation.
  *
  * @var Static
  */
  protected $events;

  // protected static $path = "./app/views/news/speakers/";

  /**
  * Set our Events instance.
  *
  * @param Events $events
  * @return void
  */
  public function __construct(Events $events)
  {
    $this->events = $events;
  }

  /**
  * GET -> irexinc.org/ & irexinc.org/news/
  *
  * @return View
  */
  public function index ()
  {
    $meetings = $this->events->getNextMeetings();

    // die(var_dump($meetings));

    foreach ($meetings as $index => $meeting)
    {
      if (file_exists("./app/views/news/speakers/" . $meeting['date'] . ".blade.php"))
      {
        $meetings[$index]['speaker'] = View::make('news.speakers.' . $meeting['date']);
      }

      $meetings[$index]['date'] = strftime("%B %d", strtotime($meeting['date']));
    }

    return View::make('news.index')->with(compact('meetings'));
    // return var_dump($next_meeting);
  }

}