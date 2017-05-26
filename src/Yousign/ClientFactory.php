<?php

namespace Yousign;

class ClientFactory
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * ClientFactory constructor.
     * @param Environment $environment
     * @param Authentication|null $authentication
     */
    public function __construct(Environment $environment, Authentication $authentication = null)
    {
        $this->environment = $environment;
        $this->authentication = $authentication;
    }

    /**
     * @return Client
     */
    public function createClient()
    {
        $client = new Client();
        foreach(Services::listing() as $service) {
            $options = array();

            if(!$this->environment->isSslEnable()) {
                $options = array(
                    'stream_context' => stream_context_create(array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    )
                ));
            }

            $wsdl = sprintf('%s/%s/%s?wsdl', $this->environment->getHost(), $service, $service);
            $soapClient = new \SoapClient($wsdl, $options);

            $header = new \SoapHeader('http://www.yousign.com', 'Auth', (object)(array)$this->authentication);
            $soapClient->__setSoapHeaders($header);

            $client->addSoapClient($service, $soapClient);
        }

        return $client;
    }
}
