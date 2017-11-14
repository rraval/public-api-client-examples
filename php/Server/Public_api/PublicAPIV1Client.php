<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Server\Public_api;

/**
 * Allows public access to some of encircle's functionality
 */
class PublicAPIV1Client extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * Given search criteria (currently limited to searching everything ever),
     * returns every matching organization.
     * @param \Server\Public_api\OrganizationFilterCriteria $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ListOrganizations(\Server\Public_api\OrganizationFilterCriteria $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/server.public_api.PublicAPIV1/ListOrganizations',
        $argument,
        ['\Server\Public_api\OrganizationInfo', 'decode'],
        $metadata, $options);
    }

    /**
     * Given search criteria (currently limited to brand's organization),
     * returns every matching brand.
     * @param \Server\Public_api\BrandFilterCriteria $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ListBrands(\Server\Public_api\BrandFilterCriteria $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/server.public_api.PublicAPIV1/ListBrands',
        $argument,
        ['\Server\Public_api\BrandInfo', 'decode'],
        $metadata, $options);
    }

    /**
     * Given search criteria (currently limited to claim's organization),
     * returns every matching property claim.
     * @param \Server\Public_api\PropertyClaimFilterCriteria $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ListPropertyClaims(\Server\Public_api\PropertyClaimFilterCriteria $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/server.public_api.PublicAPIV1/ListPropertyClaims',
        $argument,
        ['\Server\Public_api\PropertyClaimInfo', 'decode'],
        $metadata, $options);
    }

    /**
     * Given search criteria, returns every picture in that claim.
     * @param \Server\Public_api\PropertyClaimPictureFilterCriteria $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ListPropertyClaimPictures(\Server\Public_api\PropertyClaimPictureFilterCriteria $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/server.public_api.PublicAPIV1/ListPropertyClaimPictures',
        $argument,
        ['\Server\Public_api\PictureInfo', 'decode'],
        $metadata, $options);
    }

    /**
     * Given search criteria, returns every rendered document in that claim.
     * Will not return documents that have not finished downloading.
     * @param \Server\Public_api\PropertyClaimDocumentFilterCriteria $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ListPropertyClaimDocuments(\Server\Public_api\PropertyClaimDocumentFilterCriteria $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/server.public_api.PublicAPIV1/ListPropertyClaimDocuments',
        $argument,
        ['\Server\Public_api\DocumentInfo', 'decode'],
        $metadata, $options);
    }

    /**
     * Creates a property claim with the given information, returning its id.
     * @param \Server\Public_api\NewPropertyClaimInfo $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function CreatePropertyClaim(\Server\Public_api\NewPropertyClaimInfo $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/server.public_api.PublicAPIV1/CreatePropertyClaim',
        $argument,
        ['\Server\Public_api\PropertyClaimId', 'decode'],
        $metadata, $options);
    }

}
