<?php

namespace Bregananta\Blockchain;

use Illuminate\Config\Repository as Config;

class Blockchain {

    protected $config, $curl, $curlResponse;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $block
     * @return mixed
     */
    public function block($block)
    {
        $blockchainResponse = $this->callBlockchain('block-index/' . $block, 'GET', array('format' => 'json'));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get transaction info
     * @param $tx
     * @return mixed
     */
    public function tx($tx)
    {
        $blockchainResponse = $this->callBlockchain('tx-index/' . $tx, 'GET', array('format' => 'json'));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get an address transaction
     * @param $address
     * @param bool $limit
     * @param bool $offset
     * @return mixed
     */
    public function address($address, $limit = false, $offset = false)
    {
        $settings = array('format' => 'json');

        if($limit && $limit > 0)
            $settings['limit'] = $limit;

        if($offset && $offset > 0)
            $settings['offset'] = $offset;

        $blockchainResponse = $this->callBlockchain('address/' . $address, 'GET', $settings);

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get multi address transaction
     * @param array $addresses
     * @return mixed
     */
    public function multiAddress($addresses = array())
    {
        $blockchainResponse = $this->callBlockchain('multiaddr', 'GET', array(
            'format' => 'json',
            'active' => (is_array($address) ? implode('|', $address) : $address)
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get unspent outputs from address(es)
     * @param $address
     * @return mixed
     */
    public function unspentOutputs($address)
    {
        $blockchainResponse = $this->callBlockchain('unspent', 'GET', array(
            'format' => 'json',
            'active' => (is_array($address) ? implode('|', $address) : $address)
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get unconfirmed transactions
     * @return mixed
     */
    public function unconfirmedTxs()
    {
        $blockchainResponse = $this->callBlockchain('unconfirmed-transactions', 'GET', array(
            'format' => 'json'
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * ticker
     * @return mixed
     */
    public function ticker()
    {
        $blockchainResponse = $this->callBlockchain('ticker', 'GET', array(
            'format' => 'json'
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * convert currency to btc
     * @param $amount
     * @param string $currency
     * @return mixed
     */
    public function toBTC($amount, $currency = 'USD')
    {
        $currencies = explode('|', 'USD|ISK|HKD|TWD|CHF|EUR|DKK|CLP|CAD|CNY|THB|AUD|SGD|KRW|JPY|PLN|GBP|SEK|NZD|BRL|RUB');

        $blockchainResponse = $this->callBlockchain('tobtc', 'GET', array(
            'format' => 'json',
            'currency' => in_array($currency, $currencies) ? $currency : 'USD',
            'value' => intval($amount)
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get chart
     * @param $type
     * @return bool|mixed
     */
    public function chart($type)
    {
        if( ! in_array($type, array(
            'total-bitcoins',
            'market-cap',
            'transaction-fees',
            'n-transactions',
            'n-transactions-excluding-popular',
            'n-unique-addresses',
            'n-transactions-per-block',
            'n-orphaned-blocks',
            'output-volume',
            'estimated-transaction-volume',
            'estimated-transaction-volume-usd',
            'trade-volume',
            'tx-trade-ratio',
            'market-price',
            'cost-per-transaction-percent',
            'cost-per-transaction',
            'hash-rate',
            'difficulty',
            'miners-revenue',
            'avg-confirmation-time',
            'bitcoin-days-destroyed-cumulative',
            'bitcoin-days-destroyed',
            'bitcoin-days-destroyed-min-week',
            'bitcoin-days-destroyed-min-month',
            'bitcoin-days-destroyed-min-year',
            'blocks-size',
            'avg-block-size',
            'my-wallet-transaction-volume',
            'my-wallet-n-users',
            'my-wallet-n-tx'
            )))
            return false;

        $blockchainResponse = $this->callBlockchain('charts/' . $type, 'GET', array(
            'format' => 'json',
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * get statistic (in JSON)
     * @return mixed
     */
    public function stats()
    {
        $blockchainResponse = $this->callBlockchain('stats', 'GET', array(
            'format' => 'json',
            ));

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * Create Wallet
     * @param $password
     * @param bool $privateKey
     * @param bool $label
     * @param bool $email
     * @return mixed
     */
    public function createWallet($password, $privateKey = false, $label = false, $email = false)
    {
        $settings = array('password' => $password);

        if($privateKey && $privateKey != '')
            $settings['priv'] = $privateKey;

        if($label && $label != '')
            $settings['label'] = $label;

        if($email && $email != '')
            $settings['email'] = $email;

        $blockchainResponse = $this->callBlockchain('api/v2/create_wallet', 'GET', $settings);

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    public function receive($callback, $gap_limit)
    {
        $settings = array('gap_limit' => $gap_limit);

        $blockchainResponse = $this->callBlockchain('v2/receive/' . $callback, 'GET', $settings);

        if( ! $response = json_decode($blockchainResponse))
            return $blockchainResponse;

        return $response;
    }

    /**
     * query API
     * @param $func
     * @param $params
     * @return mixed
     */
    function __call($func, $params)
    {
        if(in_array($func, array(
            'getdifficulty',
            'getblockcount',
            'latesthash',
            'bcperblock',
            'totalbc',
            'probability',
            'hashestowin',
            'nextretarget',
            'avgtxsize',
            'avgtxvalue',
            'interval',
            'eta',
            'avgtxnumber',
            'newkey',
            'unconfirmedcount',
            '24hrprice',
            'marketcap',
            '24hrtransactioncount',
            '24hrbtcsent',
            'hashrate',
            'rejected',
            'getreceivedbyaddress',
            'getsentbyaddress',
            'addressbalance',
            'addressfirstseen',
            'txtotalbtcoutput',
            'txtotalbtcinput',
            'txfee',
            'txresult',
            'hashtontxid',
            'ntxidtohash',
            'addresstohash',
            'hashtoaddress',
            'hashpubkey',
            'addrpubkey',
            'pubkeyaddr'
            ))){
            $blockchainResponse = $this->callBlockchain('q/' . strtolower($func) . (is_array($params) ? '/' . $params[0] : ''), 'GET', array(
                'format' => 'json'
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }
    }

    /**
     * curl call
     * @param $url
     * @param string $type
     * @param array $params
     * @return mixed
     */
    public function callBlockchain($url, $type = 'GET', $params = array())
    {
        $curl = curl_init();

        $settings = array();

        if($this->config['cors'] == true)
            $params['cors'] = true;

        if($this->config['api_secret'] != '')
            $params['api_code'] = $this->config['api_secret'];

        if($type == 'GET'){
            $settings[CURLOPT_RETURNTRANSFER] = true;
        }
        elseif($type == 'POST'){
            $settings[CURLOPT_POST] = count($params);
            $settings[CURLOPT_POSTFIELDS] = http_build_query($params);
        }

        $settings[CURLOPT_URL] = 'http://blockchain.info/' . $url . (($type == 'GET' && is_array($params) && count($params) != 0) ? '?' . http_build_query($params): '');

        curl_setopt_array($curl, $settings);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}