<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');

$KEYWORD = "what is goji";
$RESULT = [];

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
$host = 'http://localhost:4444/wd/hub';

$capabilities = DesiredCapabilities::chrome();

$driver = RemoteWebDriver::create($host, $capabilities);

// navigate to Selenium page on Wikipedia
$driver->get('http://www.google.com');

// write 'PHP' in the search box
$driver->findElement(WebDriverBy::name('q')) // find search input element
    ->sendKeys($KEYWORD) // fill the search box
    ->submit(); // submit the whole form

// wait until 'PHP' is shown in the page heading element
// $driver->wait()->until(
//     WebDriverExpectedCondition::elementTextContains(WebDriverBy::id('firstHeading'), 'PHP')
// );

// wait until the target page is loaded
$driver->wait()->until(
    WebDriverExpectedCondition::titleContains($KEYWORD)
);

// find elements of questions
$sResults = $driver->findElements(
    WebDriverBy::className('match-mod-horizontal-padding')
);

$questionArray = [];
foreach ($sResults as $element) {
    $k = $element->getText();
    // var_dump($k);
    if($k != "")
        $questionArray[] = $k;
}
// sleep(5);
$aResults = $driver->findElements(
    WebDriverBy::className('mod')
);

$i = 0;
foreach ($aResults as $element) {
    if($i > count($questionArray) -1)
        break;
    $ans = strip_tags($element->getAttribute("innerHTML"));
    // var_dump($ans);
    // echo "<br>";
    $tmp = array(
        "question" => $questionArray[$i],
        "answer" => $ans
    );

    $RESULT[] = $tmp;

    $i++;
}

print(json_encode($RESULT));
// // read text of the element and print it to output
// echo "About to click to button with text: '" . $historyButton->getText() . "'\n";

// // click the element to navigate to revision history page
// $historyButton->click();

// // wait until the target page is loaded
// $driver->wait()->until(
//     WebDriverExpectedCondition::titleContains('Revision history')
// );

// // print the title of the current page
// echo "The title is '" . $driver->getTitle() . "'\n";

// // print the URI of the current page

// echo "The current URI is '" . $driver->getCurrentURL() . "'\n";

// // delete all cookies
// $driver->manage()->deleteAllCookies();

// // add new cookie
// $cookie = new Cookie('cookie_set_by_selenium', 'cookie_value');
// $driver->manage()->addCookie($cookie);

// // dump current cookies to output
// $cookies = $driver->manage()->getCookies();
// print_r($cookies);

// // close the browser
// $driver->quit();


?>