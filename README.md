# PHP-colored-ascii-linechart

[![Sinus output](https://i.imgur.com/Wc7OjvO.gif)](https://i.imgur.com/Wc7OjvO.gif)

Create beautiful, versatile ASCII line-charts within Terminal, written in `PHP`.

- Create multiple lines in a single chart, each with it's own color,
- Use points in your chart ,
- Scale the chart to a desired height or let it grow and shrink freely,
- Have multi-colored lines based on uptrends and downtrends,
- Print charts as ASCII colored text, a HTML snippet or png image,
- Use simple API that helps with animating sequence of charts.

_Built upon [kroitor/asciichart](https://github.com/kroitor/asciichart)_

## Installation
```
$ composer require noximo/php-colored-ascii-linechart
```

## Usage

### Simple Output:

```php
$linechart = new Linechart();
echo $linechart->addMarkers([1,2,3,4,5,6])->addPoint(4, 2)->chart();
```
This will print a simple chart with a single point in default colors.

### Advanced Output:

```php
$settings = new Settings();
// Note that any setting can be ommited.
$settings
    ->setColorizer(new AsciiColorizer())  // Colorizer, choose between Ascii, HTML and image colorizers
    ->setFPS(24)  // Control speed of chart::wait method
    ->setHeight(30)  // Set fixed height of chart. chart will scale accordingly. If not set, height will be calculated based on highest and lowest numbers across all sets of markers.
    ->setPadding(5, ' ')  // Set lenght of a padding and character used
    ->setOffset(10)  // Offset left border
    ->setFormat(  // Control how y axis labels will be printed out
        function ($x, Settings $settings) {
            $padding = $settings->getPadding();
            $paddingLength = \strlen($padding);

            return substr($padding . round($x, 2), -$paddingLength);
        }
    );

$linechart = new Linechart();
$linechart->setSettings($settings);

for ($y = 0; $y < 1200; $y++) { // Move sinusoid
    $lineA = [];
    $lineB = [];
    for ($i = $y; $i < $y + 120; $i++) {
        $lineA[] = 10 * sin($i * ((M_PI * 4) / 120));  // Draw sinusoid
        $lineB[] = 20 * sin($i * ((M_PI * 4) / 120));  // Draw sinusoid
    }
    
    $linechart->addMarkers(    
        $lineA,  // Chart data - note that any elements with non-integer keys will be discarded
        [AsciiColorizer::GREEN, AsciiColorizer::BOLD],  // Default color of line. Can be ommited. You can combine mutliple color codes together. If you set up HTML colorizer, you can enter css styles instead of codes. See below
        [AsciiColorizer::RED, AsciiColorizer::BOLD]  // Color of downtrend. Can be ommited, then default color will be used instead.
    );  // Pro-tip - combine color with bold style - it will pop-out nicely

    $linechart->addMarkers($lineB, [AsciiColorizer::CYAN]);  // Add as many datasets as you want

    $linechart->addLine(  // Add a guiding line - a zero line for example
        0,  // Alias y coordinate
        [AsciiColorizer::CYAN],  // You can set color the same way as with markers
        Linechart::FULL_LINE  // Choose between full line and
    );

    $linechart->addPoint(
        10,
        15,
        [AsciiColorizer::LIGHT_BLUE],
        Linechart::CROSS  // Point can be made more visible with crosslines. Default is Linechart::POINT
    );


    $chart = $linechart->chart();  // Chart is an object with all data drawn. It can be simply echoed or we can leverage its methods for output control

    $chart->clearScreen();  // Clears already outputed charts
    $chart->print();  // Alias of echo $chart with fluent method call
    $chart->wait();  // Naive implementation of animation. It simply sleeps for n microseconds (defined by setFPS earlier). It does not take into account time spent on chart generation or on retrieving data
    $linechart->clearAllMarkers();  // Get rid of already processed chart data so they won't get printed again.
}
```

This will print out the [chart](https://i.imgur.com/Wc7OjvO.gif) shown in the [heading](https://github.com/noximo/PHP-colored-ascii-linechart#php-colored-ascii-linechart).

### HTML Output

```php
$linechart = new Linechart();
$settings = new Settings();  // Settings are needed in this case
$settings->setColorizer(new HTMLColorizer());  // Here you need to set up HTMLColorizer

$lineA = [];
for ($i = 0; $i < +120; $i++) {
    $lineA[] = 10 * sin($i * ((M_PI * 4) / 120));
}

$linechart->addLine(0, ['color:white'], Linechart::FULL_LINE);  // Use css styles instead of ascii color codes
$linechart->addMarkers($lineA, ['color: green'], ['color: red', 'background-color: black']);
$linechart->setSettings($settings);

echo $linechart->chart();
``` 

[![Sinus output](https://i.imgur.com/Qw78k9k.png)](https://i.imgur.com/Qw78k9k.png)

### Image Output
Images are a work in progress. You can look in `/examples` folder for a *beta* implementation.

## Development Pathway / To-Do

- Refactoring of colorizers and printers:
  - single colorizer regardles of output type, 
  - chart class rewrite so $chart->toHtml(), $chart->toPng(), $chart->toAscii() etc. exists,
- Proper image support. Animated images through gifs,
- Better customization (backgrounds, borders),
- X axis with labels.