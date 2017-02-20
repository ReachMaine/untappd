<?php
/* class def */

class UTB_untappd {
    const URI_BASE = 'https://api.untappd.com/v4';
    protected $_clientId = '';
    protected $_clientSecret = '';
    protected $_redirectUri = '';
    protected $_lastParsedResponse = null;
    protected $_lastRawResponse = null;
    protected $_lastRequestUri = null;
    protected $_breweryID = '';

    public function __construct(array $connectArgs = array())
    {
        if (!isset($connectArgs['clientId']) || empty($connectArgs['clientId'])) {
            echo 'clientId not set and is required';
        }

        if (!isset($connectArgs['clientSecret']) || empty($connectArgs['clientSecret'])) {
            echo 'clientSecret not set and is required';
        }

        $this->_clientId = $connectArgs['clientId'];
        $this->_clientSecret = $connectArgs['clientSecret'];
        $this->_breweryID = $connectArgs['breweryid'];
    }

    public function userFeed($username = '', $limit = '', $offset = '')
    {
        if ($username == '') {

            echo 'username parameter or Untappd authentication parameters must be set.';
        }

        $args = array(
            'limit'  => $limit,
            'offset' => $offset
        );

        return $this->TappdRequest('user/checkins/' . $username, $args);
    }

    public function beerFeed($beerId, $since = '', $offset = '')
    {
        if (empty($beerId)) {

            echo 'beerId parameter must be set and not empty';
        }

        $args = array(
            'since'  => $since,
            'offset' => $offset,
        );

        return $this->TappdRequest('beer/checkins/' . $beerId, $args);
    }

    public function venueFeed($venueId, $since = '', $offset = '', $limit = '')
    {
        if (empty($venueId)) {

            echo 'venueId parameter must be set and not empty';
        }
        $args = array(
            'since'    => $since,
            'offset'   => $offset,
            'limit'    => $limit,
        );

        return $this->TappdRequest('venue/checkins/' . $venueId, $args);
    }

    public function breweryBeers($breweryId, $since = '', $offset = '', $limit = '')
    {
        if (empty($breweryId)) {
            echo 'breweryId parameter must be set and not empty';
        }
        $args = array(
            'since'  => $since,
            'offset' => $offset,
            'limit'  => $limit,
        );
        return $this->TappdRequest('brewery/info/' . $breweryId, $args);
    }

    protected function TappdRequest($method, $args, $requireAuth = false)
    {
        $this->_lastRequestUri = null;
        $this->_lastRawResponse = null;
        $this->_lastParsedResponse = null;

        // Append the API key to the args passed in the query string
        $args['client_id'] = $this->_clientId;
        $args['client_secret'] = $this->_clientSecret;

        // remove any unnecessary args from the query string
        foreach ($args as $key => $a) {
            if ($a == '') {
                unset($args[$key]);
            }
        }

        if (preg_match('/^https/i', $method)) {
            $this->_lastRequestUri = $method;
        } else {
            $this->_lastRequestUri = self::URI_BASE . '/' . $method;
        }

        $this->_lastRequestUri .= '?' . http_build_query($args);

        // Set curl options and execute the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_lastRequestUri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->_lastRawResponse = curl_exec($ch);

        if ($this->_lastRawResponse === false) {

            $this->_lastRawResponse = curl_error($ch);

            echo 'CURL Error: ' . curl_error($ch);
        }

        curl_close($ch);

        // Response comes back as JSON, so we decode it into a stdClass object
        $this->_lastParsedResponse = json_decode($this->_lastRawResponse);

        // If the https_code var is not found, the response from the server was unparsable
        if (!isset($this->_lastParsedResponse->meta->code)) {

            echo 'Error parsing response from server.';
        }

        // Server provides error messages in https_code and error vars.  If not 200, we have an error.
        if ($this->_lastParsedResponse->meta->code != '200') {

            echo 'Untappd Service Error ' .
                $this->_lastParsedResponse->meta->code . ': ' .  $this->_lastParsedResponse->meta->error;
        }

        return $this->getLastParsedResponse();
    }

    public function getLastParsedResponse()
    {
        return $this->_lastParsedResponse;
    }

    public function getLastRawResponse()
    {
        return $this->_lastRawResponse;
    }

    public function getLastRequestUri()
    {
        return $this->_lastRequestUri;
    }

}
