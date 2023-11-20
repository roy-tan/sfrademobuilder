'use strict';

var server = require('server');
var HTTPClient = require('dw/net/HTTPClient');
var cache = require('*/cartridge/scripts/middleware/cache');
var consentTracking = require('*/cartridge/scripts/middleware/consentTracking');
var pageMetaData = require('*/cartridge/scripts/middleware/pageMetaData');

server.get('Show', consentTracking.consent, cache.applyDefaultCache, function (req, res, next) {
    // start weather demo related code
    //
    // variables needed for weather demo
    // Note: in the homePage.isml there are a few environment variables set at the top of the page that are accessible in other isml templates
    // Note: Below in the res.Render sections a few variables are passed on to the homePage template. If you need more data from here please pass it on in the arguments

    // get country code for localized weather information
    var Locale = require('dw/util/Locale');
    var currentLocale = Locale.getLocale(req.locale.id);

    // prepare weather related variables
    var currentCustomer = req.currentCustomer.raw;
    var latitude = req.geolocation.latitude;
    var longitude = req.geolocation.longitude;
    var weather: string;
    var outside: string;
    var temperature: num;
    var iconCode: string;
    var iconUrl: string;
    var city: string;
    var weatherServiceFoundation = "https://api.openweathermap.org/data/2.5/weather?";
    var weatherURL = weatherServiceFoundation + 'lat=' + latitude + '&lon=' + longitude + '&appid=f9e21ea09f1c172c595a22b1185348bd&units=metric&lang='+ currentLocale.language;
    var httpClient: HTTPClient = new HTTPClient();
    httpClient.open('GET', weatherURL);
    httpClient.send();
    weather = httpClient.text;
    var conditions : string = JSON.parse(weather);
    temperature = conditions.main.temp;
    var temperatureFixed: num = temperature.toFixed(0);
    outside = conditions.weather[0].description;
    iconCode = conditions.weather[0].icon;
    iconUrl = 'http://openweathermap.org/img/wn/' + iconCode + '@2x.png';
    city = conditions.name;
    //end weather demo related code

    var Site = require('dw/system/Site');
    var pageMetaHelper = require('*/cartridge/scripts/helpers/pageMetaHelper');

    pageMetaHelper.setPageMetaTags(req.pageMetaData, Site.current);
    res.render('/home/homePage', {temperature : temperatureFixed, outside : outside, iconUrl : iconUrl, city : city});
    next();
}, pageMetaData.computedPageMetaData);

server.get('ErrorNotFound', function (req, res, next) {
    res.setStatusCode(404);
    res.render('error/notFound');
    next();
});

module.exports = server.exports();
