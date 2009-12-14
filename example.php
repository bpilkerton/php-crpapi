<?php
/**
 *
 * php-crpapi example usage
 * @author Ben Pilkerton
 *
**/

require_once('crpapi.php');

$crp = new crpData("candIndustry", Array("cid"=>"N00000019","cycle"=>"2006","output"=>"xml"));

/**
 * These variables are exposed upon instantiation
**/

echo $crp->apikey . "<br />";
echo $crp->output . "<br />";
echo $crp->method . "<br />";
echo $crp->url    . "<br />";

/**
 * Get the data (either JSON std obj or SimpleXML Object)
**/

echo "<hr />";

$data = $crp->getData();
print_r($data);

/**
 * Alternatively use the raw response after getData() is run
**/

print_r($crp->rawdata);

?>