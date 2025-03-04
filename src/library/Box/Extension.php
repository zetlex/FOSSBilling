<?php

/**
 * FOSSBilling
 *
 * @copyright FOSSBilling (https://www.fossbilling.org)
 * @license   Apache-2.0
 *
 * This file may contain code previously used in the BoxBilling project.
 * Copyright BoxBilling, Inc 2011-2021
 *
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 *
 */

    /**
     * BoxBilling extensions API wrapper
     */
    class Box_Extension
    {

        /**
         * @var \Box_Ii
         */
        protected $di = null;

        /**
         * @param \Box_Ii $di
         */
        public function setDi($di)
        {
            $this->di = $di;
        }

        /**
         * @return \Box_Ii
         */
        public function getDi()
        {
            return $this->di;
        }

        const TYPE_MOD      = 'mod';
        const TYPE_THEME    = 'theme';
        const TYPE_PG       = 'payment-gateway';
        const TYPE_SM       = 'server-manager';
        const TYPE_DR       = 'domain-registrar';
        const TYPE_HOOK     = 'hook';
        const TYPE_TRANSLATION    = 'translation';

        private $_url = 'https://extensions.fossbilling.org/api/';

        public function getExtension($id, $type = Box_Extension::TYPE_MOD)
        {
            $params = array();
            $params['return'] = 'manifest';
            $params['type'] = $type;
            $params['id'] = $id;
            return $this->_request('extension/' . $params['id'], $params);
        }

        public function getLatestExtensionVersion($id, $type = Box_Extension::TYPE_MOD)
        {
            $params = array();
            $params['type'] = $type;
            $params['id'] = $id;
            return $this->_request('extension/' . $params['id'] . '/version', $params);
        }

        public function getLatest($type = null)
        {
            $params = array();
            $params['return'] = 'manifest';
            if(!empty($type)) {
                $params['type'] = $type;
            }
            return $this->_request('list', $params);
        }

        /**
         * @param string $call
         */
        private function _request($call, array $params)
        {
            $url = $this->_url.$call;
            $client = $this->di['http_client'];
            $response = $client->request('GET', $url, [
                'timeout' => 5,
                'query' => [
                    'bb_version' => Box_Version::VERSION,
                ],
            ]);
            $json = $response->toArray();

            if(is_null($json)) {
                throw new \Box_Exception('Unable to connect to FOSSBilling extensions site.', null, 1545);
            }

            if(isset($json['error']) && is_array($json['error'])) {
                throw new Exception($json['error']['message'], 746);
            }
            return $json['result'];
        }
    }
