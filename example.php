<?php
/**
 *
 * php-crpapi example usage
 * @author Ben Pilkerton
 *
 *
 * There are a few options which need to be defined in the constructor 
 * in crpapi.php.  These include specifying your API key, default 
 * output type (json recommended, default) and cache lifetime 
 * (one day is default).
 *
 * Include the CRP API Library and create an object instance, specifying
 * the parameters.  In this example, we specify the output type.  This
 * could be ommitted and the default (defined in the constructor) will
 * be used.
 *
**/

require_once('crpapi.php');

/**
 * Setup the class instance with our request parameters
**/

$crp = new crp_api("candIndustry", Array("cid"=>"N00002408","cycle"=>"2012","output"=>"json"));

/**
 * These variables are exposed upon instantiation
**/

echo "<h2>Request data</h2>";

echo $crp->api_key . "<br />";
echo $crp->output . "<br />";
echo $crp->method . "<br />";
echo $crp->base_url . "<br />";
echo $crp->url    . "<br />";
echo "<hr />";

/**
 * Get the data. This example retrieves json data which is converted to 
 * an associative array. If using xml, a SimpleXML object will be returned.  
 * The getData method can optionally be passed a true/false value (true is 
 * default).  If set to false, a local file cache will not be used.
**/

$data = $crp->get_data();

/**
 * Show the cache status.  By default, the library caches API query results in a
 * gzipped, serialized form in a text file in the dataCache directory.  If you do 
 * not desire file caching, call get_data(false) (see above).  The cache life can
 * be set by altering $this->cache_time value in crpapi.php.  The default is 
 * one day.
**/

echo "<h2>Request Cache Status</h2>";

if ($crp->get_cache_status()) {
    echo "Cache Hit";
} else {
    echo "Cache Miss";
}

echo "<hr />";

/**
 * Show response headers.  If not using the cache or the cache is empty an http
 * request is made to the service. You can view the HTTP response headers with
 * something like this.
**/

if (!$crp->get_cache_status()) {

    echo "<h2>HTTP Response Headers</h2>";

    foreach ($crp->response_headers as $header) {
        echo $header . "<br />";
    }

    echo "<hr />";
}

/**
 * Iterate over the results
**/

echo "<h2>Parsed Results</h2>";

echo "<h3>Meta data</h3>";
foreach ($data['response']['industries']['@attributes'] as $key=>$val) {
    echo $key . " => " . $val . "<br />";
}

echo "<h3>Actual data</h3>";
echo "<table><tr><th>Industry</th><th>Indivs</th><th>PACs</th><th>Total</th></tr>";
foreach ($data['response']['industries']['industry'] as $ind) {
    foreach ($ind as $row) {
        echo "<tr><td>" . $row['industry_name'] . "</td><td>$" . 
            $row['indivs'] . "</td><td>$" . $row['pacs'] . "</td><td>$" . 
            $row['total'] . "</td></tr>";
    }
}

echo "</table>";

?>