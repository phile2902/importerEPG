<p align="center">Importer EPG using Laravel Framework</p>


## About Importer

This project is a mini application used to read EPG data from providers and import it to database with the existed format.

## The principal

The project is created with using Laravel Framework, a MVC design pattern. So corresponding models, controllers, views are created.

Data files of providers are uploaded via welcome page and handled in TVController. Depending on extension of files, they will be formatted with suitable methods. At this moment, this application just only support format from xml to json method. Json file is no needed to formatted but it should follow the standard format. The application is designed to support formatting from the other files such as csv, web services, etc., to json. However, at this moment, corresponding methods were not created yet because I do not have models of csv or web services files.

Then, formatted to json data will be imported to database through models and repostitories. If there are any sql errors or other errors and the process could not be done, they will be catched and showed in notification view. Conversely, the success message will be showed in the notification view. 

## Images
<a href="http://imgur.com/UhEtyRU"><img src="http://i.imgur.com/UhEtyRU.png" title="source: imgur.com" /></a>
<a href="http://imgur.com/MibAxre"><img src="http://i.imgur.com/MibAxre.png" title="source: imgur.com" /></a>
<a href="http://imgur.com/8LKPes7"><img src="http://i.imgur.com/8LKPes7.png" title="source: imgur.com" /></a>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
Copyright © 2017 LE TIEU PHI.
