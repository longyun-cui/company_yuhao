<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Home</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CPoppins:400,600" rel="stylesheet">


        <!-- favicon and touch icons -->
        <link rel="shortcut icon" href="assets/images/favicon.png.html" >

        <!-- Bootstrap -->
        <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="plugins/slick/slick.css" rel="stylesheet">
        <link href="plugins/slick-nav/slicknav.css" rel="stylesheet">
        <link href="plugins/wow/animate.css" rel="stylesheet">
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <link href="assets/css/theme.css" rel="stylesheet">

    </head>
    <body class="">
        <div id="page-loader">
            <div class="loaders">
                <img src="assets/images/loader/3.gif" alt="First Loader">
                <img src="assets/images/loader/4.gif" alt="First Loader">
            </div>
        </div>
        <header id="site-header">
            <div id="site-header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="clearfix">
                                <button class="btn btn-warning btn-lg header-btn visible-sm pull-right">List your Property for Free</button>
                                <p class="timing-in-header">Open Hours: Monday to Saturday - 8am to 6pm</p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="clearfix">
                                <button class="btn btn-warning btn-lg header-btn hidden-sm">List your Property for Free</button>
                                <div class="language-in-header">
                                    <i class="fa fa-globe"></i>
                                    <label for="language-dropdown"> Language:</label>
                                    <select name="currency" id="language-dropdown">
                                        <option value="ENG">ENG</option>
                                        <option value="AR">AR</option>
                                        <option value="UR">UR</option>
                                        <option value="NEO">NEO</option>
                                        <option value="AKA">AKA</option>
                                    </select>
                                </div>
                                <div class="currency-in-header">
                                    <i class="fa fa-flag"></i>
                                    <label for="currency-dropdown"> Currency: </label>
                                    <select name="currency" id="currency-dropdown">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="AOA">AOA</option>
                                        <option value="XCD">XCD</option>
                                        <option value="PKR">PKR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <figure id="site-logo">
                            <a href="index.html"><img src="assets/images/logo.png" alt="Logo"></a>
                        </figure>
                    </div>
                    <div class="col-md-6 col-sm-8">
                        <nav id="site-nav" class="nav navbar-default">
                            <ul class="nav navbar-nav">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="property-listing.html">Listing</a></li>
                                <li><a href="single-property.html">Property</a></li>
                                <li><a href="gallery.html">Gallery</a></li>
                                <li><a href="contact.html">contact</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="contact-in-header clearfix">
                            <i class="fa fa-mobile"></i>
                            <span>
                                Call Us Now
                                <br>
                            <strong>111 222 333 444</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="main-slider-wrapper clearfix">
            <div id="main-slider">
                <div class="slide"><img src="assets/images/slider/1.jpg" alt="Slide"></div>
                <div class="slide"><img src="assets/images/slider/2.jpg" alt="Slide"></div>
                <div class="slide"><img src="assets/images/slider/3.jpg" alt="Slide"></div>
                <div class="slide"><img src="assets/images/slider/4.jpg" alt="Slide"></div>
            </div>
            <div id="slider-contents">
                <div class="container text-center">
                    <div class="jumbotron">
                        <h1>Find Your Dream House</h1>
                        <div class="contents clearfix">
                            <p>If you dream of designing a new home that takes full advantage of <br>
                                the unique geography and views of land that you love</p>
                        </div>
                        <a class="btn btn-warning btn-lg btn-3d" data-hover="Our Services" href="index.html#" role="button">Our Services</a>
                        <a class="btn btn-default btn-border btn-lg" href="index.html#" role="button">Get a Quote</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="advance-search" class="main-page clearfix">
            <div class="container">
                <button class="btn top-btn">Find Your Place</button>
                <form action="index.html#" id="adv-search-form" class="clearfix">
                    <fieldset>
                        <select name="location" id="main-location">
                            <option value="">All Cities</option>
                            <option value="chicago"> Chicago</option>
                            <option value="los-angeles"> Los Angeles</option>
                            <option value="miami"> Miami</option>
                            <option value="new-york"> New York</option>
                        </select>
                        <select name="sub-location" id="property-sub-location">
                            <option value="">All Areas</option>
                            <option value="brickell" > Brickell</option>
                            <option value="brickyard" > Brickyard</option>
                            <option value="bronx" > Bronx</option>
                            <option value="brooklyn" > Brooklyn</option>
                            <option value="coconut-grove" > Coconut Grove</option>
                            <option value="downtown" > Downtown</option>
                            <option value="eagle-rock" > Eagle Rock</option>
                            <option value="englewood" > Englewood</option>
                            <option value="hermosa" > Hermosa</option>
                            <option value="hollywood" > Hollywood </option>
                            <option value="lincoln-park" > Lincoln Park</option>
                            <option value="manhattan" > Manhattan</option>
                            <option value="midtown" > Midtown</option>
                            <option value="queens" > Queens</option>
                            <option value="westwood" > Westwood </option>
                            <option value="wynwood" > Wynwood</option>
                        </select>
                        <select id="property-status" name="status">
                            <option value="">All Status</option>
                            <option value="for-rent"> For Rent</option>
                            <option value="for-sale"> For Sale</option>
                            <option value="foreclosures"> Foreclosures</option>
                            <option value="new-costruction"> New Costruction</option>
                            <option value="new-listing"> New Listing</option>
                            <option value="open-house"> Open House</option>
                            <option value="reduced-price"> Reduced Price</option>
                            <option value="resale"> Resale</option>
                        </select>
                        <select id="property-type" name="type" >
                            <option value="">All Types</option>
                            <option value="apartment"> Apartment</option>
                            <option value="condo"> Condo</option>
                            <option value="farm"> Farm</option>
                            <option value="loft"> Loft</option>
                            <option value="lot"> Lot</option>
                            <option value="multi-family-home"> Multi Family Home</option>
                            <option value="single-family-home"> Single Family Home</option>
                            <option value="townhouse"> Townhouse</option>
                            <option value="villa"> Villa</option>
                        </select>
                        <select name="bedrooms" id="property-beds">
                            <option value="">Beds</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="any">Any</option>
                        </select>
                        <select name="bathrooms" id="property-baths">
                            <option value="">Bathrooms</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="any">Any</option>
                        </select>
                        <input type="text" name="min-area" id="property-min-area" placeholder="Min Area (sqft)">
                        <input type="text" name="max-area" id="property-max-area" placeholder="Max Area (sqft)">
                        <select name="min-price" id="property-min-price">
                            <option value="any" selected="selected">Min Price</option>
                            <option value="1000">$1000</option>
                            <option value="5000">$5000</option>
                            <option value="10000">$10000</option>
                            <option value="50000">$50000</option>
                            <option value="100000">$100000</option>
                            <option value="200000">$200000</option>
                            <option value="300000">$300000</option>
                            <option value="400000">$400000</option>
                            <option value="500000">$500000</option>
                            <option value="600000">$600000</option>
                            <option value="700000">$700000</option>
                            <option value="800000">$800000</option>
                            <option value="900000">$900000</option>
                            <option value="1000000">$1000000</option>
                            <option value="1500000">$1500000</option>
                            <option value="2000000">$2000000</option>
                            <option value="2500000">$2500000</option>
                            <option value="5000000">$5000000</option>
                        </select>
                        <select name="max-price" id="property-max-price" >
                            <option value="any" selected="selected">Max Price</option>
                            <option value="5000">$5000</option>
                            <option value="10000">$10000</option>
                            <option value="50000">$50000</option>
                            <option value="100000">$100000</option>
                            <option value="200000">$200000</option>
                            <option value="300000">$300000</option>
                            <option value="400000">$400000</option>
                            <option value="500000">$500000</option>
                            <option value="600000">$600000</option>
                            <option value="700000">$700000</option>
                            <option value="800000">$800000</option>
                            <option value="900000">$900000</option>
                            <option value="1000000">$1000000</option>
                            <option value="1500000">$1500000</option>
                            <option value="2000000">$2000000</option>
                            <option value="2500000">$2500000</option>
                            <option value="5000000">$5000000</option>
                            <option value="10000000">$10000000</option>
                        </select>
                    </fieldset>
                    <button type="submit" class="btn btn-default btn-lg text-center">Search <br class="hidden-sm hidden-xs"> Property</button>
                </form>
            </div>
        </div>
        <section id="home-property-listing">
        <header class="section-header home-section-header text-center">
            <div class="container">
                <h2 class="wow slideInRight">Featured Properties</h2>
                <p class="wow slideInLeft">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut <br>
                    labore et dolore magna aliquan ut enim ad minim veniam.</p>
            </div>
        </header>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/1.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div><div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/2.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div><div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/3.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div><div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/4.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div><div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/5.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div><div class="col-lg-4 col-sm-6 layout-item-wrap"><article class="property layout-item clearfix">
            <figure class="feature-image">
                <a class="clearfix zoom" href="single-property.html"><img data-action="zoom" src="assets/images/property/6.jpg" alt="Property Image"></a>
                <span class="btn btn-warning btn-sale">for sale</span>
            </figure>
            <div class="property-contents clearfix">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
                <div class="contents clearfix">
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
                </div>
                <div class="author-box clearfix">
                    <a href="index.html#" class="author-img"><img src="assets/images/agents/1.jpg" alt="Agent Image"></a>
                    <cite class="author-name">Personal Seller: <a href="index.html#">Linda Garret</a></cite>
                    <span class="phone"><i class="fa fa-phone"></i> 00894 692-49-22</span>
                </div>
            </div>
        </article>
        </div>        </div>
            </div>
        </section>
        <section id="announcement-section" class="text-center">
               <div class="container ">
                   <h2 class="title wow slideInLeft">Download Our Latest App</h2>
                   <p class="wow slideInRight">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut <br>
                       labore et dolore magna aliquan ut enim ad minim veniam.</p>
                   <a class="btn" href="index.html#"><img src="assets/images/iso-btn.png" alt="ISO Button"></a>
                   <a class="btn" href="index.html#"><img src="assets/images/playstore-btn.png" alt="Play Store Button"></a>
               </div>
        </section>

        <section id="home-property-for-rent-listing">
            <header class="section-header home-section-header text-center">
                <div class="container">
                    <h2 class="wow slideInLeft">Office For Rent</h2>
                    <p class="wow slideInRight">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut <br>
                        labore et dolore magna aliquan ut enim ad minim veniam.</p>
                </div>
            </header>
            <div class="container">
                <div class="row">
                    <div id="property-for-rent-slider">
                        <div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/1.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div><div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/2.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div><div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/3.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div><div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/4.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div><div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/5.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div><div class="col-lg-4 col-md-6"><article class="property clearfix">
            <figure class="feature-image">
                <a class="clearfix" href="single-property.html"> <img src="assets/images/property/6.jpg" alt="Property Image"></a>
            </figure>
            <div class="property-contents">
                <header class="property-header clearfix">
                    <div class="pull-left">
                        <h6 class="entry-title"><a href="single-property.html">Guaranteed modern home</a></h6>
                        <span class="property-location"><i class="fa fa-map-marker"></i> 14 Tottenham Road, London</span>
                    </div>
                    <button class="btn btn-default btn-price pull-right btn-3d" data-hover="$389.000"><strong>$389.000</strong></button>
                </header>
                <div class="property-meta clearfix">
                    <span><i class="fa fa-arrows-alt"></i> 3060 SqFt</span>
                    <span><i class="fa fa-bed"></i> 3 Beds</span>
                    <span><i class="fa fa-bathtub"></i> 3 Baths</span>
                    <span><i class="fa fa-cab"></i> Yes</span>
                </div>
            </div>
        </article></div>            </div>
                </div>
            </div>
        </section>

        <section id="home-features-section" class="text-center">
            <header class="section-header home-section-header">
               <div class="container">
                   <h2 class="wow slideInRight">WHY CHOOSE US</h2>
                   <p class="wow slideInLeft">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut <br>
                       labore et dolore magna aliquan ut enim ad minim veniam.</p>
               </div>
            </header>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <div class="feature clearfix">
                            <i class="icon"><img src="assets/images/features/1.png" alt="Feature Icon"></i>
                            <h6 class="entry-title">Paying guest</h6>
                            <p>Dolor sit amet consectetuer sed diam nonummy euismod tincidunt laoreet dolore magna</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="feature clearfix">
                            <i class="icon"><img src="assets/images/features/2.png" alt="Feature Icon"></i>
                            <h6 class="entry-title">Paying guest</h6>
                            <p>Dolor sit amet consectetuer sed diam nonummy euismod tincidunt laoreet dolore magna</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="feature clearfix">
                            <i class="icon"><img src="assets/images/features/3.png" alt="Feature Icon"></i>
                            <h6 class="entry-title">Paying guest</h6>
                            <p>Dolor sit amet consectetuer sed diam nonummy euismod tincidunt laoreet dolore magna</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="feature clearfix">
                            <i class="icon"><img src="assets/images/features/4.png" alt="Feature Icon"></i>
                            <h6 class="entry-title">Paying guest</h6>
                            <p>Dolor sit amet consectetuer sed diam nonummy euismod tincidunt laoreet dolore magna</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer id="footer">
            <div class="site-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <section class="widget about-widget clearfix">
                                <h4 class="title hide">About Us</h4>
                                <a class="footer-logo" href="index.html#"><img src="assets/images/footer-logo.png" alt="Footer Logo"></a>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>
                                <ul class="social-icons clearfix">
                                    <li><a href="index.html#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="index.html#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="index.html#"><i class="fa fa-pinterest"></i></a></li>
                                    <li><a href="index.html#"><i class="fa fa-youtube-play"></i></a></li>
                                </ul>
                            </section>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <section class="widget twitter-widget clearfix">
                                <h4 class="title">Latest Tweets</h4>
                                <div id="twitter-feeds" class="clearfix"></div>
                            </section>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <section class="widget address-widget clearfix">
                                <h4 class="title">OUR OFFICE</h4>
                                <ul>
                                    <li><i class="fa fa-map-marker"></i> 4 Tottenham Road, London, England.</li>
                                    <li><i class="fa fa-phone"></i> (123) 45678910</li>
                                    <li><i class="fa fa-envelope"></i> huycoi.art@gmail.com</li>
                                    <li><i class="fa fa-fax"></i> +84 962 216 601</li>
                                    <li><i class="fa fa-clock-o"></i> Mon - Sat: 9:00 - 18:00</li>
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer-bottom">
                <div class="container">
                  <p class="copyright pull-left wow slideInRight">Copyright &copy; 2017.Company name All rights reserved.<a target="_blank" href="http://sc.chinaz.com/moban/">&#x7F51;&#x9875;&#x6A21;&#x677F;</a></p>
                    <nav class="footer-nav pull-right wow slideInLeft">
                        <ul>
                            <li><a href="index.html#">Terms & Conditions</a></li>
                            <li><a href="index.html#">Pricing</a></li>
                            <li><a href="index.html#">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </footer>
        <a href="index.html#top" id="scroll-top"><i class="fa fa-angle-up"></i></a>


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.migrate.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="plugins/slick-nav/jquery.slicknav.min.js"></script>
        <script src="plugins/slick/slick.min.js"></script>
        <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="plugins/tweetie/tweetie.js"></script>
        <script src="plugins/forms/jquery.form.min.js"></script>
        <script src="plugins/forms/jquery.validate.min.js"></script>
        <script src="plugins/modernizr/modernizr.custom.js"></script>
        <script src="plugins/wow/wow.min.js"></script>
        <script src="plugins/zoom/zoom.js"></script>
        <script src="plugins/mixitup/mixitup.min.js"></script>
        <!---<script src="http://ditu.google.cn/maps/api/js?key=AIzaSyD2MtZynhsvwI2B40juK6SifR_OSyj4aBA&libraries=places"></script>--->
        <script src="plugins/whats-nearby/source/WhatsNearby.js"></script>
        <script src="assets/js/theme.js"></script>


    </body>
</html>
