# Google Music Analysis

## Usage

 1. Retrieve a [CSV dump of your Google Play Music Library](https://gist.github.com/danhanly/9279ccc18d5594e305ad028505400130)
 2. Run one the commands contained within this repository
 
## Commands

This package comes bundled with a load of helpful commands for analysing your music collection.

### Artist

#### Simple

`php musician artist:simple <csv_file__path>`

The simple ranking system for artists ranks your artists by the amount of thumbs-up ratings they have received.

#### Differential

`php musician artist:differential <csv_file__path>`

To rule out controversial artists, the differential ranking system is the same as the simple one, but it also subtracts the amount of thumbs-down ratings they have received.

#### Wilson

`php musician artist:wilson <csv_file__path>`

For a more accurate statistical analysis of your music collection, normalising results regardless of discography size, you can use Wilson Lower Bound Confidence Interval scores to rank your collection.

#### Wilson Extended

`php musician artist:wilson-extended <csv_file__path>`

This command is similar to the Wilson command, but it normalises based upon thumbs-up ratings and thumbs-down ratings separately, then subtracts them to, again, rule out controversial artists.
